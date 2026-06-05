<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flash_sale_banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('custom_title')->nullable();
            $table->string('custom_subtitle')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(false);
            $table->boolean('is_manual')->default(false); // True = admin added manually
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flash_sale_banners');
    }
};
