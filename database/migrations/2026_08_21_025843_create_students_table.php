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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('studentid');
            $table->string('studentname',100);
            $table->enum('gender', ['male', 'female']);
            $table->integer('age');
            $table->timestamp('dob')->nullable();
            $table->decimal('payment', 20, 2)->default(0);
            $table->string('subject', 255);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
