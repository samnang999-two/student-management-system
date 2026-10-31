<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentDepartmentCrudTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->bigIncrements('dept_id');
            $table->string('dept_name', 100)->unique();
            $table->string('shortname')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('student_id');
            $table->string('student_name', 100);
            $table->enum('gender', ['male', 'female']);
            $table->unsignedInteger('age');
            $table->date('dob')->nullable();
            $table->string('enrollment', 30)->nullable();
            $table->date('enrollment_date')->nullable();
            $table->unsignedBigInteger('dept_id')->nullable();
            $table->decimal('payment', 20, 2)->default(0);
            $table->string('subject');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('dept_id')->references('dept_id')->on('departments')->nullOnDelete();
        });

        $this->actingAs(User::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
        ]));
    }

    public function test_department_can_be_created_updated_searched_and_deleted(): void
    {
        $this->post(route('departments.store'), [
            'dept_name' => 'Information Technology',
            'shortname' => 'IT',
            'description' => 'Computing department',
        ])->assertRedirect(route('departments.index'));

        $department = Department::firstOrFail();
        $this->assertSame('Information Technology', $department->dept_name);

        $this->get(route('departments.index', ['search' => 'Information']))
            ->assertOk()
            ->assertSee('Information Technology');

        $this->put(route('departments.update', $department->dept_id), [
            'dept_name' => 'Computer Science',
            'shortname' => 'CS',
            'description' => 'Updated department',
        ])->assertRedirect(route('departments.index'));

        $this->assertDatabaseHas('departments', ['dept_id' => $department->dept_id, 'dept_name' => 'Computer Science']);

        $this->delete(route('departments.destroy', $department->dept_id))
            ->assertRedirect(route('departments.index'));
        $this->assertDatabaseMissing('departments', ['dept_id' => $department->dept_id]);
    }

    public function test_student_can_be_created_and_department_name_is_displayed(): void
    {
        $department = Department::create(['dept_name' => 'Information Technology', 'shortname' => 'IT']);

        $this->post(route('students.store'), [
            'student_name' => 'Dara Sok',
            'gender' => 'male',
            'age' => 21,
            'dob' => '2005-04-10',
            'enrollment' => 'IT-2026-001',
            'enrollment_date' => '2026-01-10',
            'dept_id' => $department->dept_id,
            'payment' => '450.00',
            'subject' => 'Programming',
            'description' => 'First-year student',
        ])->assertRedirect(route('students.index'));

        $student = Student::with('department')->firstOrFail();
        $this->assertSame('Information Technology', $student->department->dept_name);

        $this->get(route('students.index'))
            ->assertOk()
            ->assertSee('Dara Sok')
            ->assertSee('Information Technology');
    }

    public function test_student_can_be_updated_and_deleted(): void
    {
        $student = Student::create([
            'student_name' => 'Old Name', 'gender' => 'female', 'age' => 20,
            'payment' => '0.00', 'subject' => 'Math',
        ]);

        $this->put(route('students.update', $student->student_id), [
            'student_name' => 'New Name', 'gender' => 'female', 'age' => 21,
            'payment' => '125.50', 'subject' => 'Database',
        ])->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('students', ['student_id' => $student->student_id, 'student_name' => 'New Name']);

        $this->delete(route('students.destroy', $student->student_id))
            ->assertRedirect(route('students.index'));
        $this->assertDatabaseMissing('students', ['student_id' => $student->student_id]);
    }

    public function test_student_department_assignment_is_nullable_and_cleared_when_department_is_deleted(): void
    {
        $department = Department::create(['dept_name' => 'Business']);
        $student = Student::create([
            'student_name' => 'Unassigned Test', 'gender' => 'male', 'age' => 22,
            'dept_id' => $department->dept_id, 'payment' => '0.00', 'subject' => 'Management',
        ]);

        $department->delete();

        $this->assertDatabaseHas('students', ['student_id' => $student->student_id, 'dept_id' => null]);
    }

    public function test_student_validation_rejects_invalid_gender_and_unknown_department(): void
    {
        $this->from(route('students.create'))
            ->post(route('students.store'), [
                'student_name' => 'Invalid Student',
                'gender' => 'other',
                'age' => 20,
                'payment' => '0',
                'subject' => 'Testing',
                'dept_id' => 999,
            ])
            ->assertRedirect(route('students.create'))
            ->assertSessionHasErrors(['gender', 'dept_id']);

        $this->assertDatabaseCount('students', 0);
    }
}
