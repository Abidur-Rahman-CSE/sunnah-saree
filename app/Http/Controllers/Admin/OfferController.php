<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OfferRequest;
use App\Models\Offer;
use App\Models\Product;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $offers = Offer::query()
            ->withCount('products')
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.offers.index', [
            'offers' => $offers->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.offers.form', [
            'offer' => new Offer,
            'products' => Product::query()->with(['category', 'images'])->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request): RedirectResponse
    {
        $offer = Offer::query()->create($this->payload($request));
        $offer->products()->sync($request->input('product_ids', []));

        return to_route('admin.offers.index')->with('status', 'Offer saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.offers.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer): View
    {
        return view('admin.offers.form', [
            'offer' => $offer->load('products'),
            'products' => Product::query()->with(['category', 'images'])->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferRequest $request, Offer $offer): RedirectResponse
    {
        $offer->update($this->payload($request));
        $offer->products()->sync($request->input('product_ids', []));

        return to_route('admin.offers.index')->with('status', 'Offer updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer): RedirectResponse
    {
        $offer->delete();

        return to_route('admin.offers.index')->with('status', 'Offer deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(OfferRequest $request): array
    {
        $validated = $request->validated();

        return [
            ...Arr::except($validated, ['product_ids', 'banner_file']),
            'slug' => ($validated['slug'] ?? null) ?: Str::slug($validated['title']),
            'banner_url' => app(AdminImage::class)->store($request->file('banner_file'), 'offers') ?? ($validated['banner_url'] ?? null),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
