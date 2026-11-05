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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('employment_type', ['Shared Faculty', 'Full-Time Faculty', 'Part-Time Faculty']);
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
