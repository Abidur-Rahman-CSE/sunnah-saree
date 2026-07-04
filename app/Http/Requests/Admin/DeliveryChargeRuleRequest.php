<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DeliveryChargeRuleRequest extends FormRequest
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
            'delivery_charge_rules' => ['nullable', 'array'],
            'delivery_charge_rules.*.id' => ['nullable', 'integer', 'exists:delivery_charge_rules,id'],
            'delivery_charge_rules.*.scope' => ['required_with:delivery_charge_rules', 'string', Rule::in(['division', 'district', 'area'])],
            'delivery_charge_rules.*.locations' => ['nullable', 'array'],
            'delivery_charge_rules.*.locations.*' => ['required', 'string', 'max:255'],
            'delivery_charge_rules.*.amount' => ['required_with:delivery_charge_rules', 'numeric', 'min:0'],
            'delivery_charge_rules.*.is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'delivery_charge_rules.*.amount.required_with' => 'Please enter an amount for each delivery rule.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $locations = config('bangladesh');
            $allowedLocations = [
                'division' => array_keys($locations['districts']),
                'district' => collect($locations['districts'])->flatten()->all(),
                'area' => collect($locations['areas'])->flatten()->unique()->values()->all(),
            ];
            $selectedByScope = [
                'division' => [],
                'district' => [],
                'area' => [],
            ];

            foreach ($this->input('delivery_charge_rules', []) as $index => $rule) {
                $scope = $rule['scope'] ?? null;
                $selectedLocations = $rule['locations'] ?? [];

                if (! isset($allowedLocations[$scope])) {
                    continue;
                }

                if ($selectedLocations === []) {
                    $validator->errors()->add("delivery_charge_rules.$index.locations", match ($scope) {
                        'division' => 'Please tick at least one division for this division charge.',
                        'district' => 'Please tick at least one district for this district charge.',
                        'area' => 'Please tick at least one area for this area charge.',
                    });

                    continue;
                }

                foreach ($selectedLocations as $location) {
                    if (! in_array($location, $allowedLocations[$scope], true)) {
                        $validator->errors()->add("delivery_charge_rules.$index.locations", 'Please select valid delivery locations.');
                    }
                }

                $selectedByScope[$scope] = [
                    ...$selectedByScope[$scope],
                    ...$selectedLocations,
                ];
            }

            foreach ($selectedByScope as $scope => $selectedLocations) {
                $duplicates = collect($selectedLocations)->duplicatesStrict()->unique();

                foreach ($duplicates as $location) {
                    $validator->errors()->add('delivery_charge_rules', "The {$location} {$scope} already has a delivery charge rule.");
                }
            }
        });
    }
}
