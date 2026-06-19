<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_supplier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('supplier_sku')->nullable(); // Supplier's product code
            $table->decimal('supplier_price', 10, 2)->nullable(); // Cost from supplier
            $table->integer('lead_time_days')->nullable(); // Days to deliver
            $table->boolean('is_default')->default(false); // Primary supplier
            $table->timestamps();

            // Unique combination to prevent duplicates
            $table->unique(['product_id', 'supplier_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_supplier');
    }
};