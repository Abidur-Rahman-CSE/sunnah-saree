@php
    $ruleData = $rule ?? [];
    $ruleScope = $scope ?? ($ruleData['scope'] ?? 'division');
    $ruleLocations = $ruleData['locations'] ?? [];
    $ruleAmount = $ruleData['amount'] ?? '';
    $ruleIsActive = ($ruleData['is_active'] ?? '1') === '1';
    $ruleTitle = match ($ruleScope) {
        'district' => 'District rule',
        'area' => 'Area rule',
        default => 'Division rule',
    };
@endphp

<div class="@if($hidden ?? false) hidden @endif grid gap-3 rounded-lg border border-[#eadcc3] bg-white p-4 md:grid-cols-[180px_minmax(0,1fr)_140px_90px_44px]" data-delivery-rule-row data-rule-scope="{{ $ruleScope }}">
    <input type="hidden" value="{{ $ruleData['id'] ?? '' }}" data-rule-id>
    <input type="hidden" value="{{ $ruleScope }}" data-rule-scope>

    <div class="grid gap-2">
        <p class="text-sm font-semibold text-[#2f1f1a]" data-rule-title>{{ $ruleTitle }}</p>
        <select class="rounded-lg border border-[#ddd4c4] bg-white px-3 py-2 text-sm" data-district-division-filter data-scope-only="district">
            <option value="">All divisions</option>
            @foreach (array_keys($locations['districts'] ?? []) as $division)
                <option value="{{ $division }}">{{ $division }}</option>
            @endforeach
        </select>
        <div class="grid gap-2" data-scope-only="area">
            <select class="rounded-lg border border-[#ddd4c4] bg-white px-3 py-2 text-sm" data-area-division-filter>
                <option value="">All divisions</option>
                @foreach (array_keys($locations['districts'] ?? []) as $division)
                    <option value="{{ $division }}">{{ $division }}</option>
                @endforeach
            </select>
            <select class="rounded-lg border border-[#ddd4c4] bg-white px-3 py-2 text-sm" data-rule-district-filter>
                <option value="">All districts</option>
            </select>
        </div>
    </div>

    <div class="rounded-lg border border-[#ddd4c4] bg-white p-3" data-rule-location-panel data-selected="{{ base64_encode(json_encode($ruleLocations)) }}">
        <div data-rule-hidden-locations></div>
        <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wide text-[#7a6a60]" data-rule-count>0 selected</span>
                <p class="mt-1 text-xs text-[#8d786d]" data-rule-availability>Already assigned options are hidden.</p>
                <p class="mt-1 text-xs text-[#8d786d]" data-scope-only="area">Filter korar por area checkbox tick korun.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" class="text-xs font-semibold text-[#7a1f55]" data-select-rule-locations>Select all shown</button>
                <button type="button" class="text-xs font-semibold text-[#7a1f55]" data-clear-rule-locations>Clear</button>
            </div>
        </div>
        <div class="grid max-h-56 gap-2 overflow-y-auto pr-1 sm:grid-cols-2 lg:grid-cols-3" data-rule-locations></div>
    </div>
    <input value="{{ $ruleAmount }}" class="rounded-lg border border-[#ddd4c4] px-3 py-2" placeholder="Charge amount" data-rule-amount>
    <label class="flex items-center gap-2 text-sm font-semibold text-[#4f3d36]">
        <input type="checkbox" value="1" @checked($ruleIsActive) data-rule-active>
        Active
    </label>
    <button type="button" class="h-10 rounded-lg border border-red-200 text-red-700" data-remove-delivery-rule aria-label="Remove delivery charge rule">x</button>
</div>
