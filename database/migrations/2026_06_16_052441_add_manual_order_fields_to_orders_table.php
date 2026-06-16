<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Only add columns that don't already exist
            if (!Schema::hasColumn('orders', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('orders', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
            if (!Schema::hasColumn('orders', 'guest_mobile')) {
                $table->string('guest_mobile')->nullable()->after('guest_email');
            }
            if (!Schema::hasColumn('orders', 'manual_discount')) {
                $table->decimal('manual_discount', 10, 2)->default(0)->after('discount_amount');
            }
            if (!Schema::hasColumn('orders', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0)->after('manual_discount');
            }
            if (!Schema::hasColumn('orders', 'is_manual_order')) {
                $table->boolean('is_manual_order')->default(false)->after('tax_amount');
            }
            if (!Schema::hasColumn('orders', 'created_by_admin')) {
                $table->unsignedBigInteger('created_by_admin')->nullable()->after('is_manual_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'guest_name', 'guest_email', 'guest_mobile',
                'manual_discount', 'tax_amount', 'is_manual_order', 'created_by_admin'
            ]);
        });
    }
};