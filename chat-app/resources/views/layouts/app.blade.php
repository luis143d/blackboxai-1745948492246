<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Chat App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-gray-800">@yield('header', 'Chat App')</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
        </form>
    </header>
    <main class="flex-grow container mx-auto p-4">
        @yield('content')
    </main>
</body>
</html>
