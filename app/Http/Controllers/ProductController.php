<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Log;
use App\Models\Shop;
use App\Models\Product;

class ProductController extends Controller
{
    public function get_all_from_shopify(Request $request, $api_version) {
      $shop = $request->header("X-Shopify-Shop");

      if (!$shop) {
        return abort(400, "Bad request, missing headers");
      }

      $shop_response = Shop::where("shop", $shop)->firstOrFail();

      $products_res = Http::withHeaders([
        'X-Shopify-Access-Token' => $shop_response->access_token
      ])->get("https://{$shop}/admin/api/{$api_version}/products.json");

      if ($products_res->failed()) {
        return "fetching products failed";
      }

      return response()->json([
        "data" => $products_res->json()["products"],
      ]);
    }

    public function get_all_local(Request $request) {
      $products = Product::all();

      return Inertia::render("product", ["products" => $products ?? []]);
    }

    public function show_by_id($id) {
      $product = Product::where("shopify_id",(int) $id)->firstOrFail();

      Log::info("quering product by shopify_id ".$id);

      return Inertia::render("productDetail", ["product" => $product]);
    }

    public function create(Request $request)
    {
      $body = $request->body;

      echo $body;
    }

    public function get_by_id($id)
    {
      $product = Product::where("shopify_id",(int) $id)->firstOrFail();

      Log::info("quering product by shopify_id ".$id);

      return response()->json([
        "product" => $product
      ]);
    }

    public function sync(Request $request, $api_version)
    {
      $shop = $request->header('X-Shopify-Shop');

      Shop::where("shop", $shop)->firstOrFail();

      $data = $request->json()->all();

      if (!isset($data["products"])) {
        return response()->json(['error' => 'No products found in request.'], 400);
      }

      Product::save_all($data["products"]);

      return response()->json([
        "code" => "ok",
        "status" => 200,
      ]);
    }

    public function update(Request $request, Product $product)
    {}

    public function destroy($id)
    {}
}
