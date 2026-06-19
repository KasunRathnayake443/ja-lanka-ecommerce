<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restock_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_request_id')->constrained()->onDelete('cascade');
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->constrained('admins')->onDelete('restrict');
            $table->timestamps();
            
            // Indexes
            $table->index('restock_request_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('restock_status_history');
    }
};