# Student Management System — Option B Implementation

This update implements the Student and Department CRUD application and adds four staged MySQL migrations. It preserves the seven historical migration files and does not include a `.env` file or a live database dump.

## Target schema

- `departments`: `dept_id` primary key, `dept_name` unique `VARCHAR(100)`, `shortname`, `description`, timestamps.
- `students`: `student_id` primary key, `student_name`, `gender`, `age`, `dob DATE NULL`, `enrollment`, `enrollment_date`, `dept_id NULL`, `payment`, `subject`, `description`, timestamps.
- `employees`: existing columns retained, with `departmentid` transitioned to `department_id` and a foreign key to `departments.dept_id` (`ON DELETE CASCADE`, `ON UPDATE NO ACTION`).
- Student department relationship is nullable and uses `ON DELETE SET NULL`.
- No `manager_id` or `location_id` columns are introduced. No student department assignment is inferred.

## Important safety behavior

- The migration sequence is staged: expand, backfill/validate, key/FK transition, contract.
- The backfill migration intentionally stops if any existing student `dob` value is non-null. That prevents an unapproved `TIMESTAMP` to `DATE` timezone conversion. The current verified database had zero student rows. If this guard stops, do not bypass it; decide the DOB business timezone first.
- The contract migration removes legacy columns and is not automatically reversible. `down()` methods intentionally throw instead of silently dropping or reconstructing data.
- Keep the verified backup outside the project folder too. Do not run `migrate:fresh`, `db:wipe`, `DROP DATABASE`, or `TRUNCATE`.

## Apply to the existing Windows project

1. Stop Laravel, queue workers, scheduler tasks, and any other process that can write to this database.
2. Keep a copy of the existing project and the verified SQL backup. Do not overwrite `.env`, `backups/`, `vendor/`, or `storage/` from an archive.
3. Copy the updated `app/`, `database/migrations/`, `database/seeders/`, `resources/views/`, `routes/`, and `tests/` folders into `C:\laragon\www\LARAVEL13SV7`, allowing the included files to replace their matching source files. Preserve your local `.env`.
4. Check the configured database is still `laravel13sv7`:

   ```powershell
   php artisan config:clear
   php artisan migrate:status
   ```

   Confirm the seven historical migrations are `Ran` and the four Option B migrations are `Pending`.

5. Review the four new migration files. Run tests before touching the live database:

   ```powershell
   php artisan test
   ```

6. For the live migration, use a maintenance window. First take a fresh backup and restore it to a new verification database. Then enable maintenance mode and stop background writers:

   ```powershell
   php artisan down
   ```

7. Only after confirming the backup, restore verification, and maintenance window, run:

   ```powershell
   php artisan migrate --force
   ```

8. Verify:

   ```powershell
   php artisan migrate:status
   php artisan route:list
   ```

   Then test login, Student CRUD, Department CRUD, search, validation, department dropdown, and department name display in a browser.

9. Take the application out of maintenance only after all checks pass:

   ```powershell
   php artisan up
   ```

## Application features implemented

- Authenticated dashboard with student and department totals.
- Department list, search, create, edit, delete, validation, and student counts.
- Student list, search, department filter, create, edit, delete, validation, pagination, and relationship display.
- Student form includes department dropdown with an `Unassigned` option.
- Deleting a department leaves its students intact and clears their department assignment.
- Feature tests cover Department CRUD, Student CRUD, search/display, nullable department behavior, and validation.
