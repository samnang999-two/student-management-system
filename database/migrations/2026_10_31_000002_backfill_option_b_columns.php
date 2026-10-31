<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tooLongDepartmentNames = DB::table('departments')
            ->whereRaw('CHAR_LENGTH(departmentname) > 100')
            ->count();

        if ($tooLongDepartmentNames > 0) {
            throw new RuntimeException('Option B migration stopped: one or more department names exceed 100 characters. No legacy columns have been removed.');
        }

        $duplicateDepartmentNames = DB::table('departments')
            ->select('departmentname')
            ->groupBy('departmentname')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        if ($duplicateDepartmentNames > 0) {
            throw new RuntimeException('Option B migration stopped: duplicate department names prevent creation of the target unique key.');
        }

        // A TIMESTAMP-to-DATE conversion needs an approved business timezone.
        // Do not guess: stop if existing DOB values are present. The current verified
        // database has zero student rows, so this guard should pass without conversion.
        if (DB::table('students')->whereNotNull('dob')->exists()) {
            throw new RuntimeException('Option B migration stopped: student DOB values exist. Confirm the intended timezone before converting TIMESTAMP to DATE.');
        }

        DB::table('departments')->update([
            'dept_id' => DB::raw('id'),
            'dept_name' => DB::raw('departmentname'),
        ]);

        DB::table('students')->update([
            'student_id' => DB::raw('studentid'),
            'student_name' => DB::raw('studentname'),
        ]);

        DB::table('employees')->update([
            'department_id' => DB::raw('departmentid'),
        ]);

        $invalidDepartments = DB::table('departments')
            ->whereNull('dept_id')
            ->orWhereNull('dept_name')
            ->orWhereColumn('dept_id', '<>', 'id')
            ->orWhereColumn('dept_name', '<>', 'departmentname')
            ->count();

        $invalidStudents = DB::table('students')
            ->whereNull('student_id')
            ->orWhereNull('student_name')
            ->orWhereColumn('student_id', '<>', 'studentid')
            ->orWhereColumn('student_name', '<>', 'studentname')
            ->count();

        $invalidEmployees = DB::table('employees')
            ->whereNull('department_id')
            ->orWhereColumn('department_id', '<>', 'departmentid')
            ->count();

        $orphanEmployees = DB::table('employees as e')
            ->leftJoin('departments as d', 'e.department_id', '=', 'd.id')
            ->whereNull('d.id')
            ->count();

        if ($invalidDepartments || $invalidStudents || $invalidEmployees || $orphanEmployees) {
            throw new RuntimeException('Option B backfill validation failed. Keep the application offline and inspect staging columns before continuing.');
        }
    }

    public function down(): void
    {
        throw new RuntimeException('Option B backfill is not automatically reversible. Use the documented recovery plan.');
    }
};
