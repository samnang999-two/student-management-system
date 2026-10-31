<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Final guard: all new keys and relationships must be in place before
        // legacy columns are removed. This migration is intentionally irreversible.
        $employeeOrphans = DB::table('employees as e')
            ->leftJoin('departments as d', 'e.department_id', '=', 'd.dept_id')
            ->whereNull('d.dept_id')
            ->count();

        $studentOrphans = DB::table('students as s')
            ->leftJoin('departments as d', 's.dept_id', '=', 'd.dept_id')
            ->whereNotNull('s.dept_id')
            ->whereNull('d.dept_id')
            ->count();

        if ($employeeOrphans || $studentOrphans) {
            throw new RuntimeException('Option B contract stopped: one or more rows reference a missing department.');
        }

        DB::statement(<<<'SQL'
            ALTER TABLE departments
                DROP INDEX departments_id_legacy_unique,
                DROP COLUMN id,
                DROP COLUMN departmentname,
                MODIFY dept_name VARCHAR(100) NOT NULL
        SQL);

        DB::statement(<<<'SQL'
            ALTER TABLE students
                DROP INDEX students_studentid_legacy_unique,
                DROP COLUMN studentid,
                DROP COLUMN studentname,
                DROP COLUMN dob,
                CHANGE COLUMN dob_date dob DATE NULL,
                MODIFY student_name VARCHAR(100) NOT NULL
        SQL);

        DB::statement('ALTER TABLE employees DROP COLUMN departmentid');
    }

    public function down(): void
    {
        throw new RuntimeException('Option B contract removes legacy columns and cannot be rolled back automatically. Restore the verified backup to an isolated database and follow the recovery plan.');
    }
};
