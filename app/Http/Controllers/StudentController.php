<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with('department')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());
                $query->where(function ($builder) use ($search) {
                    $builder->where('student_name', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('enrollment', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('dept_id'), fn ($query) => $query->where('dept_id', $request->integer('dept_id')))
            ->orderByDesc('student_id')
            ->paginate(10)
            ->withQueryString();

        $departments = Department::orderBy('dept_name')->get(['dept_id', 'dept_name']);

        return view('student.list', compact('students', 'departments'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('dept_name')->get(['dept_id', 'dept_name']);

        return view('student.create', [
            'student' => new Student(),
            'departments' => $departments,
            'formAction' => route('students.store'),
            'formMethod' => 'POST',
            'pageTitle' => 'Add Student',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Student::create($this->validatedData($request));

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(string $student): View
    {
        $record = Student::findOrFail($student);
        $departments = Department::orderBy('dept_name')->get(['dept_id', 'dept_name']);

        return view('student.create', [
            'student' => $record,
            'departments' => $departments,
            'formAction' => route('students.update', $record->student_id),
            'formMethod' => 'PUT',
            'pageTitle' => 'Edit Student',
        ]);
    }

    public function update(Request $request, string $student): RedirectResponse
    {
        $record = Student::findOrFail($student);
        $record->update($this->validatedData($request));

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(string $student): RedirectResponse
    {
        Student::findOrFail($student)->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'student_name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'age' => ['required', 'integer', 'min:1', 'max:150'],
            'dob' => ['nullable', 'date', 'before_or_equal:today'],
            'enrollment' => ['nullable', 'string', 'max:30'],
            'enrollment_date' => ['nullable', 'date'],
            'dept_id' => ['nullable', 'integer', Rule::exists('departments', 'dept_id')],
            'payment' => ['required', 'numeric', 'min:0', 'max:999999999999999999.99'],
            'subject' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
