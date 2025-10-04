<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
  protected $_apiKey;
  protected $_apiSecret;
  protected $_scopes;
  protected $_redirectUri;
  protected $uri;

  public function __construct()
  {
    // TODO: add these configs
    $this->_apiKey = config();
    $this->_scopes = config();
    $this->_redirectUri = config();
    $this->_apiSecret = config();
  }

  private function shopify_oauth_redirect(Request $request)
  {
    $url = "{$this->uri}/auth?shop={$this->_redirectUri}";

    // TODO: if not authenticated then redirect else proceed
    return redirect($url);
  }

  private function shopify_auth_url(String $shop)
  {
    $k = $this->_apiKey;
    $s = $this->_scopes;
    $r = $this->_redirectUri;

    return "https://{$shop}/admin/oauth/authorize?client_id={$k}&scope={$s}&redirect_uri={$r}&state=nonce&grant_options[]=per-user";
  }

  public function redirect_to_shopify(Request $request) {
    $shop = $request->get("shop");

    if (!$shop)
    {
      return response()
        ->json([
          "message" => "failed",
          "status" => "Shop not specified in your request",
          "code" => 403,
        ])
        ->header("Content-Type", "application/json");
    }

    return redirect($this->shopify_auth_url($shop));
  }

  public function handle_shopify_auth(Request $request) {
    $shop = $request->get('shop');
    $code = $request->get('code');
    $hmac = $request->get('hmac');
  }
}
