<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->enum('product_type', ['mobile', 'accessory']);
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity_sold')->default(1);
            $table->decimal('selling_price', 10, 2);
            $table->timestamp('sold_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
