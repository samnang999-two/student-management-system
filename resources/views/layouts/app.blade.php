<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Management System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800">
    <nav class="bg-slate-900 text-white shadow">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-5 py-4">
            <a href="{{ route('dashboard') }}" class="text-lg font-bold tracking-wide">Student Management System</a>
            <div class="flex flex-wrap items-center gap-4 text-sm">
                <a href="{{ route('dashboard') }}" class="hover:text-cyan-300">Dashboard</a>
                <a href="{{ route('students.index') }}" class="hover:text-cyan-300">Students</a>
                <a href="{{ route('departments.index') }}" class="hover:text-cyan-300">Departments</a>
                <span class="hidden text-slate-400 sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="rounded-lg bg-rose-600 px-3 py-2 font-medium hover:bg-rose-700">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-5 py-8">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800" role="alert">
                <p class="font-semibold">Please correct the following:</p>
                <ul class="mt-2 list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
