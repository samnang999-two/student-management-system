@extends('layouts.app')

@section('title', 'Students | Student Management System')

@section('content')
<div class="mb-6 flex flex-wrap items-end justify-between gap-4">
    <div><p class="text-sm font-semibold uppercase tracking-wider text-cyan-700">Records</p><h1 class="mt-1 text-3xl font-bold">Students</h1><p class="mt-1 text-sm text-slate-500">Manage student information and department assignments.</p></div>
    <a href="{{ route('students.create') }}" class="rounded-lg bg-cyan-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-cyan-800">+ Add Student</a>
</div>
<form method="GET" action="{{ route('students.index') }}" class="mb-5 grid gap-3 rounded-xl border border-slate-200 bg-white p-4 md:grid-cols-[1fr_240px_auto_auto]">
    <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, subject, enrollment..." class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-100">
    <select name="dept_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-cyan-600 focus:outline-none"><option value="">All departments</option>@foreach($departments as $department)<option value="{{ $department->dept_id }}" @selected((string)request('dept_id') === (string)$department->dept_id)>{{ $department->dept_name }}</option>@endforeach</select>
    <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900">Search</button>
    <a href="{{ route('students.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
</form>
<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">Student</th><th class="px-4 py-3">Gender / Age</th><th class="px-4 py-3">Subject</th><th class="px-4 py-3">Department</th><th class="px-4 py-3">Enrollment</th><th class="px-4 py-3 text-right">Actions</th></tr></thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($students as $student)
                <tr class="hover:bg-slate-50"><td class="whitespace-nowrap px-4 py-3 font-mono text-slate-500">{{ $student->student_id }}</td><td class="px-4 py-3"><div class="font-semibold text-slate-900">{{ $student->student_name }}</div><div class="text-xs text-slate-500">{{ $student->dob?->format('d M Y') ?? 'DOB not set' }}</div></td><td class="whitespace-nowrap px-4 py-3 capitalize">{{ $student->gender }} / {{ $student->age }}</td><td class="px-4 py-3">{{ $student->subject }}</td><td class="px-4 py-3">{{ $student->department?->dept_name ?? 'Unassigned' }}</td><td class="px-4 py-3">{{ $student->enrollment ?: '—' }}</td><td class="whitespace-nowrap px-4 py-3 text-right"><a href="{{ route('students.edit', $student->student_id) }}" class="font-semibold text-cyan-700 hover:underline">Edit</a><form method="POST" action="{{ route('students.destroy', $student->student_id) }}" class="ml-3 inline" onsubmit="return confirm('Delete this student?')">@csrf @method('DELETE')<button class="font-semibold text-rose-600 hover:underline">Delete</button></form></td></tr>
            @empty
                <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500">No students found. Add your first student to get started.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($students->hasPages())<div class="border-t border-slate-200 px-4 py-3">{{ $students->links() }}</div>@endif
</div>
@endsection
