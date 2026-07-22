@extends('layouts.storefront', ['title' => 'Admin Login'])

@section('content')
    <section class="bg-[#fbf6ee] px-4 py-12">
        <div class="mx-auto grid max-w-5xl overflow-hidden rounded-lg border border-[#eadcc3] bg-white shadow-sm md:grid-cols-[0.9fr_1.1fr]">
            <div class="bg-[#3b2922] p-8 text-white md:p-10">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#e9c86d]">Admin Access</p>
                <h1 class="mt-4 font-serif text-4xl font-bold leading-tight">Sunnah Sharee control panel</h1>
                <p class="mt-4 text-sm leading-6 text-[#eadcc3]">অ্যাডমিন প্যানেলে ঢুকতে আপনার ইমেইল অথবা ফোন নম্বর এবং পাসওয়ার্ড দিন।</p>
                <div class="mt-8 rounded-lg border border-white/15 bg-white/10 p-4 text-sm text-[#f8ead0]">
                    Customer login আলাদা রাখা হয়েছে, তাই শুধু admin account দিয়েই এখানে login হবে।
                </div>
            </div>

            <form action="{{ route('admin.login.store') }}" method="POST" class="p-6 md:p-10">
                @csrf
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a24a]">Secure Login</p>
                    <h2 class="mt-2 font-serif text-3xl font-bold text-[#2f241f]">Admin login</h2>
                </div>

                <div class="mt-6 grid gap-4">
                    <x-admin.field label="Email or phone">
                        <input
                            name="identifier"
                            value="{{ old('identifier') }}"
                            class="rounded-lg border border-[#dfcda9] px-4 py-3"
                            placeholder="admin@sunnahsharee.test or 017XXXXXXXX"
                            autocomplete="username"
                            autofocus
                        >
                    </x-admin.field>

                    <x-admin.field label="Password">
                        <input
                            name="password"
                            type="password"
                            class="rounded-lg border border-[#dfcda9] px-4 py-3"
                            placeholder="Password"
                            autocomplete="current-password"
                        >
                    </x-admin.field>

                    <label class="flex items-center gap-2 text-sm font-semibold text-[#5d4a43]">
                        <input type="checkbox" name="remember" value="1" class="rounded border-[#dfcda9] text-[#7a1f55]">
                        Remember me
                    </label>

                    @if ($errors->any())
                        <p class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ $errors->first() }}</p>
                    @endif

                    <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white shadow-sm transition hover:bg-[#641746]">Login to admin</button>
                    <a href="{{ route('login') }}" class="text-center text-sm font-semibold text-[#7a1f55]">Customer login e jan</a>
                </div>
            </form>
        </div>
    </section>
@endsection
