<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TenantSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Tenant::with(['subscriptionPlan', 'schools.siswas']);

        if ($request->has('status') && $request->status != '') {
            $query->where('subscription_status', $request->status);
        }

        if ($request->has('plan_id') && $request->plan_id != '') {
            $query->where('subscription_plan_id', $request->plan_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $tenants = $query->paginate(15);
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('superadmin.subscriptions.index', compact('tenants', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'subscription_status' => 'required|in:active,expired,suspended',
            'duration_months' => 'nullable|integer|min:0',
            'custom_expires_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->subscription_plan_id);

        $expiresAt = null;
        if ($request->custom_expires_at) {
            $expiresAt = Carbon::parse($request->custom_expires_at);
        } elseif ($request->duration_months > 0) {
            $baseDate = ($tenant->subscription_expires_at && $tenant->subscription_expires_at->isFuture())
                ? $tenant->subscription_expires_at
                : now();
            $expiresAt = (clone $baseDate)->addMonths((int)$request->duration_months);
        }

        $tenant->update([
            'subscription_plan_id' => $plan->id,
            'subscription_tier' => $plan->code,
            'subscription_status' => $request->subscription_status,
            'subscribed_at' => $tenant->subscribed_at ?? now(),
            'subscription_expires_at' => $expiresAt,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Langganan untuk sekolah/tenant ' . $tenant->name . ' berhasil diperbarui ke paket ' . $plan->name . '!');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = $tenant->subscription_status === 'active' ? 'suspended' : 'active';
        $tenant->update(['subscription_status' => $newStatus]);

        return redirect()->back()->with('success', 'Status langganan ' . $tenant->name . ' diubah menjadi ' . strtoupper($newStatus));
    }
}
