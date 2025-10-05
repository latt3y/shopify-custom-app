<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create("products", function (Blueprint $table) {
        $table->id();
        $table->bigInteger('shopify_id')->unique();
        $table->string("title");
        $table->string("description")->nullable();
        $table->json("variants");
        $table->integer("inventory");
        $table->json("images")->nullable();
        $table->json("prices");
        $table->timestamps();
      });
    }

    public function down(): void
    {
      Schema::dropIfExists("products");
    }
};
