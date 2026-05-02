@extends('layouts.admin', ['heading' => $attribute->exists ? 'Edit Fashion Attribute' : 'Add Fashion Attribute'])

@section('content')
    @php
        $attributeValues = collect($attribute->values ?? []);
        $colorRows = old('color_names')
            ? collect(old('color_names'))->map(fn ($name, $index) => ['name' => $name, 'code' => old("color_codes.$index", '#c9a24a')])
            : $attributeValues->map(fn ($value) => is_array($value) ? $value : ['name' => $value, 'code' => '#c9a24a']);
        $valuesText = old('values_text', $attributeValues->map(fn ($value) => is_array($value) ? ($value['name'] ?? '') : $value)->implode("\n"));
    @endphp
    <form action="{{ $attribute->exists ? route('admin.fashion-attributes.update', $attribute) : route('admin.fashion-attributes.store') }}" method="POST" class="max-w-2xl rounded-lg border border-[#e5ded0] bg-white p-6 shadow-sm">
        @csrf
        @if ($attribute->exists) @method('PUT') @endif
        <div class="grid gap-4">
            <x-admin.field label="Display name">
                <input name="name" value="{{ old('name', $attribute->name) }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Color">
            </x-admin.field>
            <x-admin.field label="Key">
                <select name="key" class="rounded-lg border border-[#ddd4c4] px-4 py-3" data-attribute-key>
                    @foreach (['sharee_type' => 'Fashion type / Sharee type', 'fabric' => 'Fabric', 'work_type' => 'Work type', 'color' => 'Color', 'occasion' => 'Occasion'] as $key => $label)
                        <option value="{{ $key }}" @selected(old('key', $attribute->key) === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </x-admin.field>
            <div data-text-values>
                <x-admin.field label="Values">
                    <textarea name="values_text" rows="8" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="One value per line">{{ $valuesText }}</textarea>
                </x-admin.field>
                <p class="mt-2 text-sm text-[#8d786d]">Write one value per line, or comma-separated. Example: Katan Sharee, Banarasi Sharee.</p>
            </div>
            <div class="grid gap-3 rounded-lg border border-[#eadcc3] bg-[#fffaf3] p-4" data-color-values>
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-serif text-xl font-bold">Color values</h2>
                        <p class="text-sm text-[#8d786d]">Add each color name with its hex code.</p>
                    </div>
                    <button type="button" class="rounded-lg bg-[#7a1f55] px-3 py-2 text-sm font-semibold text-white" data-add-color-row>Add color</button>
                </div>
                <div class="grid gap-3" data-color-rows>
                    @forelse ($colorRows as $row)
                        <div class="grid gap-2 rounded-lg bg-white p-3 md:grid-cols-[1fr_120px_auto] md:items-end" data-color-row>
                            <x-admin.field label="Color name"><input name="color_names[]" value="{{ $row['name'] ?? '' }}" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Royal Blue"></x-admin.field>
                            <x-admin.field label="Code"><input type="color" name="color_codes[]" value="{{ $row['code'] ?? '#c9a24a' }}" class="h-12 rounded-lg border border-[#ddd4c4] p-1"></x-admin.field>
                            <button type="button" class="rounded-lg border border-red-200 px-3 py-3 text-sm font-semibold text-red-700" data-remove-color-row>Remove</button>
                        </div>
                    @empty
                        <div class="grid gap-2 rounded-lg bg-white p-3 md:grid-cols-[1fr_120px_auto] md:items-end" data-color-row>
                            <x-admin.field label="Color name"><input name="color_names[]" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Royal Blue"></x-admin.field>
                            <x-admin.field label="Code"><input type="color" name="color_codes[]" value="#c9a24a" class="h-12 rounded-lg border border-[#ddd4c4] p-1"></x-admin.field>
                            <button type="button" class="rounded-lg border border-red-200 px-3 py-3 text-sm font-semibold text-red-700" data-remove-color-row>Remove</button>
                        </div>
                    @endforelse
                </div>
            </div>
            <x-admin.check label="Active"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $attribute->is_active ?? true))></x-admin.check>
            @if ($errors->any())<p class="text-sm text-red-700">{{ $errors->first() }}</p>@endif
            <button class="rounded-lg bg-[#7a1f55] px-4 py-3 font-semibold text-white">Save Attribute</button>
        </div>
    </form>
    <template data-color-row-template>
        <div class="grid gap-2 rounded-lg bg-white p-3 md:grid-cols-[1fr_120px_auto] md:items-end" data-color-row>
            <x-admin.field label="Color name"><input name="color_names[]" class="rounded-lg border border-[#ddd4c4] px-4 py-3" placeholder="Royal Blue"></x-admin.field>
            <x-admin.field label="Code"><input type="color" name="color_codes[]" value="#c9a24a" class="h-12 rounded-lg border border-[#ddd4c4] p-1"></x-admin.field>
            <button type="button" class="rounded-lg border border-red-200 px-3 py-3 text-sm font-semibold text-red-700" data-remove-color-row>Remove</button>
        </div>
    </template>
@endsection
