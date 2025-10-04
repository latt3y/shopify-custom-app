<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ShopifyController;
use App\Http\Controllers\ProductController;

/* GET METHODS HERE */
/* Route::get("/auth", [ShopifyController::class, "start_auth"]); */

Route::get("/auth/shopify/redirect", [ShopifyController::class, "start_shopify_auth"])
  ->name("shopify.redirect");

Route::get("/auth/shopify/handle", [ShopifyController::class, "handle_shopify_auth"])
  ->name("shopify.handle");

Route::get("/dashboard", function () {

})->name("dashboard");

Route::get("/", function (Request $req) {
  if ($req->has('hmac') && $req->has('shop') && $req->has("timestamp")) {
    return redirect()->route('shopify.redirect', $req);
  }

  return Inertia::render("home");
})->name("home");

Route::get("/product", [ProductController::class, "get_all"]);
Route::get("/product/{id}", [ProductController::class, "get_by_id"]);

/* POST METHODS HERE */
Route::post("/product", [ProductController::class, "create"]);

/* PUT METHODS HERE */
Route::put("/product/update", [ProductController::class, "update"]);

/* DELETE METHODS HERE */
Route::delete("/product/{id}", [ProductController::class, "destroy"]);
