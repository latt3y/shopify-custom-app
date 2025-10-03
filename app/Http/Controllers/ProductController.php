<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $test = [
      "Product 1",
      "Product 2",
      "Product 3",
      "Product 4",
    ];

    public function get_all() {
      return $this->test;
    }

    public function create(Request $request)
    {
      $body = $reueqst->body;

      echo $body;
    }

    public function get_by_id(Product $product)
    {}

    public function update(Request $request, Product $product)
    {}

    public function destroy($id)
    {}
}
