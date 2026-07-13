<?php

namespace App\Http\Requests\Admin;

use App\Models\Banner;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BannerRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'placement' => [
                'required',
                'string',
                Rule::in(array_keys(Banner::placements())),
            ],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'image_file' => ['nullable', 'image', 'max:4096'],
            'headline' => ['nullable', 'string', 'max:255'],
            'cta_label' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'string', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $placement = $this->string('placement')->toString();
                $placementMeta = Banner::placements()[$placement] ?? null;

                if (! $placementMeta || $placementMeta['multiple']) {
                    return;
                }

                $banner = $this->route('banner');
                $alreadyUsed = Banner::query()
                    ->where('placement', $placement)
                    ->when($banner instanceof Banner, fn ($query) => $query->whereKeyNot($banner->id))
                    ->exists();

                if ($alreadyUsed) {
                    $validator->errors()->add('placement', 'This banner placement is already used.');
                }
            },
        ];
    }
}
