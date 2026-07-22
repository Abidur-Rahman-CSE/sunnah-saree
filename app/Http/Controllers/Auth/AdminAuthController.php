<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (request()->user()?->isAdmin()) {
            return to_route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $user = $this->findAdminByIdentifier($validated['identifier']);

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return back()
                ->withErrors(['identifier' => 'Admin login তথ্য সঠিক নয়।'])
                ->onlyInput('identifier');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return to_route('admin.dashboard');
    }

    private function findAdminByIdentifier(string $identifier): ?User
    {
        return User::query()
            ->where('role', 'admin')
            ->where(function ($query) use ($identifier): void {
                $query->where('email', $identifier)
                    ->orWhereIn('phone', $this->phoneVariants($identifier));
            })
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
