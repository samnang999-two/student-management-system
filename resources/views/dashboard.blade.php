@extends('layouts.app')

@section('title', 'Dashboard | Student Management System')

@section('content')
<div class="mb-7">
    <p class="text-sm font-semibold uppercase tracking-wider text-cyan-700">Overview</p>
    <h1 class="mt-1 text-3xl font-bold text-slate-900">Dashboard</h1>
    <p class="mt-2 text-slate-600">Welcome back, {{ auth()->user()->name }}.</p>
</div>
<div class="mb-6 grid gap-4 sm:grid-cols-2">
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Total Students</p><p class="mt-1 text-3xl font-bold">{{ $studentCount }}</p></div>
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">Total Departments</p><p class="mt-1 text-3xl font-bold">{{ $departmentCount }}</p></div>
</div>
<div class="grid gap-5 md:grid-cols-2">
    <a href="{{ route('students.index') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between"><span class="text-sm font-semibold text-slate-500">STUDENT MANAGEMENT</span><span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-800">CRUD</span></div>
        <h2 class="mt-3 text-2xl font-bold">Students</h2>
        <p class="mt-2 text-sm text-slate-600">Create, view, search, update, and delete student records.</p>
        <span class="mt-5 inline-block text-sm font-semibold text-cyan-700">Open Students →</span>
    </a>
    <a href="{{ route('departments.index') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between"><span class="text-sm font-semibold text-slate-500">DEPARTMENT MANAGEMENT</span><span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-800">CRUD</span></div>
        <h2 class="mt-3 text-2xl font-bold">Departments</h2>
        <p class="mt-2 text-sm text-slate-600">Manage departments and view their assigned student counts.</p>
        <span class="mt-5 inline-block text-sm font-semibold text-violet-700">Open Departments →</span>
    </a>
</div>
@endsection
