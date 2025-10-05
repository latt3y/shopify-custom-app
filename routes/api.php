<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopifyController;

Route::get("/{api_version}/product", [ProductController::class, "get_all_from_shopify"]);
Route::get("/{api_version}/product/{id}", [ProductController::class, "get_by_id"]);

Route::get("/{api_version}/product/update", [ProductController::class, "update"]);
Route::delete("/{api_version}/product/{id}", [ProductController::class, "destroy"]);
Route::post("/{api_version}/product", [ProductController::class, "create"]);
Route::post("/{api_version}/product/sync-all", [ProductController::class, "sync"]);

Route::post('/webhooks', [ShopifyController::class, 'handleWebhook'])->name('shopify.webhook');
