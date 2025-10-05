<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
  protected $_api_key;
  protected $_api_secret;
  protected $_scopes;
  protected $_api_version;
  protected $_shop_name;
  protected $_app_uri;
  protected $_redirect_uri;
  protected $_webhook_topics;

  public function __construct()
  {
    $this->_api_key = config("shopify.api_key");
    $this->_scopes = config("shopify.scopes");
    $this->_shop_name = config("shopify.shop_name");
    $this->_api_secret = config("shopify.api_secret");
    $this->_app_uri = config("shopify.app_uri");
    $this->_redirect_uri = "{$this->_app_uri}/auth/shopify/handle";
    $this->_api_version = config("shopify.api_version");
    $this->_webhook_topics = config("shopify.webhook_topics");
  }

  public function start_shopify_auth(Request $req)
  {
    $shop = $req->get("shop");

    if (!$shop) {
      $shop = $this->_shop_name;
    }

    $auth_query = http_build_query([
      "client_id" => $this->_api_key,
      "scope" => $this->_scopes,
      "redirect_uri" => $this->_redirect_uri,
      "state" => bin2hex(random_bytes(16)),
      "grant_options[]" => "per-user"
    ]);

    return redirect()->to("https://{$shop}/admin/oauth/authorize?".$auth_query);
  }

  private function is_valid_shop(String $shop) {
    return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com$/', $shop);
  }

  public function handle_shopify_auth(Request $resource) {
    $query = $resource->query();

    $shop = $query["shop"];

    if (!$this->is_valid_shop($shop)) {
      abort(400, "Bad request, shop name is weird");
    }

    $hmac = $query["hmac"];
    $code = $query["code"];
    $timestamp = $query["timestamp"];

    $params = $query;

    unset($params["hmac"]);

    ksort($params);

    $query_string = http_build_query($params, '', '&',  PHP_QUERY_RFC3986);

    $calculated_hmac = hash_hmac('sha256', $query_string, $this->_api_secret);

    if (!hash_equals($hmac, $calculated_hmac)) {
      abort(401, 'HMAC validation failed');
    }

    $response = Http::asForm()->post("https://{$shop}/admin/oauth/access_token", [
      'client_id' => $this->_api_key,
      'client_secret' => $this->_api_secret,
      'code' => $code,
    ]);

    if ($response->failed()) {
      abort(500, 'Failed to get access token from Shopify');
    }

    $access_token = $response->json()['access_token'];

    Shop::updateOrCreate(
      ["shop" => $shop],
      ["access_token" => $access_token]
    );

    return redirect()->route("dashboard", ["shop" => $shop]);
  }

  public function handleWebhook(Request $request) {
    $hmac = $request->header('X-Shopify-Hmac-Sha256');
    $shop = $request->header('X-Shopify-Shop-Domain');
    $topic = $request->header('X-Shopify-Topic');
    $payload = $request->getContent();

    $calculated_hmac = base64_encode(hash_hmac('sha256', $payload, $this->_api_secret));

    Log::info("request", [
      "hmac" => $hmac,
      "shop" => $shop,
      "topic" => $topic,
      "payload" => $payload
    ]);

    if (!hash_equals($hmac, $calculated_hmac)) {
      Log::error('HMAC mismatch!');
      return response('Invalid HMAC', 401);
    }

    $data = json_decode($payload, true);

    dispatch(new ProcessWebhookJob($payload, $topic, $shop));

    return response('Webhook received', 200);
  }
}
