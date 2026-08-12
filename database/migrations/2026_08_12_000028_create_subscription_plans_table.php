<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Pro, Enterprise
            $table->string('code')->unique(); // free, pro, enterprise
            $table->decimal('price', 12, 2)->default(0);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly, lifetime
            $table->integer('max_siswas')->default(50); // 0 = unlimited
            $table->integer('max_schools')->default(1); // 0 = unlimited
            $table->json('features')->nullable(); // list of feature keys
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add subscription columns to tenants table
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'subscription_plan_id')) {
                $table->foreignId('subscription_plan_id')->nullable()->constrained('subscription_plans')->onDelete('set null')->after('code');
            }
            if (!Schema::hasColumn('tenants', 'subscription_status')) {
                $table->enum('subscription_status', ['active', 'expired', 'suspended'])->default('active')->after('status');
            }
            if (!Schema::hasColumn('tenants', 'subscribed_at')) {
                $table->timestamp('subscribed_at')->nullable()->after('subscription_expires_at');
            }
            if (!Schema::hasColumn('tenants', 'notes')) {
                $table->text('notes')->nullable()->after('subscribed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['subscription_plan_id']);
            $table->dropColumn(['subscription_plan_id', 'subscription_status', 'subscribed_at', 'notes']);
        });
        Schema::dropIfExists('subscription_plans');
    }
};
