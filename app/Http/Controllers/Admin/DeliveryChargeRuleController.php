<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DeliveryChargeRuleRequest;
use App\Models\DeliveryChargeRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DeliveryChargeRuleController extends Controller
{
    public function index(): View
    {
        return view('admin.delivery-charge-rules.index', [
            'deliveryChargeRules' => DeliveryChargeRule::query()->latest('id')->get(),
            'locations' => config('bangladesh'),
        ]);
    }

    public function update(DeliveryChargeRuleRequest $request): RedirectResponse
    {
        $keptRuleIds = [];

        foreach ($request->safe()->input('delivery_charge_rules', []) as $ruleData) {
            $rule = isset($ruleData['id'])
                ? DeliveryChargeRule::query()->findOrFail($ruleData['id'])
                : new DeliveryChargeRule;

            $rule->fill([
                'scope' => $ruleData['scope'],
                'locations' => array_values($ruleData['locations']),
                'amount' => $ruleData['amount'],
                'is_active' => (bool) ($ruleData['is_active'] ?? false),
            ])->save();

            $keptRuleIds[] = $rule->id;
        }

        DeliveryChargeRule::query()
            ->when($keptRuleIds !== [], fn ($query) => $query->whereNotIn('id', $keptRuleIds))
            ->delete();

        return to_route('admin.delivery-charge-rules.index')->with('status', 'Delivery charge rules updated.');
    }
}
