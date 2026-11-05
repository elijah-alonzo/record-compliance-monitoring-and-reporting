<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculty_load', function (Blueprint $table) {
            $table->id();
            $table->foreignId('load_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['load_id', 'faculty_id']);
        });

        if (Schema::hasColumn('loads', 'faculty_id')) {
            $now = now();

            DB::table('loads')
                ->whereNotNull('faculty_id')
                ->orderBy('id')
                ->chunkById(100, function ($loads) use ($now): void {
                    $rows = [];

                    foreach ($loads as $load) {
                        $rows[] = [
                            'load_id' => $load->id,
                            'faculty_id' => $load->faculty_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if ($rows !== []) {
                        DB::table('faculty_load')->insertOrIgnore($rows);
                    }
                });

            Schema::table('loads', function (Blueprint $table): void {
                $table->dropForeign(['faculty_id']);
                $table->dropUnique('unique_load_assignment');
                $table->dropColumn('faculty_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('loads', 'faculty_id')) {
            Schema::table('loads', function (Blueprint $table): void {
                $table->foreignId('faculty_id')->nullable()->constrained()->onDelete('cascade');
            });

            DB::table('faculty_load')
                ->orderBy('load_id')
                ->chunkById(100, function ($rows): void {
                    foreach ($rows as $row) {
                        DB::table('loads')
                            ->where('id', $row->load_id)
                            ->whereNull('faculty_id')
                            ->update(['faculty_id' => $row->faculty_id]);
                    }
                });

            Schema::table('loads', function (Blueprint $table): void {
                $table->unique(
                    ['faculty_id', 'subject_id', 'section_id', 'year', 'semester'],
                    'unique_load_assignment'
                );
            });
        }

        Schema::dropIfExists('faculty_load');
    }
};
