<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopifyController;

Route::get("/auth/shopify/redirect", [ShopifyController::class, "start_shopify_auth"])
  ->name("shopify.redirect");

Route::get("/product", [ProductController::class, "get_all_local"])
  ->name("product.local");

Route::get("/product/{id}", [ProductController::class, "show_by_id"]);

Route::get("/auth/shopify/handle", [ShopifyController::class, "handle_shopify_auth"])
  ->name("shopify.handle");

Route::get("/dashboard", function (Request $req) {
  $query = $req->query();

  if (isset($query["shop"])) {
    $shop = $query["shop"];
  } else if ($req->header("X-Shopify-Shop")) {
    $shop = $req->header("X-Shopify-Shop");
  } else {
    return redirect()->route("shopify.redirect");
  }

  return Inertia::render("dashboard", [
    "shop" => $shop,
  ]);
})->name("dashboard");

Route::get("/", function (Request $req) {
  if ($req->has('hmac') && $req->has('shop') && $req->has("timestamp")) {
    return redirect()->route('shopify.redirect', $req);
  }

  return redirect()->to("/dashboard");
})->name("home");
