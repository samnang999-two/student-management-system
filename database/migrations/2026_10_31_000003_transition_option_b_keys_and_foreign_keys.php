<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (
            DB::table('departments')->whereNull('dept_id')->exists()
            || DB::table('departments')->whereNull('dept_name')->exists()
            || DB::table('students')->whereNull('student_id')->exists()
            || DB::table('students')->whereNull('student_name')->exists()
            || DB::table('employees')->whereNull('department_id')->exists()
        ) {
            throw new RuntimeException('Option B key transition stopped: staging columns contain NULL values.');
        }

        // Keep the legacy keys until their dependants have been moved.
        DB::statement('ALTER TABLE departments ADD UNIQUE KEY departments_dept_id_unique (dept_id)');
        DB::statement('ALTER TABLE departments ADD UNIQUE KEY departments_dept_name_unique (dept_name)');
        DB::statement('ALTER TABLE students ADD UNIQUE KEY students_student_id_unique (student_id)');

        // Remove the old employee relationship before changing the referenced key.
        DB::statement('ALTER TABLE employees DROP FOREIGN KEY employees_departmentid_foreign');

        // Promote the copied identifiers to the new AUTO_INCREMENT primary keys.
        // Legacy identifiers remain unique until the contract migration.
        DB::statement(<<<'SQL'
            ALTER TABLE departments
                MODIFY id BIGINT UNSIGNED NOT NULL,
                MODIFY dept_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                DROP PRIMARY KEY,
                DROP INDEX departments_dept_id_unique,
                ADD PRIMARY KEY (dept_id),
                ADD UNIQUE KEY departments_id_legacy_unique (id)
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE students
                MODIFY studentid BIGINT UNSIGNED NOT NULL,
                MODIFY student_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                DROP PRIMARY KEY,
                DROP INDEX students_student_id_unique,
                ADD PRIMARY KEY (student_id),
                ADD UNIQUE KEY students_studentid_legacy_unique (studentid)
        SQL);

        DB::statement('ALTER TABLE employees MODIFY department_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE employees ADD INDEX employees_department_id_index (department_id)');
        DB::statement(<<<'SQL'
            ALTER TABLE employees
            ADD CONSTRAINT employees_department_id_foreign
            FOREIGN KEY (department_id) REFERENCES departments (dept_id)
            ON DELETE CASCADE ON UPDATE NO ACTION
        SQL);

        DB::statement('ALTER TABLE students ADD INDEX students_dept_id_index (dept_id)');
        DB::statement(<<<'SQL'
            ALTER TABLE students
            ADD CONSTRAINT students_dept_id_foreign
            FOREIGN KEY (dept_id) REFERENCES departments (dept_id)
            ON DELETE SET NULL ON UPDATE NO ACTION
        SQL);
    }

    public function down(): void
    {
        throw new RuntimeException('Option B key and foreign-key transition is not automatically reversible. Use the documented recovery plan.');
    }
};
