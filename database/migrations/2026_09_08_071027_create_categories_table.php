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
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('categoryid');
            $table->string('categoryname')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('productid');
            $table->string('productname')->unique();
            $table->decimal('price', 20, 2)->default(0);
            $table->integer('quantity');
            $table->decimal('amount', 20, 2)->storedAs('price * quantity');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('catid');
            $table->foreign('catid')->references('categoryid')->on('categories')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
