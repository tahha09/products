<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Products App')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-blue-700 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center px-6 py-4">
            <h1 class="text-xl font-bold">🛍️ Products App</h1>
            <div class="space-x-6">
                <a href="{{ route('products.index') }}" class="hover:text-yellow-300">All Products</a>
                <a href="{{ route('products.create') }}" class="hover:text-yellow-300">Add Product</a>
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @if(session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-3">
        <p>© {{ date('Y') }} Products App | Developed by <strong>Taha</strong></p>
    </footer>
</body>
</html>
