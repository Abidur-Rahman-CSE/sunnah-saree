@props(['createUrl' => null, 'createLabel' => 'Add New', 'searchPlaceholder' => 'Search'])

<div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
    <form class="grid gap-2 md:grid-cols-[1fr_160px_auto]">
        <input name="search" value="{{ request('search') }}" class="rounded-lg border border-[#ddd4c4] px-4 py-2" placeholder="{{ $searchPlaceholder }}">
        <select name="status" class="rounded-lg border border-[#ddd4c4] px-4 py-2">
            <option value="">Any status</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        </select>
        <button class="rounded-lg bg-[#7a1f55] px-4 py-2 font-semibold text-white">Filter</button>
    </form>
    @if ($createUrl)
        <a href="{{ $createUrl }}" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-center font-semibold text-white">{{ $createLabel }}</a>
    @endif
</div>
