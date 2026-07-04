@extends('layouts.admin', ['heading' => 'Delivery Rules'])

@section('content')
    @php
        $deliveryRuleRows = collect(old('delivery_charge_rules', $deliveryChargeRules->map(fn ($rule): array => [
            'id' => $rule->id,
            'scope' => $rule->scope,
            'locations' => $rule->locations,
            'amount' => $rule->amount,
            'is_active' => $rule->is_active ? '1' : '0',
        ])->all()));
        $hiddenDeliveryRuleRows = $deliveryRuleRows->filter(fn (array $rule): bool => filled($rule['id'] ?? null));
        $deliveryRulesByScope = $deliveryRuleRows
            ->reject(fn (array $rule): bool => filled($rule['id'] ?? null))
            ->groupBy('scope');
        $activeRules = $deliveryChargeRules->where('is_active', true)->count();
    @endphp

    <form action="{{ route('admin.delivery-charge-rules.update') }}" method="POST" class="space-y-6" data-delivery-rule-manager>
        @csrf @method('PUT')
        <div class="hidden" data-existing-rule-list>
            @foreach ($hiddenDeliveryRuleRows as $rule)
                @include('admin.settings.partials.delivery-rule-row', ['rule' => $rule, 'scope' => $rule['scope'], 'hidden' => true])
            @endforeach
        </div>

        <section class="overflow-hidden rounded-lg border border-[#eadcc3] bg-white shadow-sm">
            <div class="grid gap-6 bg-[#3b2a23] p-6 text-white lg:grid-cols-[minmax(0,1fr)_360px]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#f4d8a8]">Shipping zones</p>
                    <h2 class="mt-2 font-serif text-3xl font-bold">Delivery charge control</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-[#f8ead7]">Set delivery fees by full division, full district, or exact area. Checkout uses the most specific match first.</p>
                </div>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="rounded-lg bg-white/10 p-3">
                        <p class="text-2xl font-bold">{{ $deliveryChargeRules->count() }}</p>
                        <p class="mt-1 text-xs text-[#f8ead7]">Rules</p>
                    </div>
                    <div class="rounded-lg bg-white/10 p-3">
                        <p class="text-2xl font-bold">{{ $activeRules }}</p>
                        <p class="mt-1 text-xs text-[#f8ead7]">Active</p>
                    </div>
                    <div class="rounded-lg bg-white/10 p-3">
                        <p class="text-2xl font-bold">3</p>
                        <p class="mt-1 text-xs text-[#f8ead7]">Priority levels</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 border-t border-[#eadcc3] bg-[#fffaf2] p-4 text-sm text-[#6f5a50] md:grid-cols-3">
                <div class="rounded-lg border border-[#eadcc3] bg-white p-3"><span class="font-semibold text-[#7a1f55]">1. Area</span> charge wins first.</div>
                <div class="rounded-lg border border-[#eadcc3] bg-white p-3"><span class="font-semibold text-[#7a1f55]">2. District</span> charge applies next.</div>
                <div class="rounded-lg border border-[#eadcc3] bg-white p-3"><span class="font-semibold text-[#7a1f55]">3. Division</span> charge is fallback before default.</div>
            </div>
        </section>

        <section class="rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-[#2f1f1a]">Already set charges</h3>
                    <p class="mt-1 text-sm text-[#7a6a60]">Selected locations are removed from new rules in the same section.</p>
                </div>
            </div>
            <div class="mt-4 grid gap-3 md:grid-cols-3" data-assigned-rule-summary></div>
        </section>

        <div class="grid gap-5">
            <section class="rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm" data-rule-section="division">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-[#2f1f1a]">Division charge</h3>
                        <p class="mt-1 text-sm text-[#7a6a60]">Best for outside-Dhaka or regional pricing.</p>
                    </div>
                    <button type="button" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white" data-add-rule="division">Add division charge</button>
                </div>
                <div class="mt-4 grid gap-3" data-rule-list>
                    @foreach ($deliveryRulesByScope->get('division', collect()) as $rule)
                        @include('admin.settings.partials.delivery-rule-row', ['rule' => $rule, 'scope' => 'division'])
                    @endforeach
                </div>
            </section>

            <section class="rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm" data-rule-section="district">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-[#2f1f1a]">District charge</h3>
                        <p class="mt-1 text-sm text-[#7a6a60]">Filter by division, then tick one or many districts.</p>
                    </div>
                    <button type="button" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white" data-add-rule="district">Add district charge</button>
                </div>
                <div class="mt-4 grid gap-3" data-rule-list>
                    @foreach ($deliveryRulesByScope->get('district', collect()) as $rule)
                        @include('admin.settings.partials.delivery-rule-row', ['rule' => $rule, 'scope' => 'district'])
                    @endforeach
                </div>
            </section>

            <section class="rounded-lg border border-[#eadcc3] bg-white p-5 shadow-sm" data-rule-section="area">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-[#2f1f1a]">Area charge</h3>
                        <p class="mt-1 text-sm text-[#7a6a60]">Filter by division and district, then tick specific areas.</p>
                    </div>
                    <button type="button" class="rounded-lg bg-[#7a1f55] px-4 py-2 text-sm font-semibold text-white" data-add-rule="area">Add area charge</button>
                </div>
                <div class="mt-4 grid gap-3" data-rule-list>
                    @foreach ($deliveryRulesByScope->get('area', collect()) as $rule)
                        @include('admin.settings.partials.delivery-rule-row', ['rule' => $rule, 'scope' => 'area'])
                    @endforeach
                </div>
            </section>
        </div>

        @if ($errors->any())<p class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>@endif
        <div class="sticky bottom-4 flex justify-end">
            <button class="rounded-lg bg-[#7a1f55] px-6 py-3 font-semibold text-white shadow-lg">Save Delivery Rules</button>
        </div>
    </form>

    <template data-delivery-rule-template>
        @include('admin.settings.partials.delivery-rule-row', ['rule' => null, 'scope' => 'division', 'hidden' => false])
    </template>

    <script>
        const deliveryRuleLocations = {{ Illuminate\Support\Js::from([
            'divisions' => array_keys($locations['districts']),
            'districtsByDivision' => $locations['districts'],
            'areasByDistrict' => $locations['areas'],
        ]) }};

        document.querySelectorAll('[data-delivery-rule-manager]').forEach((manager) => {
            const allDistricts = Object.values(deliveryRuleLocations.districtsByDivision).flat();
            const selectedValues = (panel) => JSON.parse(atob(panel.dataset.selected || 'W10='));
            const setSelectedValues = (panel, values) => {
                panel.dataset.selected = btoa(JSON.stringify([...new Set(values)]));
            };
            let isRefreshingRows = false;
            const formatAmount = (value) => {
                const amount = Number(value || 0);

                return amount > 0 ? `৳${Math.round(amount).toLocaleString()}` : 'No amount';
            };
            const scopeLabels = {
                division: 'Division',
                district: 'District',
                area: 'Area',
            };

            const renderAssignedSummary = () => {
                const summary = manager.querySelector('[data-assigned-rule-summary]');

                if (! summary) {
                    return;
                }

                summary.innerHTML = '';

                const rows = Array.from(manager.querySelectorAll('[data-delivery-rule-row]'))
                    .map((row) => ({
                        row,
                        scope: row.dataset.ruleScope,
                        amount: row.querySelector('[data-rule-amount]').value,
                        active: row.querySelector('[data-rule-active]').checked,
                        locations: selectedValues(row.querySelector('[data-rule-location-panel]')),
                    }))
                    .filter((rule) => rule.locations.length > 0);

                if (rows.length === 0) {
                    const empty = document.createElement('p');

                    empty.className = 'rounded-lg border border-dashed border-[#eadcc3] bg-[#fffaf2] px-4 py-5 text-sm text-[#7a6a60] md:col-span-3';
                    empty.textContent = 'No delivery charge rules set yet.';
                    summary.append(empty);

                    return;
                }

                rows.forEach((rule) => {
                    const card = document.createElement('article');
                    const header = document.createElement('div');
                    const title = document.createElement('p');
                    const amount = document.createElement('span');
                    const meta = document.createElement('p');
                    const chips = document.createElement('div');
                    const remove = document.createElement('button');

                    card.className = 'rounded-lg border border-[#eadcc3] bg-[#fffaf2] p-4';
                    header.className = 'flex items-start justify-between gap-3';
                    title.className = 'text-sm font-bold text-[#2f1f1a]';
                    amount.className = 'rounded-full bg-[#7a1f55] px-3 py-1 text-xs font-bold text-white';
                    meta.className = 'mt-1 text-xs text-[#7a6a60]';
                    chips.className = 'mt-3 flex max-h-24 flex-wrap gap-2 overflow-y-auto';
                    remove.type = 'button';
                    remove.className = 'mt-3 text-xs font-bold text-red-700';
                    remove.dataset.removeAssignedRule = rule.row.dataset.index || '';

                    title.textContent = `${scopeLabels[rule.scope]} charge`;
                    amount.textContent = formatAmount(rule.amount);
                    meta.textContent = `${rule.locations.length} selected${rule.active ? '' : ' · inactive'}`;
                    remove.textContent = 'Remove rule';

                    rule.locations.forEach((location) => {
                        const chip = document.createElement('span');

                        chip.className = 'rounded-full border border-[#eadcc3] bg-white px-2.5 py-1 text-xs font-semibold text-[#4f3d36]';
                        chip.textContent = location;
                        chips.append(chip);
                    });

                    header.append(title, amount);
                    card.append(header, meta, chips, remove);
                    summary.append(card);
                });
            };

            const selectedByScope = (currentRow) => {
                const selected = {
                    division: new Set(),
                    district: new Set(),
                    area: new Set(),
                };

                manager.querySelectorAll('[data-delivery-rule-row]').forEach((row) => {
                    if (row === currentRow) {
                        return;
                    }

                    selectedValues(row.querySelector('[data-rule-location-panel]')).forEach((location) => {
                        selected[row.dataset.ruleScope]?.add(location);
                    });
                });

                return selected;
            };

            const unavailableOptions = (currentRow) => {
                const selected = selectedByScope(currentRow);
                const scope = currentRow.dataset.ruleScope;

                return selected[scope] || new Set();
            };

            const renderHiddenLocations = (row, selected) => {
                const hiddenLocations = row.querySelector('[data-rule-hidden-locations]');
                const rowIndex = row.dataset.index || '0';

                hiddenLocations.innerHTML = '';

                selected.forEach((location) => {
                    const input = document.createElement('input');

                    input.type = 'hidden';
                    input.name = `delivery_charge_rules[${rowIndex}][locations][]`;
                    input.value = location;
                    hiddenLocations.append(input);
                });
            };

            const setOptions = (row, options, selected = []) => {
                const panel = row.querySelector('[data-rule-location-panel]');
                const list = row.querySelector('[data-rule-locations]');
                const count = row.querySelector('[data-rule-count]');
                const availability = row.querySelector('[data-rule-availability]');
                const rowIndex = row.dataset.index || '0';
                const unavailableLocations = unavailableOptions(row);
                const selectedOptions = selected.filter((option) => options.includes(option));
                const availableOptions = options.filter((option) => ! unavailableLocations.has(option) && ! selected.includes(option));
                const visibleOptions = [...selectedOptions, ...availableOptions];
                const hiddenCount = options.filter((option) => unavailableLocations.has(option) && ! selected.includes(option)).length;

                list.innerHTML = '';
                renderHiddenLocations(row, selected);

                visibleOptions.forEach((option) => {
                    const isSelected = selected.includes(option);
                    const id = `delivery-rule-${rowIndex}-${option.replace(/[^a-z0-9]+/gi, '-').toLowerCase()}`;
                    const label = document.createElement('label');
                    const input = document.createElement('input');
                    const text = document.createElement('span');

                    label.className = isSelected
                        ? 'flex cursor-pointer items-center gap-2 rounded-lg border border-[#7a1f55] bg-[#fff4fb] px-3 py-2 text-sm font-semibold text-[#4f3d36] hover:border-[#7a1f55]'
                        : 'flex cursor-pointer items-center gap-2 rounded-lg border border-[#eadcc3] px-3 py-2 text-sm text-[#4f3d36] hover:border-[#7a1f55]';
                    input.id = id;
                    input.type = 'checkbox';
                    input.className = 'rounded border-[#cdbd9f]';
                    input.value = option;
                    input.dataset.ruleLocationCheckbox = '';
                    input.checked = isSelected;
                    text.textContent = option;

                    label.append(input, text);
                    list.append(label);
                });

                count.textContent = `${selected.length} selected`;
                availability.textContent = hiddenCount > 0
                    ? `${hiddenCount} already assigned option${hiddenCount === 1 ? ' is' : 's are'} hidden.`
                    : 'All matching options are available.';
            };

            const syncRow = (row) => {
                const scope = row.dataset.ruleScope;
                const divisionFilter = scope === 'area' ? row.querySelector('[data-area-division-filter]') : row.querySelector('[data-district-division-filter]');
                const districtFilter = row.querySelector('[data-rule-district-filter]');
                const panel = row.querySelector('[data-rule-location-panel]');
                const selected = selectedValues(panel);

                if (scope === 'division') {
                    setOptions(row, deliveryRuleLocations.divisions, selected);
                }

                if (scope === 'district') {
                    const districts = divisionFilter.value ? deliveryRuleLocations.districtsByDivision[divisionFilter.value] || [] : allDistricts;

                    setOptions(row, districts, selected);
                }

                if (scope === 'area') {
                    const districts = divisionFilter.value ? deliveryRuleLocations.districtsByDivision[divisionFilter.value] || [] : allDistricts;
                    const selectedDistrict = districtFilter.value;

                    districtFilter.innerHTML = '<option value="">All districts</option>';
                    districts.forEach((district) => {
                        districtFilter.append(new Option(district, district, false, district === selectedDistrict));
                    });

                    const areas = selectedDistrict ? deliveryRuleLocations.areasByDistrict[selectedDistrict] || [] : [...new Set(districts.flatMap((district) => deliveryRuleLocations.areasByDistrict[district] || []))];

                    setOptions(row, areas, selected);
                }

                row.dataset.ready = '1';
            };

            const nameRows = () => {
                if (isRefreshingRows) {
                    return;
                }

                isRefreshingRows = true;
                manager.querySelectorAll('[data-delivery-rule-row]').forEach((row, index) => {
                    row.dataset.index = index;
                    row.querySelector('[data-rule-id]').name = `delivery_charge_rules[${index}][id]`;
                    row.querySelector('[data-rule-scope]').name = `delivery_charge_rules[${index}][scope]`;
                    row.querySelector('[data-rule-amount]').name = `delivery_charge_rules[${index}][amount]`;
                    row.querySelector('[data-rule-active]').name = `delivery_charge_rules[${index}][is_active]`;
                    syncRow(row);
                });
                isRefreshingRows = false;
                renderAssignedSummary();
            };

            manager.querySelectorAll('[data-add-rule]').forEach((button) => {
                button.addEventListener('click', () => {
                    const scope = button.dataset.addRule;
                    const section = manager.querySelector(`[data-rule-section="${scope}"] [data-rule-list]`);
                    const row = document.querySelector('[data-delivery-rule-template]').content.firstElementChild.cloneNode(true);

                    row.classList.remove('hidden');
                    row.dataset.ruleScope = scope;
                    row.querySelector('[data-rule-scope]').value = scope;
                    row.querySelector('[data-rule-title]').textContent = scope === 'division' ? 'Division rule' : scope === 'district' ? 'District rule' : 'Area rule';
                    row.querySelector('[data-rule-location-panel]').dataset.selected = 'W10=';
                    row.querySelectorAll('[data-scope-only]').forEach((element) => {
                        element.hidden = element.dataset.scopeOnly !== scope;
                    });
                    row.querySelectorAll('[data-hide-scope]').forEach((element) => {
                        element.hidden = element.dataset.hideScope === scope;
                    });

                    section.append(row);
                    nameRows();
                });
            });

            manager.addEventListener('change', (event) => {
                if (event.target.matches('[data-district-division-filter], [data-area-division-filter]')) {
                    const row = event.target.closest('[data-delivery-rule-row]');

                    setSelectedValues(row.querySelector('[data-rule-location-panel]'), []);

                    if (row.querySelector('[data-rule-district-filter]')) {
                        row.querySelector('[data-rule-district-filter]').value = '';
                    }

                    syncRow(row);
                }

                if (event.target.matches('[data-rule-district-filter]')) {
                    const row = event.target.closest('[data-delivery-rule-row]');

                    setSelectedValues(row.querySelector('[data-rule-location-panel]'), []);
                    syncRow(row);
                }
            });

            manager.addEventListener('click', (event) => {
                if (event.target.matches('[data-remove-delivery-rule]')) {
                    event.target.closest('[data-delivery-rule-row]').remove();
                    nameRows();
                }

                if (event.target.matches('[data-remove-assigned-rule]')) {
                    const row = manager.querySelectorAll('[data-delivery-rule-row]')[Number(event.target.dataset.removeAssignedRule)];

                    row?.remove();
                    nameRows();
                }

                if (event.target.matches('[data-clear-rule-locations]')) {
                    const row = event.target.closest('[data-delivery-rule-row]');

                    setSelectedValues(row.querySelector('[data-rule-location-panel]'), []);
                    nameRows();
                }

                if (event.target.matches('[data-select-rule-locations]')) {
                    const row = event.target.closest('[data-delivery-rule-row]');
                    const panel = row.querySelector('[data-rule-location-panel]');
                    const addedLocations = Array.from(row.querySelectorAll('[data-rule-location-checkbox]')).map((input) => input.value);

                    setSelectedValues(panel, [...selectedValues(panel), ...addedLocations]);
                    nameRows();
                }
            });

            manager.addEventListener('change', (event) => {
                if (event.target.matches('[data-rule-location-checkbox]')) {
                    const row = event.target.closest('[data-delivery-rule-row]');
                    const panel = row.querySelector('[data-rule-location-panel]');
                    const selected = selectedValues(panel).filter((location) => location !== event.target.value);

                    setSelectedValues(panel, event.target.checked ? [...selected, event.target.value] : selected);
                    nameRows();
                }
            });

            manager.addEventListener('input', (event) => {
                if (event.target.matches('[data-rule-amount]')) {
                    renderAssignedSummary();
                }
            });

            manager.querySelectorAll('[data-delivery-rule-row]').forEach((row) => {
                row.querySelectorAll('[data-scope-only]').forEach((element) => {
                    element.hidden = element.dataset.scopeOnly !== row.dataset.ruleScope;
                });
                row.querySelectorAll('[data-hide-scope]').forEach((element) => {
                    element.hidden = element.dataset.hideScope === row.dataset.ruleScope;
                });
            });
            nameRows();
        });
    </script>
@endsection
