<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $banners = Banner::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search')->toString().'%')->orWhere('placement', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.banners.index', [
            'banners' => $banners->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.banners.form', [
            'banner' => new Banner,
            'placements' => $this->availablePlacements(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request): RedirectResponse
    {
        Banner::query()->create($this->payload($request));

        return to_route('admin.banners.index')->with('status', 'Banner saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.banners.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner): View
    {
        return view('admin.banners.form', [
            'banner' => $banner,
            'placements' => Banner::placements(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banner $banner): RedirectResponse
    {
        $banner->update($this->payload($request));

        return to_route('admin.banners.index')->with('status', 'Banner updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner): RedirectResponse
    {
        $banner->delete();

        return to_route('admin.banners.index')->with('status', 'Banner deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(BannerRequest $request): array
    {
        return [
            ...collect($request->validated())->except('image_file')->all(),
            'image_url' => app(AdminImage::class)->store($request->file('image_file'), 'banners') ?? ($request->validated()['image_url'] ?? null),
            'is_active' => $request->boolean('is_active'),
        ];
    }

    /**
     * @return array<string, array{label: string, description: string, multiple: bool}>
     */
    private function availablePlacements(): array
    {
        $usedSinglePlacements = Banner::query()
            ->whereIn('placement', collect(Banner::placements())->where('multiple', false)->keys())
            ->pluck('placement')
            ->all();

        return collect(Banner::placements())
            ->reject(fn (array $placement, string $key): bool => ! $placement['multiple'] && in_array($key, $usedSinglePlacements, true))
            ->all();
    }
}
