import { Nav } from "./dashboard";

interface ServerProps {
  product: {
    title: string;
    description: string;
    inventory: number;
    prices: any;
    images: any;
    variants: any[];
    created_at: any;
    updated_at: any;
  }
}

export default function ProductDetail({ product }: ServerProps) {
  if (!product) return <div className="text-gray-500">No product found.</div>;

  const {
    title,
    description,
    inventory,
    images,
    prices,
    variants = [],
    created_at,
    updated_at,
  } = product;

  const variant = variants[0];

  return (
    <div className="max-w-4xl mx-auto px-4 py-8">
      <Nav />

      <div className="border-b pb-4 mb-6">
        <h1 className="text-3xl font-bold text-gray-800">{title}</h1>
        <p className="text-sm text-gray-500">
          Created: {new Date(created_at).toLocaleDateString()} | Updated:{" "}
          {new Date(updated_at).toLocaleDateString()}
        </p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="bg-gray-100 aspect-square rounded-md flex items-center justify-center text-gray-400">
          {(images.length && images[0]?.src) ? (
              <img src={images[0].src} className="block h-full w-full" alt={title} />
            ) : "No Image"}
        </div>

        <div className="flex flex-col space-y-4">
          <div>
            <span className="text-gray-600">Price:</span>
            <p className="text-xl font-semibold text-green-600">
              ${prices?.price}
            </p>
            {prices?.compareAtPrice && prices.compareAtPrice !== prices.price && (
              <p className="text-sm line-through text-red-500">
                ${prices.compareAtPrice}
              </p>
            )}
          </div>

          <div>
            <span className="text-gray-600">Inventory:</span>
            <p className="text-md text-gray-800">{inventory} units</p>
          </div>

          <div>
            <span className="text-gray-600">SKU:</span>
            <p className="text-md text-gray-800">{variant?.sku || "N/A"}</p>
          </div>

          <div>
            <span className="text-gray-600">Weight:</span>
            <p className="text-md text-gray-800">
              {variant?.weight} {variant?.weight_unit}
            </p>
          </div>

          <div>
            <span className="text-gray-600">Variant Title:</span>
            <p className="text-md text-gray-800">{variant?.title}</p>
          </div>
        </div>
      </div>

      <div className="mt-8">
        <h2 className="text-xl font-semibold text-gray-700 mb-2">Description</h2>
        <div
          className="prose max-w-none"
          dangerouslySetInnerHTML={{ __html: description }}
        />
      </div>
    </div>
  );
}
