<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Student Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center text-slate-800 mb-6">Register</h1>

        @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
            </div>

            <div class="mb-4 text-right">
                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">Already have an account?</a>
            </div>

            <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                Register
            </button>
        </form>
    </div>
</body>
</html>
