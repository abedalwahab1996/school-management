<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_teacher', function (Blueprint $table) {
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->primary(['teacher_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_teacher');
    }
};
