<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use App\Models\Shop;
use App\Models\Product;

Artisan::command('sync-products {shop}', function ($shop) {
  $this->comment("Starting the sync process");

  if (!$shop) {
    $this->line("please specify the shop name");
    return;
  }

  $shop_model = Shop::where("shop", $shop)->firstOrFail();
  $access_token = $shop_model->access_token;

  if (!$access_token) {
    $this->line("oooppss no access token found");
    return;
  }

  $api_version = config("shopify.api_version", "2025-10");
  $endpoint = "https://{$shop}/admin/api/{$api_version}/products.json";

  $limit = 250;
  $nextPageUrl = null;

  while (true) {
    if ($nextPageUrl) {
      $response = Http::withHeaders([
        'X-Shopify-Access-Token' => $access_token,
      ])->get($nextPageUrl);
    } else {
      $response = Http::withHeaders([
        'X-Shopify-Access-Token' => $access_token,
      ])->get($endpoint, ["limit" => $limit]);
    }

    if ($response->failed()) {
      $this->error("❌ Failed to fetch products for {$shop}");
      break;
    }

    $products = $response->json('products');

    Product::save_all($products);

    $linkHeader = $response->header('Link');

    if ($linkHeader) {
        preg_match('/<([^>]+)>; rel="next"/', $linkHeader, $matches);

        if (isset($matches[1])) {
            $nextPageUrl = $matches[1];
        }
    }

    usleep(500_000);

    if (!$nextPageUrl) break;
  }

  $this->line("Products for shop: `{$shop}` have been synchronized !!!");
})->purpose('Sync Shopify products to database');
