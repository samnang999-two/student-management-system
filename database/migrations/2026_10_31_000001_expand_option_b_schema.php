<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->string('dept_name', 100)->nullable();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('student_name', 100)->nullable();
            $table->date('dob_date')->nullable();
            $table->string('enrollment', 30)->nullable();
            $table->date('enrollment_date')->nullable();
            $table->unsignedBigInteger('dept_id')->nullable();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable();
        });
    }

    public function down(): void
    {
        throw new RuntimeException('Option B schema expansion is not automatically reversible. Restore the verified backup or use the documented recovery plan.');
    }
};
