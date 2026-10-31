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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employeename');
            $table->enum("gender", ["Male", "Female"]);
            $table->integer('age');
            $table->string('position');
            $table->decimal('salary', 20, 2)->default(0);
            $table->timestamp('dob');
            $table->string('phone')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->foreignId('departmentid')
                  ->constrained('departments')
                  ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
