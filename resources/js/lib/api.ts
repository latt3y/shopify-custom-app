import type { ProductResponse, Product } from "../interface.ts";

class _ProductSyncAPI {
  public readonly shop_key = "my-shop";
  private static _instance: _ProductSyncAPI | null = null;
  public readonly base_url: string;
  protected api_version: string;
  protected shop: string | null = null;

  private base_api_url: string;

  private constructor() {
    this.api_version = import.meta.env.VITE_API_VERSION;
    this.base_url = import.meta.env.VITE_API_URL;
    this.base_api_url = `${this.base_url}/api/${this.api_version}`;
  }

  static getInstance(): _ProductSyncAPI | null {
    if (this._instance && this._instance instanceof _ProductSyncAPI) {
      return null;
    }

    this._instance = new _ProductSyncAPI();

    return this._instance;
  }

  public get_shop(): string | null {
    return this.shop;
  }

  async get_all_products(): Promise<ProductResponse | void> {
    try {
      console.log(this.base_api_url);
      const response = await fetch(`${this.base_api_url}/product`, {
        method: "GET",
        headers: {
          "X-Shopify-Shop": this.shop!,
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Access-Control-Allow-Origin": "*",
        },
      });

      return await response.json();
    } catch (error) {
      console.error("Could not fetch products: ", error);
      return { data: [] };
    }
  }

  async sync(data: Product[]): Promise<any> {
    try {
      const response = await fetch(`${this.base_api_url}/product/sync-all`, {
        method: "POST",
        headers: {
          "X-Shopify-Shop": this.shop!,
          "Content-Type": "application/json",
          "Accept": "application/json",
          "Access-Control-Allow-Origin": "*",
        },
        body: JSON.stringify({ products: data }),
      });

      if (response.ok) return response;
    } catch (err) {
      console.error("could not sync products");

      // return better code
      return [];
    }
  }

  public set_key(val: string | null): void {
     if(!val) {
       console.error("shop key does not exist");
       return;
     }

     this.shop = val;

     if (localStorage) {
       localStorage.setItem(this.shop_key, val);
     }
  }
}

export const ProductSyncAPI = _ProductSyncAPI.getInstance()!;
