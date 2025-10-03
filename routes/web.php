<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\ProductController;

/* GET METHODS HERE */
Route::get("/auth/shopify/redirect", [ShopifyController::class, "redirect_to_shopify"]);
Route::get("/auth/shopify/handle", [ShopifyController::class, "handle_shopify_auth"]);
Route::get("/product", [ProductController::class, "get_all"]);
Route::get("/product/:id", [ProductController::class, "get_by_id"]);

/* POST METHODS HERE */
Route::post("/product", [ProductController::class, "create"]);

/* PUT METHODS HERE */
Route::put("/product/update", [ProductController::class, "update"]);

/* DELETE METHODS HERE */
Route::delete("/product/:id", [ProductController::class, "destroy"]);

/* REDIRECTS */
Route::redirect('/', '/product');
