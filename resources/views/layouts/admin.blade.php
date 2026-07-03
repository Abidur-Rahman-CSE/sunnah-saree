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
                <a class="rounded-lg px-3 py-2 hover:bg-[#f7f0e4]" href="{{ route('admin.fashion-attributes.index') }}">Fashion Attributes</a>
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
    <script>
        document.querySelectorAll('[data-image-preview]').forEach((input) => {
            input.addEventListener('change', () => {
                const target = document.getElementById(input.dataset.imagePreview);

                if (! target) {
                    return;
                }

                target.innerHTML = '';

                Array.from(input.files).forEach((file) => {
                    const image = document.createElement('img');

                    image.src = URL.createObjectURL(file);
                    image.alt = file.name;
                    image.className = 'aspect-square rounded-lg border border-[#eadcc3] object-cover';
                    image.onload = () => URL.revokeObjectURL(image.src);

                    target.appendChild(image);
                });
            });
        });

        document.querySelectorAll('[data-product-picker]').forEach((picker) => {
            const modal = picker.querySelector('[data-picker-modal]');
            const search = picker.querySelector('[data-picker-search]');
            const category = picker.querySelector('[data-picker-category]');
            const rows = Array.from(picker.querySelectorAll('[data-picker-row]'));
            const checkboxes = Array.from(picker.querySelectorAll('[data-picker-checkbox]'));
            const selectedCount = picker.querySelector('[data-selected-count]');
            const selectedList = picker.querySelector('[data-selected-list]');
            const empty = picker.querySelector('[data-picker-empty]');

            const setModal = (isOpen) => {
                modal?.classList.toggle('hidden', ! isOpen);
                modal?.classList.toggle('flex', isOpen);
            };

            const refreshSelection = () => {
                const selected = checkboxes.filter((checkbox) => checkbox.checked);

                if (selectedCount) {
                    selectedCount.textContent = selected.length;
                }

                if (selectedList) {
                    selectedList.innerHTML = '';

                    selected.forEach((checkbox) => {
                        const chip = document.createElement('span');

                        chip.className = 'rounded-full border border-[#eadcc3] bg-white px-3 py-1 text-xs font-semibold text-[#7a1f55]';
                        chip.textContent = checkbox.dataset.productName;

                        selectedList.appendChild(chip);
                    });
                }
            };

            const filterRows = () => {
                const term = (search?.value || '').trim().toLowerCase();
                const selectedCategory = category?.value || '';
                let visibleRows = 0;

                rows.forEach((row) => {
                    const matchesSearch = ! term || row.dataset.search.includes(term);
                    const matchesCategory = ! selectedCategory || row.dataset.category === selectedCategory;
                    const isVisible = matchesSearch && matchesCategory;

                    row.classList.toggle('hidden', ! isVisible);

                    if (isVisible) {
                        visibleRows += 1;
                    }
                });

                empty?.classList.toggle('hidden', visibleRows > 0);
            };

            picker.querySelectorAll('[data-picker-open]').forEach((button) => {
                button.addEventListener('click', () => setModal(true));
            });

            picker.querySelectorAll('[data-picker-close]').forEach((button) => {
                button.addEventListener('click', () => setModal(false));
            });

            picker.querySelector('[data-picker-clear]')?.addEventListener('click', () => {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                refreshSelection();
            });

            modal?.addEventListener('click', (event) => {
                if (event.target === modal) {
                    setModal(false);
                }
            });

            search?.addEventListener('input', filterRows);
            category?.addEventListener('change', filterRows);
            checkboxes.forEach((checkbox) => checkbox.addEventListener('change', refreshSelection));

            refreshSelection();
            filterRows();
        });

        document.querySelectorAll('[data-product-type]').forEach((select) => {
            const form = select.closest('form');
            const fields = form ? Array.from(form.querySelectorAll('[data-fashion-fields]')) : [];
            const syncProductType = () => {
                fields.forEach((field) => {
                    field.classList.toggle('hidden', select.value === 'general');
                });
            };

            select.addEventListener('change', syncProductType);
            syncProductType();
        });

        document.querySelectorAll('[data-product-name]').forEach((nameInput) => {
            const form = nameInput.closest('form');
            const slugInput = form?.querySelector('[data-auto-slug]');
            const skuInput = form?.querySelector('[data-auto-sku]');
            let slugTouched = Boolean(slugInput?.value);
            let skuTouched = Boolean(skuInput?.value);
            const slugify = (value) => value
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');

            slugInput?.addEventListener('input', () => {
                slugTouched = Boolean(slugInput.value);
            });

            skuInput?.addEventListener('input', () => {
                skuTouched = Boolean(skuInput.value);
            });

            nameInput.addEventListener('input', () => {
                const slug = slugify(nameInput.value);

                if (slugInput && ! slugTouched) {
                    slugInput.value = slug;
                }

                if (skuInput && ! skuTouched) {
                    skuInput.value = slug.toUpperCase();
                }
            });
        });

        document.querySelectorAll('[data-attribute-key]').forEach((select) => {
            const form = select.closest('form');
            const textValues = form?.querySelector('[data-text-values]');
            const colorValues = form?.querySelector('[data-color-values]');
            const colorRows = form?.querySelector('[data-color-rows]');
            const template = form?.querySelector('[data-color-row-template]');
            const syncAttributeKey = () => {
                textValues?.classList.toggle('hidden', select.value === 'color');
                colorValues?.classList.toggle('hidden', select.value !== 'color');
            };

            form?.querySelector('[data-add-color-row]')?.addEventListener('click', () => {
                if (template && colorRows) {
                    colorRows.appendChild(template.content.cloneNode(true));
                }
            });

            form?.addEventListener('click', (event) => {
                const button = event.target.closest('[data-remove-color-row]');

                if (button) {
                    button.closest('[data-color-row]')?.remove();
                }
            });

            select.addEventListener('change', syncAttributeKey);
            syncAttributeKey();
        });
    </script>
</body>
</html>
