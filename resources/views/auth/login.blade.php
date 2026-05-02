@extends('layouts.storefront', ['title' => 'Login'])

@section('content')
    <section class="mx-auto max-w-md px-4 py-12">
        <form action="{{ route('login.store') }}" method="POST" class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm">
            @csrf
            <h1 class="font-serif text-3xl font-bold">Login</h1>
            <div class="mt-5 grid gap-4">
                <x-admin.field label="Email"><input name="email" value="{{ old('email') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Email"></x-admin.field>
                <x-admin.field label="Password"><input name="password" type="password" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Password"></x-admin.field>
                @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
                <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Login</button>
                <a href="{{ route('register') }}" class="text-center text-sm font-semibold text-[#7a1f55]">Create customer account</a>
            </div>
        </form>
    </section>
@endsection
