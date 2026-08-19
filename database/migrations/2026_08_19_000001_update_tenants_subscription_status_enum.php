<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `tenants` MODIFY COLUMN `subscription_status` ENUM('active', 'expired', 'suspended', 'pending') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `tenants` MODIFY COLUMN `subscription_status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'");
        }
    }
};
