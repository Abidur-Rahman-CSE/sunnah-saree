@extends('layouts.admin', ['heading' => 'Fashion Attributes'])

@section('content')
    <div class="mb-4 flex justify-end">
        <a href="{{ route('admin.fashion-attributes.create') }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-center font-semibold text-white">Add Attribute</a>
    </div>
    <div class="overflow-x-auto rounded-lg border border-[#e5ded0] bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead><tr class="border-b bg-[#faf8f3]"><th class="p-3">Name</th><th>Key</th><th>Values</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($attributes as $attribute)
                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $attribute->name }}</td>
                        <td>{{ $attribute->key }}</td>
                        <td>{{ count($attribute->values ?? []) }} values</td>
                        <td>{{ $attribute->is_active ? 'Active' : 'Inactive' }}</td>
                        <td><a class="font-semibold text-[#7a1f55]" href="{{ route('admin.fashion-attributes.edit', $attribute) }}">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-5">{{ $attributes->links() }}</div>
@endsection
