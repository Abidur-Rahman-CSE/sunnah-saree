<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'website_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'facebook_page_link' => ['nullable', 'url', 'max:2048'],
            'announcement_bar_text' => ['nullable', 'string', 'max:255'],
            'free_delivery_minimum_amount' => ['required', 'numeric', 'min:0'],
            'cod_enabled' => ['nullable', 'boolean'],
            'online_payment_enabled' => ['nullable', 'boolean'],
            'return_policy_text' => ['nullable', 'string'],
            'shipping_policy_text' => ['nullable', 'string'],
            'terms_and_conditions' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
        ];
    }
}
