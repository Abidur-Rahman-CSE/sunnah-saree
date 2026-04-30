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
        $settings = [
            ...$request->validated(),
            'cod_enabled' => $request->boolean('cod_enabled') ? '1' : '0',
            'online_payment_enabled' => $request->boolean('online_payment_enabled') ? '1' : '0',
        ];

        foreach ($settings as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return to_route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
