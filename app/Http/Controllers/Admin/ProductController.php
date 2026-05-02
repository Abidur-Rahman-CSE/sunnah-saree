<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Collection;
use App\Models\FashionAttribute;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'images', 'variants'])
            ->when($request->filled('search'), fn ($query) => $query->where(fn ($search) => $search
                ->where('name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('sku', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('color', 'like', '%'.$request->string('search')->toString().'%')))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.products.index', [
            'products' => $products->paginate(20)->withQueryString(),
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::query()->orderBy('name')->get(),
            'collections' => Collection::query()->orderBy('name')->get(),
            'fashionOptions' => $this->fashionOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($this->payload($request));
        $this->syncExtras($request, $product);

        return to_route('admin.products.index')->with('status', 'Product saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.products.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('collections', 'images', 'variants'),
            'categories' => Category::query()->orderBy('name')->get(),
            'collections' => Collection::query()->orderBy('name')->get(),
            'fashionOptions' => $this->fashionOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->payload($request));
        $this->syncExtras($request, $product);

        return to_route('admin.products.index')->with('status', 'Product updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('admin.products.index')->with('status', 'Product deleted.');
    }

    public function duplicate(Product $product): RedirectResponse
    {
        $product->load('collections', 'images', 'variants');

        $copy = $product->replicate();
        $copy->name = $product->name.' Copy';
        $copy->slug = $this->uniqueSlug($product->slug.'-copy');
        $copy->sku = $this->uniqueSku($product->sku.'-COPY');
        $copy->is_active = false;
        $copy->save();

        $copy->collections()->sync($product->collections->pluck('id'));

        foreach ($product->images as $image) {
            $copy->images()->create($image->only(['image_url', 'alt_text', 'sort_order']));
        }

        foreach ($product->variants as $variant) {
            $variantCopy = $variant->replicate();
            $variantCopy->product_id = $copy->id;
            $variantCopy->sku = $this->uniqueVariantSku($variant->sku.'-COPY');
            $variantCopy->save();
        }

        return to_route('admin.products.edit', $copy)->with('status', 'Product duplicated. Review and activate when ready.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(ProductRequest $request): array
    {
        $validated = $request->validated();

        $payload = [
            ...Arr::except($validated, ['collection_ids', 'image_url', 'image_file', 'image_files', 'variant_color', 'variant_sku', 'variant_quantity']),
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['name']),
            'blouse_included' => $request->boolean('blouse_included'),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
        ];

        if (($payload['product_type'] ?? null) === 'general') {
            $payload['sharee_type'] = null;
            $payload['fabric'] = null;
            $payload['work_type'] = null;
            $payload['color'] = null;
            $payload['occasion'] = null;
            $payload['blouse_included'] = false;
            $payload['length'] = null;
            $payload['care_instruction'] = null;
        }

        return $payload;
    }

    private function syncExtras(ProductRequest $request, Product $product): void
    {
        $product->collections()->sync($request->input('collection_ids', []));

        $uploadedImageUrl = app(AdminImage::class)->store($request->file('image_file'), 'products');
        $imageUrl = $uploadedImageUrl ?? ($request->filled('image_url') ? $request->string('image_url')->toString() : null);

        if ($imageUrl) {
            $product->images()->updateOrCreate(
                ['sort_order' => 0],
                ['image_url' => $imageUrl, 'alt_text' => $product->name],
            );
        }

        foreach ($request->file('image_files', []) as $uploadedGalleryImage) {
            $product->images()->create([
                'image_url' => app(AdminImage::class)->store($uploadedGalleryImage, 'products'),
                'alt_text' => $product->name,
                'sort_order' => ((int) $product->images()->max('sort_order')) + 1,
            ]);
        }

        if ($request->filled('variant_color') && $request->filled('variant_sku')) {
            $product->variants()->updateOrCreate(
                ['sku' => $request->string('variant_sku')],
                [
                    'color' => $request->string('variant_color'),
                    'quantity' => $request->integer('variant_quantity'),
                    'stock_alert_quantity' => 3,
                    'stock_status' => $request->integer('variant_quantity') > 0 ? 'in_stock' : 'out_of_stock',
                ],
            );
        }
    }

    /**
     * @return array<string, \Illuminate\Support\Collection<int, string>>
     */
    private function fashionOptions(): array
    {
        return collect(['sharee_type', 'fabric', 'work_type', 'color', 'occasion'])
            ->mapWithKeys(fn (string $key): array => [$key => FashionAttribute::valuesFor($key)])
            ->all();
    }

    private function uniqueSlug(string $slug): string
    {
        $base = Str::slug($slug);
        $candidate = $base;
        $counter = 2;

        while (Product::query()->where('slug', $candidate)->exists()) {
            $candidate = $base.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function uniqueSku(string $sku): string
    {
        $candidate = Str::upper($sku);
        $counter = 2;

        while (Product::query()->where('sku', $candidate)->exists()) {
            $candidate = Str::upper($sku).'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    private function uniqueVariantSku(string $sku): string
    {
        $candidate = Str::upper($sku);
        $counter = 2;

        while (ProductVariant::query()->where('sku', $candidate)->exists()) {
            $candidate = Str::upper($sku).'-'.$counter;
            $counter++;
        }

        return $candidate;
    }
}
