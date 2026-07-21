<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.smart');
    }

    public function login(Request $request): RedirectResponse
    {
        if ($request->filled('email') && ! $request->filled('phone')) {
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

        return $this->authenticateByPhone($request);
    }

    public function phoneCheck(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $user = $this->findUserByPhone($request->string('phone')->toString());

        return response()->json([
            'exists' => (bool) $user,
            'name' => $user?->name,
        ]);
    }

    private function authenticateByPhone(Request $request): RedirectResponse
    {
        $base = $request->validate([
            'phone' => ['required', 'string', 'max:30'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->findUserByPhone($base['phone']);

        if ($user) {
            if (! Hash::check($base['password'], $user->password)) {
                return back()->withErrors(['password' => 'Password does not match this phone number.'])->onlyInput('phone');
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return to_route($user->isAdmin() ? 'admin.dashboard' : 'account.dashboard');
        }

        return $this->register($request);
    }

    public function showRegister(): View
    {
        return view('auth.smart');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
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

    private function findUserByPhone(string $phone): ?User
    {
        return User::query()
            ->whereIn('phone', $this->phoneVariants($phone))
            ->first();
    }

    /**
     * @return array<int, string>
     */
    private function phoneVariants(string $phone): array
    {
        $phoneDigits = preg_replace('/\D+/', '', $phone);

        if (! $phoneDigits) {
            return [$phone];
        }

        $phoneVariants = [$phone, $phoneDigits];

        if (str_starts_with($phoneDigits, '880')) {
            $phoneVariants[] = '+'.$phoneDigits;
            $phoneVariants[] = '0'.substr($phoneDigits, 3);
        }

        if (str_starts_with($phoneDigits, '0')) {
            $phoneVariants[] = '88'.$phoneDigits;
            $phoneVariants[] = '+88'.$phoneDigits;
        }

        return array_values(array_unique(array_filter($phoneVariants)));
    }
}
