<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('subject_code');
            $table->string('name');
            $table->string('description');
            $table->string('instructor');
            $table->string('schedule');
            $table->json('grades')->nullable();
            $table->double('average_grade')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date_taken');
            $table->timestamps();

            $table->foreignId('student_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
