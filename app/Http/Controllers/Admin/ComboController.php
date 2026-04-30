<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ComboRequest;
use App\Models\Combo;
use App\Models\Product;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ComboController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $combos = Combo::query()
            ->withCount('items')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.combos.index', [
            'combos' => $combos->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.combos.form', [
            'combo' => new Combo,
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ComboRequest $request): RedirectResponse
    {
        $combo = Combo::query()->create($this->payload($request));
        $this->syncItems($request, $combo);

        return to_route('admin.combos.index')->with('status', 'Combo saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.combos.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Combo $combo): View
    {
        return view('admin.combos.form', [
            'combo' => $combo->load('items.product'),
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ComboRequest $request, Combo $combo): RedirectResponse
    {
        $combo->update($this->payload($request));
        $this->syncItems($request, $combo);

        return to_route('admin.combos.index')->with('status', 'Combo updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Combo $combo): RedirectResponse
    {
        $combo->delete();

        return to_route('admin.combos.index')->with('status', 'Combo deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ComboRequest $request): array
    {
        $validated = $request->validated();

        return [
            ...Arr::except($validated, ['product_ids', 'quantities', 'image_file']),
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['name']),
            'image_url' => app(AdminImage::class)->store($request->file('image_file'), 'combos') ?? ($validated['image_url'] ?? null),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function syncItems(ComboRequest $request, Combo $combo): void
    {
        $combo->items()->delete();

        foreach ($request->input('product_ids', []) as $productId) {
            $combo->items()->create([
                'product_id' => $productId,
                'quantity' => max(1, (int) $request->input("quantities.$productId", 1)),
            ]);
        }
    }
}
