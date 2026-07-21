@extends('layouts.storefront', ['title' => 'Sign In'])

@section('content')
    <section class="mx-auto max-w-xl px-4 py-12">
        <form action="{{ route('login.store') }}" method="POST" class="rounded-lg border border-[#eadcc3] bg-white p-6 shadow-sm" data-smart-auth-form data-phone-check-url="{{ route('login.phone-check') }}">
            @csrf
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#c9a24a]">Customer Account</p>
                <h1 class="mt-2 font-serif text-3xl font-bold text-[#2f241f]">Sign in or create account</h1>
                <p class="mt-2 text-sm leading-6 text-[#6f5a50]">Phone number dile account thakle login, na thakle signup form open hobe.</p>
            </div>

            <div class="mt-6 grid gap-4">
                <x-admin.field label="Phone number">
                    <input name="phone" value="{{ old('phone') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="019XXXXXXXX" data-auth-phone autocomplete="tel">
                </x-admin.field>

                <div class="hidden rounded-lg border border-[#ead8ba] bg-[#fffaf4] px-4 py-3 text-sm font-semibold text-[#4f3d35]" data-auth-existing>
                    <span data-auth-existing-name></span> অ্যাকাউন্ট পাওয়া গেছে। পাসওয়ার্ড দিয়ে লগইন করুন।
                </div>

                <div class="hidden grid gap-4" data-auth-new>
                    <x-admin.field label="Name">
                        <input name="name" value="{{ old('name') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Your name" data-auth-name autocomplete="name">
                    </x-admin.field>
                    <x-admin.field label="Email (optional)">
                        <input name="email" value="{{ old('email') }}" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Email address" autocomplete="email">
                    </x-admin.field>
                </div>

                <div class="hidden grid gap-4" data-auth-passwords>
                    <x-admin.field label="Password">
                        <input name="password" type="password" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Password" autocomplete="current-password">
                    </x-admin.field>
                    <div class="hidden" data-auth-confirm>
                        <x-admin.field label="Confirm password">
                            <input name="password_confirmation" type="password" class="rounded-lg border border-[#dfcda9] px-4 py-3" placeholder="Confirm password" autocomplete="new-password">
                        </x-admin.field>
                    </div>
                </div>

                @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
                <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white disabled:cursor-not-allowed disabled:bg-[#c7b6a3]" data-auth-submit disabled>Continue</button>
            </div>
        </form>
    </section>

    <script>
        document.querySelectorAll('[data-smart-auth-form]').forEach((form) => {
            const phone = form.querySelector('[data-auth-phone]');
            const existingBox = form.querySelector('[data-auth-existing]');
            const existingName = form.querySelector('[data-auth-existing-name]');
            const newFields = form.querySelector('[data-auth-new]');
            const nameInput = form.querySelector('[data-auth-name]');
            const passwordFields = form.querySelector('[data-auth-passwords]');
            const confirmField = form.querySelector('[data-auth-confirm]');
            const submit = form.querySelector('[data-auth-submit]');
            let timer = null;

            const setMode = (mode, name = '') => {
                const isKnown = mode === 'known';
                const isNew = mode === 'new';

                existingBox?.classList.toggle('hidden', ! isKnown);
                newFields?.classList.toggle('hidden', ! isNew);
                passwordFields?.classList.toggle('hidden', ! mode);
                confirmField?.classList.toggle('hidden', ! isNew);
                submit.disabled = ! mode;
                submit.textContent = isKnown ? 'Login' : (isNew ? 'Create Account' : 'Continue');
                existingName.textContent = name || 'Customer';

                if (nameInput) {
                    nameInput.required = isNew;
                }
            };

            const checkPhone = async () => {
                const value = phone.value.trim();

                if (value.length < 6) {
                    setMode(null);

                    return;
                }

                submit.disabled = true;
                submit.textContent = 'Checking...';

                try {
                    const url = new URL(form.dataset.phoneCheckUrl, window.location.origin);
                    url.searchParams.set('phone', value);
                    const response = await fetch(url, { headers: { Accept: 'application/json' } });
                    const payload = await response.json();

                    setMode(payload.exists ? 'known' : 'new', payload.name);
                } catch (error) {
                    setMode('new');
                }
            };

            phone?.addEventListener('input', () => {
                window.clearTimeout(timer);
                setMode(null);
                timer = window.setTimeout(checkPhone, 350);
            });

            if (phone?.value) {
                checkPhone();
            }
        });
    </script>
@endsection
