<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restock_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('created_by')->constrained('admins')->onDelete('restrict');
            $table->date('request_date');
            $table->date('expected_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->enum('status', [
                'draft', 
                'sent', 
                'acknowledged', 
                'ordered', 
                'partially_received', 
                'received', 
                'closed', 
                'cancelled'
            ])->default('draft');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('request_number');
            $table->index('status');
            $table->index('request_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('restock_requests');
    }
};