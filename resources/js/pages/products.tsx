import { Link } from "@inertiajs/react";
import { Nav } from "./dashboard";
import { Product } from "@/interface";

interface ServerProps {
  products: Product[];
}

const ProductCard = (product: Product) => {
  const { title, prices, inventory, variants } = product;
  const variant = variants[0] || {};

  return (
    <div className="border rounded-md p-4 shadow-sm hover:shadow-md transition bg-white">
      <h2 className="text-lg font-semibold text-gray-800 mb-2">
        <Link href={`/product/${product.shopify_id}`}>
          {title}
        </Link>
      </h2>

      <div className="text-sm text-gray-600 mb-2">
        SKU: <span className="text-gray-800">{variant?.sku || "N/A"}</span>
      </div>

      <div className="text-sm text-gray-600">
        Price:{" "}
        <span className="text-green-600 font-medium">${prices?.price}</span>
        {prices?.compareAtPrice &&
          prices.compareAtPrice !== prices.price && (
            <span className="line-through text-red-400 ml-2">
              ${prices.compareAtPrice}
            </span>
          )}
      </div>

      <div className="text-sm text-gray-600 mt-1">
        Inventory: <span className="text-gray-800">{inventory}</span>
      </div>
    </div>
  );
};

export default function Products({ products }: ServerProps) {
  if (!products?.length) {
    return <div className="p-6 text-gray-500">No products found.</div>;
  }

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <Nav />
      <h1 className="text-3xl font-bold text-gray-800 mb-6">Synchronized Products</h1>

      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        {products.map((product) => (
          <ProductCard key={product.id} {...product} />
        ))}
      </div>
    </div>
  );
}
