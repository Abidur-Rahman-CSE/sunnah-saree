<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $coupons = Coupon::query()
            ->when($request->filled('search'), fn ($query) => $query->where('code', 'like', '%'.$request->string('search')->toString().'%'))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->string('status')->toString() === 'active'))
            ->latest();

        return view('admin.coupons.index', [
            'coupons' => $coupons->paginate(20)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.coupons.form', ['coupon' => new Coupon]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CouponRequest $request): RedirectResponse
    {
        Coupon::query()->create($this->payload($request));

        return to_route('admin.coupons.index')->with('status', 'Coupon saved.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return to_route('admin.coupons.edit', $id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.form', ['coupon' => $coupon]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($this->payload($request));

        return to_route('admin.coupons.index')->with('status', 'Coupon updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return to_route('admin.coupons.index')->with('status', 'Coupon deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(CouponRequest $request): array
    {
        return [
            ...$request->validated(),
            'code' => Str::upper($request->string('code')->toString()),
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
