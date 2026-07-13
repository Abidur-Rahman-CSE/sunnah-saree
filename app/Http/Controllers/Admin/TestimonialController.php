<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Support\AdminImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $testimonials = Testimonial::query()
            ->when($request->filled('search'), fn ($query) => $query
                ->where('customer_name', 'like', '%'.$request->string('search')->toString().'%')
                ->orWhere('message', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->orderBy('sort_order')
            ->latest();

        return view('admin.testimonials.index', [
            'testimonials' => $testimonials->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TestimonialRequest $request): RedirectResponse
    {
        Testimonial::query()->create($this->payload($request));

        return to_route('admin.testimonials.index')->with('status', 'Testimonial saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial): RedirectResponse
    {
        return to_route('admin.testimonials.edit', $testimonial);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->payload($request, $testimonial));

        return to_route('admin.testimonials.index')->with('status', 'Testimonial updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        app(AdminImage::class)->deleteUrl($testimonial->image_url);
        $testimonial->delete();

        return to_route('admin.testimonials.index')->with('status', 'Testimonial deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(TestimonialRequest $request, ?Testimonial $testimonial = null): array
    {
        $imageUrl = app(AdminImage::class)->store($request->file('image_file'), 'testimonials');

        if ($imageUrl && $testimonial) {
            app(AdminImage::class)->deleteUrl($testimonial->image_url);
        }

        return [
            ...collect($request->validated())->except('image_file')->all(),
            'image_url' => $imageUrl ?? ($request->validated()['image_url'] ?? $testimonial?->image_url),
            'sort_order' => $request->integer('sort_order'),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
