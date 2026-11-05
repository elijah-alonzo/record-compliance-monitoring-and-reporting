<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code');
            $table->string('name');
            $table->integer('units');
            $table->enum('type', ['Major', 'Minor']);
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('subject_code');
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
