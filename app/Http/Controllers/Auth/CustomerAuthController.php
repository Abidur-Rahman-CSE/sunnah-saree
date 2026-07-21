<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return to_route($request->user()->isAdmin() ? 'admin.dashboard' : 'account.dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            ...$validated,
            'role' => 'customer',
            'password' => Hash::make($validated['password']),
        ]);

        $this->attachGuestOrdersByPhone($user);

        Auth::login($user);
        $request->session()->regenerate();

        return to_route('account.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }

    private function attachGuestOrdersByPhone(User $user): void
    {
        if (! $user->phone) {
            return;
        }

        $phoneDigits = preg_replace('/\D+/', '', $user->phone);

        if (! $phoneDigits) {
            return;
        }

        $phoneVariants = [$user->phone, $phoneDigits];

        if (str_starts_with($phoneDigits, '880')) {
            $phoneVariants[] = '+'.$phoneDigits;
            $phoneVariants[] = '0'.substr($phoneDigits, 3);
        }

        if (str_starts_with($phoneDigits, '0')) {
            $phoneVariants[] = '88'.$phoneDigits;
            $phoneVariants[] = '+88'.$phoneDigits;
        }

        $phoneVariants = array_values(array_unique(array_filter($phoneVariants)));

        Order::query()
            ->whereNull('user_id')
            ->whereIn('customer_phone', $phoneVariants)
            ->update(['user_id' => $user->id]);
    }
}
