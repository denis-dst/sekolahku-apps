<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\School;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $plans = SubscriptionPlan::where('is_active', true)->get();
        $selectedPlanCode = $request->query('plan', 'pro');

        return view('auth.register', compact('plans', 'selectedPlanCode'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'yayasan_name' => 'required|string|max:255',
            'school_name' => 'required|string|max:255',
            'jenjang' => 'required|string',
            'admin_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:30',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        return DB::transaction(function () use ($request, $plan) {
            // Generate unique tenant code
            $baseCode = strtoupper(Str::slug(Str::limit($request->yayasan_name, 12, ''), ''));
            if (empty($baseCode)) {
                $baseCode = 'YAYASAN';
            }
            $code = $baseCode . '-' . strtoupper(Str::random(4));
            while (Tenant::where('code', $code)->exists()) {
                $code = $baseCode . '-' . strtoupper(Str::random(4));
            }

            $isFree = ($plan->price == 0);
            $subscriptionStatus = $isFree ? 'active' : 'pending';
            $expiresAt = $isFree ? now()->addMonths(1) : null;

            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->yayasan_name,
                'code' => $code,
                'subscription_plan_id' => $plan->id,
                'subscription_tier' => $plan->code,
                'status' => 'active',
                'subscription_status' => $subscriptionStatus,
                'subscription_expires_at' => $expiresAt,
                'subscribed_at' => $isFree ? now() : null,
                'notes' => 'Pendaftaran Mandiri Web (' . now()->format('d/m/Y H:i') . ') - Paket: ' . $plan->name,
            ]);

            // 2. Create First School
            $school = School::create([
                'tenant_id' => $tenant->id,
                'name' => $request->school_name,
                'jenjang' => $request->jenjang,
                'phone' => $request->phone,
                'email' => $request->email,
                'status' => 'active',
            ]);

            // 3. Create Admin User
            $user = User::create([
                'tenant_id' => $tenant->id,
                'school_id' => $school->id,
                'name' => $request->admin_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            // Assign role: if plan is enterprise / yayasan -> 'Yayasan Admin', otherwise 'School Admin'
            if (in_array(strtolower($plan->code), ['enterprise', 'yayasan'])) {
                $user->assignRole('Yayasan Admin');
            } else {
                $user->assignRole('School Admin');
            }

            if ($isFree) {
                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Selamat datang di SekolahKu-Apps! Pendaftaran Yayasan/Sekolah Anda berhasil.');
            }

            return redirect()->route('login')->with('success_pending', 'Pendaftaran berhasil! Permintaan lisensi ' . $plan->name . ' untuk "' . $tenant->name . '" sedang menunggu verifikasi & approval (ACC) oleh Superadmin. Silakan hubungi kami untuk konfirmasi aktivasi.');
        });
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if tenant is pending
            if ($user->tenant && $user->tenant->subscription_status === 'pending' && !$user->hasRole('Superadmin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Yayasan/Sekolah Anda masih menunggu persetujuan (ACC) dari Superadmin. Silakan hubungi kami untuk aktivasi lisensi.',
                ])->onlyInput('email');
            }

            // Check if tenant is suspended
            if ($user->tenant && $user->tenant->subscription_status === 'suspended' && !$user->hasRole('Superadmin')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Layanan akun Yayasan/Sekolah Anda saat ini sedang ditangguhkan. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
