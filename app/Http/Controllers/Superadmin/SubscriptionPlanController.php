<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::withCount('tenants')->get();
        $availableFeatures = SubscriptionPlan::availableFeatures();

        return view('superadmin.plans.index', compact('plans', 'availableFeatures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subscription_plans,code',
            'price' => 'required|numeric|min:0',
            'max_siswas' => 'required|integer|min:0',
            'max_schools' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        SubscriptionPlan::create([
            'name' => $request->name,
            'code' => strtolower($request->code),
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle ?? 'monthly',
            'max_siswas' => $request->max_siswas,
            'max_schools' => $request->max_schools,
            'features' => $request->features ?? [],
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Paket langganan baru berhasil dibuat!');
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_siswas' => 'required|integer|min:0',
            'max_schools' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'billing_cycle' => $request->billing_cycle ?? 'monthly',
            'max_siswas' => $request->max_siswas,
            'max_schools' => $request->max_schools,
            'features' => $request->features ?? [],
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Paket langganan ' . $plan->name . ' berhasil diperbarui!');
    }
}
