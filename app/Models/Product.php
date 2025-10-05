<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  protected $fillable = [
    "shopify_id",
    "title",
    "description",
    "variants",
    "images",
    "prices",
    "inventory",
  ];

  protected $casts = [
    "variants" => "array",
    "images" => "array",
    "prices" => "array",
  ];

  public static function save_all($products) {
    foreach($products as $product) {
      $variants = $product["variants"];

      $inventory_q = 0;

      foreach($variants as $variant) {
        $inventory_q += $variant["inventory_quantity"] ?? 0;
      }

      self::updateOrCreate(
        ["shopify_id" => $product["id"]],
        [
          "title" => $product["title"],
          "description" => $product["body_html"] ?? null,
          "variants" => $variants,
          "images" => $product["images"] ?? [],
          "prices" => [
            "price" => $variants[0]["price"] ?? null,
            "compareAtPrice" => $variants[0]["compare_at_price"] ?? null,
          ],
          "inventory" => $inventory_q
        ]
      );
    }
  }
}
