<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin · Sunnah Sharee Ghar' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f5f0] text-[#2f241f]">
    <div class="min-h-screen lg:flex">
        <aside class="border-r border-[#e5ded0] bg-white p-5 lg:w-72">
            <a href="{{ route('admin.dashboard') }}" class="block">
                <img src="{{ asset('images/Logo/sunnah_logo_bgr.png') }}" alt="Sunnah Sharee Ghar Admin" class="h-16 w-auto object-contain">
            </a>
            <nav class="mt-8 grid gap-2 text-sm font-semibold">
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.categories.index') }}">Categories</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.products.index') }}">Products</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.collections.index') }}">Collections</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.offers.index') }}">Offers</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.combos.index') }}">Combos</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.coupons.index') }}">Coupons</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.banners.index') }}">Banners</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.customers.index') }}">Customers</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.payments.index') }}">Payments</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.settings.edit') }}">Settings</a>
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('home') }}">View Store</a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="rounded-lg px-3 py-2 text-left hover:bg-[#f7f0e4]">Logout</button></form>
            </nav>
        </aside>
        <div class="flex-1">
            <header class="border-b border-[#e5ded0] bg-white px-6 py-4">
                <h1 class="text-xl font-bold">{{ $heading ?? 'Admin' }}</h1>
            </header>
            <main class="p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
