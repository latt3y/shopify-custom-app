<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
  protected $_client_id;
  protected $_apiSecret;
  protected $_scopes;
  protected $_store_name;
  protected $_uri;
  protected $_redirect_uri;

  public function __construct()
  {
    $this->_client_id = config("shopify.client_id");
    $this->_scopes = config("shopify.scopes");
    $this->_store_name = config("shopify.store_name");
    $this->_apiSecret = config("shopify.api_secret");
    $this->_app_uri = config("shopify.app_uri");
    $this->_redirect_uri = "{$this->_uri}/auth/shopify/handle";
  }

  private function shopify_oauth_redirect()
  {
    $url = "{$this->_app_uri}/auth?shop={$this->_store_name}";

    // TODO: if not authenticated then redirect else proceed
    return redirect($url);
  }

  private function shopify_auth_url(String $shop)
  {
    $auth_query = http_build_query([
      "client_id" => $this->_client_id,
      "scope" => $this->_scopes,
      "redirect_uri" => $this->_store_name,
      "state" => "nonce",
      "grant_options[]" => "per-user"
    ]);

    return "https://{$shop}/admin/oauth/authorize?" . $auth_query;
  }

  public function redirect_to_shopify(Request $request) {
    $shop = $request->get("shop") ?: $this->_store_name;

    return redirect()->to($this->shopify_auth_url($shop));
  }

  public function handle_shopify_auth(Request $request) {
    $shop = $request->get('shop');
    $code = $request->get('code');
    $hmac = $request->get('hmac');

  }
}
