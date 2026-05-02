<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Combo;
use App\Models\FashionAttribute;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'hero' => Banner::query()->where('placement', 'hero')->where('is_active', true)->first(),
            'categories' => Category::query()->where('is_active', true)->get(),
            'bestSellers' => Product::query()->active()->with('images')->where('is_best_seller', true)->take(4)->get(),
            'newArrivals' => Product::query()->active()->with('images')->where('is_new_arrival', true)->take(4)->get(),
            'collections' => Collection::query()->where('is_active', true)->where('is_featured', true)->take(6)->get(),
            'offers' => Offer::query()->where('is_active', true)->with('products.images')->take(2)->get(),
            'combos' => Combo::query()->where('is_active', true)->with('items.product.images')->take(3)->get(),
            'colorOptions' => $this->colorOptions(),
        ]);
    }

    public function products(Request $request): View
    {
        $products = Product::query()
            ->active()
            ->with(['category', 'images'])
            ->when($request->filled('category'), fn ($query) => $query->whereRelation('category', 'slug', $request->string('category')));

        $this->applyProductFilters($products, $request);

        return view('storefront.products.index', [
            'products' => $products->paginate(12)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->get(),
            'filters' => $this->filters($request->string('category')->toString()),
            'selectedCategorySlug' => $request->string('category')->toString(),
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'images', 'variants', 'collections']);

        return view('storefront.products.show', [
            'product' => $product,
            'relatedProducts' => Product::query()->active()->with('images')->where('category_id', $product->category_id)->whereKeyNot($product->id)->take(4)->get(),
            'similarColorProducts' => Product::query()->active()->with('images')->where('color', $product->color)->whereKeyNot($product->id)->take(4)->get(),
            'colorCode' => FashionAttribute::colorCodeFor($product->color),
        ]);
    }

    public function category(Request $request, Category $category): View
    {
        $products = $category->products()->active()->with(['category', 'images']);

        $this->applyProductFilters($products, $request);

        return view('storefront.products.index', [
            'products' => $products->paginate(12)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->get(),
            'filters' => $this->filters($category->slug),
            'pageTitle' => $category->name,
            'selectedCategorySlug' => $category->slug,
            'showCategoryFilter' => false,
        ]);
    }

    public function collection(Request $request, Collection $collection): View
    {
        abort_unless($collection->is_active, 404);

        $featuredProducts = $collection->products()->active()->with('images')->take(8)->get();
        $filterProductIds = $collection->products()->active()->pluck('products.id');
        $products = $collection->products()->active()->with(['category', 'images']);

        $this->applyProductFilters($products, $request);

        return view('storefront.collection', [
            'collection' => $collection,
            'featuredProducts' => $featuredProducts,
            'products' => $products->paginate(12)->withQueryString(),
            'filters' => $this->filters(productIds: $filterProductIds),
        ]);
    }

    public function offers(): View
    {
        return view('storefront.offers', [
            'offers' => Offer::query()->where('is_active', true)->with('products.images')->get(),
        ]);
    }

    public function offer(Request $request, Offer $offer): View
    {
        abort_unless($offer->is_active, 404);

        $featuredProducts = $offer->products()->active()->with('images')->take(8)->get();
        $filterProductIds = $offer->products()->active()->pluck('products.id');
        $products = $offer->products()->active()->with(['category', 'images']);

        $this->applyProductFilters($products, $request);

        return view('storefront.offer', [
            'offer' => $offer,
            'featuredProducts' => $featuredProducts,
            'products' => $products->paginate(12)->withQueryString(),
            'filters' => $this->filters(productIds: $filterProductIds),
        ]);
    }

    public function combos(): View
    {
        return view('storefront.combos', [
            'combos' => Combo::query()->where('is_active', true)->with('items.product.images')->get(),
        ]);
    }

    public function staticPage(string $page): View
    {
        abort_unless(in_array($page, ['about-us', 'contact-us', 'return-policy', 'shipping-policy', 'privacy-policy', 'terms-conditions'], true), 404);

        return view('storefront.static', ['page' => $page]);
    }

    /**
     * @return array<string, mixed>
     */
    private function filters(?string $categorySlug = null, ?SupportCollection $productIds = null): array
    {
        $baseQuery = Product::query()
            ->active()
            ->when($categorySlug, fn ($query) => $query->whereRelation('category', 'slug', $categorySlug))
            ->when($productIds, fn ($query) => $query->whereKey($productIds));
        $colors = (clone $baseQuery)->whereNotNull('color')->distinct()->pluck('color');

        return [
            'shareeTypes' => (clone $baseQuery)->whereNotNull('sharee_type')->distinct()->pluck('sharee_type'),
            'colors' => $colors,
            'colorOptions' => $this->colorOptions()->filter(fn (array $color): bool => $colors->contains($color['name']))->values(),
            'occasions' => (clone $baseQuery)->whereNotNull('occasion')->distinct()->pluck('occasion'),
            'fabrics' => (clone $baseQuery)->whereNotNull('fabric')->distinct()->pluck('fabric'),
            'workTypes' => (clone $baseQuery)->whereNotNull('work_type')->distinct()->pluck('work_type'),
        ];
    }

    private function applyProductFilters(Builder|BelongsToMany $products, Request $request): void
    {
        $products
            ->when($request->filled('sharee_type'), fn ($query) => $query->where('sharee_type', $request->string('sharee_type')))
            ->when($request->filled('color'), fn ($query) => $query->where('color', $request->string('color')))
            ->when($request->filled('occasion'), fn ($query) => $query->where('occasion', $request->string('occasion')))
            ->when($request->filled('fabric'), fn ($query) => $query->where('fabric', $request->string('fabric')))
            ->when($request->filled('work_type'), fn ($query) => $query->where('work_type', $request->string('work_type')))
            ->when($request->filled('availability'), fn ($query) => $query->whereHas('variants', fn ($variants) => $variants->where('quantity', '>', 0)))
            ->when($request->filled('offer'), fn ($query) => $query->whereNotNull('discount_price'))
            ->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->float('min_price')))
            ->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->float('max_price')));

        match ($request->string('sort')->toString()) {
            'price_low' => $products->orderBy('price'),
            'price_high' => $products->orderByDesc('price'),
            'popular' => $products->orderByDesc('is_best_seller'),
            default => $products->latest(),
        };
    }

    /**
     * @return SupportCollection<int, array{name: string, code: string}>
     */
    private function colorOptions(): SupportCollection
    {
        $colors = FashionAttribute::colorOptions();

        if ($colors->isNotEmpty()) {
            return $colors;
        }

        return Product::query()
            ->whereNotNull('color')
            ->distinct()
            ->pluck('color')
            ->map(fn (string $color): array => ['name' => $color, 'code' => '#c9a24a']);
    }
}
