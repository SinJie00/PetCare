<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('products_in_need', 'product_in_needs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('product_in_needs', 'products_in_need');
    }
};
