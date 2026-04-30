<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Combo;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        ]);
    }

    public function products(Request $request): View
    {
        $products = Product::query()
            ->active()
            ->with(['category', 'images'])
            ->when($request->filled('category'), fn ($query) => $query->whereRelation('category', 'slug', $request->string('category')))
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

        return view('storefront.products.index', [
            'products' => $products->paginate(12)->withQueryString(),
            'categories' => Category::query()->where('is_active', true)->get(),
            'filters' => $this->filters(),
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
        ]);
    }

    public function category(Category $category): View
    {
        return view('storefront.products.index', [
            'products' => $category->products()->active()->with('images')->paginate(12),
            'categories' => Category::query()->where('is_active', true)->get(),
            'filters' => $this->filters(),
            'pageTitle' => $category->name,
        ]);
    }

    public function collection(Collection $collection): View
    {
        return view('storefront.collection', [
            'collection' => $collection,
            'products' => $collection->products()->active()->with('images')->paginate(12),
        ]);
    }

    public function offers(): View
    {
        return view('storefront.offers', [
            'offers' => Offer::query()->where('is_active', true)->with('products.images')->get(),
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
    private function filters(): array
    {
        return [
            'shareeTypes' => Product::query()->whereNotNull('sharee_type')->distinct()->pluck('sharee_type'),
            'colors' => Product::query()->whereNotNull('color')->distinct()->pluck('color'),
            'occasions' => Product::query()->whereNotNull('occasion')->distinct()->pluck('occasion'),
            'fabrics' => Product::query()->whereNotNull('fabric')->distinct()->pluck('fabric'),
            'workTypes' => Product::query()->whereNotNull('work_type')->distinct()->pluck('work_type'),
        ];
    }
}
