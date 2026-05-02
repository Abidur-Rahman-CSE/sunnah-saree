<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FashionAttributeRequest;
use App\Models\FashionAttribute;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class FashionAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.fashion-attributes.index', [
            'attributes' => FashionAttribute::query()->latest()->paginate(20),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.fashion-attributes.form', [
            'attribute' => new FashionAttribute,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FashionAttributeRequest $request): RedirectResponse
    {
        FashionAttribute::query()->create($this->payload($request));

        return to_route('admin.fashion-attributes.index')->with('status', 'Fashion attribute saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FashionAttribute $fashionAttribute): RedirectResponse
    {
        return to_route('admin.fashion-attributes.edit', $fashionAttribute);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FashionAttribute $fashionAttribute): View
    {
        return view('admin.fashion-attributes.form', [
            'attribute' => $fashionAttribute,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FashionAttributeRequest $request, FashionAttribute $fashionAttribute): RedirectResponse
    {
        $fashionAttribute->update($this->payload($request));

        return to_route('admin.fashion-attributes.index')->with('status', 'Fashion attribute updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FashionAttribute $fashionAttribute): RedirectResponse
    {
        $fashionAttribute->delete();

        return to_route('admin.fashion-attributes.index')->with('status', 'Fashion attribute deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(FashionAttributeRequest $request): array
    {
        $validated = $request->validated();
        $key = Str::snake($validated['key']);
        $values = $key === 'color'
            ? collect($request->input('color_names', []))
                ->map(function (?string $name, int $index) use ($request): ?array {
                    $name = trim((string) $name);

                    if ($name === '') {
                        return null;
                    }

                    return [
                        'name' => $name,
                        'code' => $request->input("color_codes.$index") ?: '#c9a24a',
                    ];
                })
                ->filter()
                ->unique('name')
                ->values()
                ->all()
            : collect(preg_split('/\r\n|\r|\n|,/', $validated['values_text'] ?? ''))
                ->map(fn (string $value): string => trim($value))
                ->filter()
                ->unique()
                ->values()
                ->all();

        return [
            'name' => $validated['name'],
            'key' => $key,
            'values' => $values,
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
