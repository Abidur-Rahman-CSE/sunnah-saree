<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CollectionRequest;
use App\Models\Collection;
use App\Models\Product;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $collections = Collection::query()
            ->withCount('products')
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.collections.index', [
            'collections' => $collections->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.collections.form', [
            'collection' => new Collection,
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollectionRequest $request): RedirectResponse
    {
        $collection = Collection::query()->create($this->payload($request));
        $collection->products()->sync($request->input('product_ids', []));

        return to_route('admin.collections.index')->with('status', 'Collection saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.collections.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection): View
    {
        return view('admin.collections.form', [
            'collection' => $collection->load('products'),
            'products' => Product::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollectionRequest $request, Collection $collection): RedirectResponse
    {
        $collection->update($this->payload($request));
        $collection->products()->sync($request->input('product_ids', []));

        return to_route('admin.collections.index')->with('status', 'Collection updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection): RedirectResponse
    {
        $collection->delete();

        return to_route('admin.collections.index')->with('status', 'Collection deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(CollectionRequest $request): array
    {
        $validated = $request->validated();

        return [
            ...Arr::except($validated, ['product_ids', 'banner_file']),
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['name']),
            'banner_url' => app(AdminImage::class)->store($request->file('banner_file'), 'collections') ?? ($validated['banner_url'] ?? null),
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
