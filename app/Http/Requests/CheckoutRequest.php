<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $locations = config('bangladesh');
        $divisions = array_keys($locations['districts']);
        $districts = collect($locations['districts'])->flatten()->all();
        $areas = collect($locations['areas'])->flatten()->unique()->values()->all();

        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_division' => ['required', 'string', Rule::in($divisions)],
            'shipping_district' => ['required', 'string', Rule::in($districts)],
            'shipping_area' => ['required', 'string', Rule::in($areas)],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:cod,online'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $locations = config('bangladesh');
            $division = (string) $this->input('shipping_division');
            $district = (string) $this->input('shipping_district');
            $area = (string) $this->input('shipping_area');

            if ($division !== '' && $district !== '' && ! in_array($district, $locations['districts'][$division] ?? [], true)) {
                $validator->errors()->add('shipping_district', 'Please select a valid district for the selected division.');
            }

            if ($district !== '' && $area !== '' && ! in_array($area, $locations['areas'][$district] ?? [], true)) {
                $validator->errors()->add('shipping_area', 'Please select a valid area for the selected district.');
            }
        });
    }
}
