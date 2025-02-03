<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_material', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('material_id')->constrained('materials')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->primary(['order_id', 'material_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_material');
    }
};
