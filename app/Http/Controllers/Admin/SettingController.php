<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => Setting::query()->pluck('value', 'key'),
        ]);
    }

    public function update(SettingRequest $request): RedirectResponse
    {
        $booleanSettings = [
            'cod_enabled',
            'online_payment_enabled',
            'home_section_hero_enabled',
            'home_section_sharee_types_enabled',
            'home_section_colors_enabled',
            'home_section_best_sellers_enabled',
            'home_section_new_arrivals_enabled',
            'home_section_collections_enabled',
            'home_section_essentials_enabled',
            'home_section_promo_banners_enabled',
            'home_section_trust_enabled',
        ];

        $settings = [
            ...$request->validated(),
        ];

        foreach ($booleanSettings as $key) {
            $settings[$key] = $request->boolean($key) ? '1' : '0';
        }

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return to_route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
