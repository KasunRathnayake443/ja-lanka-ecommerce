<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restock_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            $table->integer('quantity_requested');
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->decimal('total_cost', 10, 2)->nullable();
            $table->string('supplier_sku')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('product_id');
            $table->unique(['restock_request_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('restock_request_items');
    }
};