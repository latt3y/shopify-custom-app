export interface Product {
  id: number | string;
  title: string;
  body_html: string;
  variants: any[];
  options: any[];
  prices: Record<string, number>;
  inventory: number;
  images: Record<string, string>;
}

export interface ProductResponse {
  data: Product[];
}
