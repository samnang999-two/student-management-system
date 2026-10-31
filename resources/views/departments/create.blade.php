@extends('layouts.app')

@section('title', $pageTitle.' | Student Management System')

@section('content')
<div class="mx-auto max-w-2xl"><div class="mb-6"><a href="{{ route('departments.index') }}" class="text-sm font-semibold text-violet-700 hover:underline">← Back to Departments</a><h1 class="mt-3 text-3xl font-bold">{{ $pageTitle }}</h1></div>
<form method="POST" action="{{ $formAction }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">@csrf @if($formMethod !== 'POST') @method($formMethod) @endif
<label class="block"><span class="mb-1 block text-sm font-semibold">Department Name *</span><input name="dept_name" required maxlength="100" value="{{ old('dept_name', $department->dept_name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-violet-600 focus:outline-none">@error('dept_name')<span class="text-xs text-rose-600">{{ $message }}</span>@enderror</label>
<label class="block"><span class="mb-1 block text-sm font-semibold">Short Name</span><input name="shortname" maxlength="255" value="{{ old('shortname', $department->shortname) }}" placeholder="e.g. IT, BUS, ENG" class="w-full rounded-lg border border-slate-300 px-3 py-2">@error('shortname')<span class="text-xs text-rose-600">{{ $message }}</span>@enderror</label>
<label class="block"><span class="mb-1 block text-sm font-semibold">Description</span><textarea name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2">{{ old('description', $department->description) }}</textarea>@error('description')<span class="text-xs text-rose-600">{{ $message }}</span>@enderror</label>
<div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><a href="{{ route('departments.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold">Cancel</a><button class="rounded-lg bg-violet-700 px-5 py-2 text-sm font-semibold text-white hover:bg-violet-800">Save Department</button></div>
</form></div>
@endsection
