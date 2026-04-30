@extends('layouts.storefront', ['title' => 'Register'])

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12">
        <form action="{{ route('register.store') }}" method="POST" class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            @csrf
            <h1 class="font-serif text-3xl font-bold">Create Account</h1>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <input name="name" value="{{ old('name') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Name">
                <input name="phone" value="{{ old('phone') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Phone">
                <input name="email" value="{{ old('email') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3 md:col-span-2" placeholder="Email">
                <textarea name="address" class="rounded-lg border border-[#dfcda9] px-4 py-3 md:col-span-2" placeholder="Address">{{ old('address') }}</textarea>
                <input name="password" type="password" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Password">
                <input name="password_confirmation" type="password" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Confirm password">
                @if ($errors->any())<p class="text-sm text-red-700 md:col-span-2">{{ $errors->first() }}</p>@endif
                <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white md:col-span-2">Register</button>
            </div>
        </form>
    </section>
@endsection
