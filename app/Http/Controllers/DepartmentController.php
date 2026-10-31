<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function index(Request $request): View
    {
        $departments = Department::query()
            ->withCount('students')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->string('search')->toString());
                $query->where(function ($builder) use ($search) {
                    $builder->where('dept_name', 'like', "%{$search}%")
                        ->orWhere('shortname', 'like', "%{$search}%");
                });
            })
            ->orderBy('dept_name')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('departments.create', [
            'department' => new Department(),
            'formAction' => route('departments.store'),
            'formMethod' => 'POST',
            'pageTitle' => 'Add Department',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Department::create($this->validatedData($request));

        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(string $department): View
    {
        $record = Department::findOrFail($department);

        return view('departments.create', [
            'department' => $record,
            'formAction' => route('departments.update', $record->dept_id),
            'formMethod' => 'PUT',
            'pageTitle' => 'Edit Department',
        ]);
    }

    public function update(Request $request, string $department): RedirectResponse
    {
        $record = Department::findOrFail($department);
        $record->update($this->validatedData($request, $record));

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(string $department): RedirectResponse
    {
        $record = Department::findOrFail($department);
        $record->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted. Students assigned to it will become unassigned.');
    }

    private function validatedData(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'dept_name' => [
                'required', 'string', 'max:100',
                Rule::unique('departments', 'dept_name')->ignore($department?->dept_id, 'dept_id'),
            ],
            'shortname' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
