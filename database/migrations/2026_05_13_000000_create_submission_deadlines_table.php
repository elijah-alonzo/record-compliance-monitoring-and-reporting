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
        Schema::create('submission_deadlines', function (Blueprint $table) {
            $table->id();
            $table->date('first_semester_deadline');
            $table->date('second_semester_deadline');
            $table->date('summer_semester_deadline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_deadlines');
    }
};
