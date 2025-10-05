import { Head, Link } from "@inertiajs/react";
import type { Product } from "../interface";
import { ProductSyncAPI } from "../lib/api";
import { useEffect, useState, ReactNode } from "react";

interface ServerProps {
  shop: string;
}

function Variant(props: any) {
  return (
    <p className="text-xs mt-2">
      {props.name}
    </p>
  );
}

function Product(props: Product) {
  const [isExpanded, setIsExpanded] = useState<boolean>(false);

  return (
    <div className="border-[#d4d4d4] border-1 rounded-sm p-2 mt-1">
      <div className="flex items-center gap-3">
        <Link className="underline" href={`/product/${props.id}`}>
          {props.title}
        </Link>
      </div>

      <button
        className="cursor-pointer text-sm"
        onClick={() => setIsExpanded(!isExpanded)}
      >
        Options: {props.options.length}
      </button>

      {isExpanded && (
        <div className="pl-2">
          {props.options.map((variant) => <Variant key={variant.name} {...variant} />)}
        </div>
      )}
    </div>
  );
}

export function Nav() {
  const [shop, setShop] = useState<string>("");

  useEffect(() => {
    if (ProductSyncAPI.get_shop()) {
      setShop(ProductSyncAPI.get_shop()!);
    }
  }, []);

  return (
    <nav className="flex gap-6 mb-4">
      <Link
        className="text-md text-black cursor-pointer"
        href={`/dashboard?shop=${shop}`}
      >
        Dashboard
      </Link>

      <Link
        className="text-md text-black cursor-pointer"
        href="/product"
      >
        My Products
      </Link>
    </nav>
  );
}

export default function Dashboard({ shop }: ServerProps) {
  const [_shop, _set_shop] = useState<string | null>(null);
  const [products, _set_products] = useState<Product[]>([]);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [refreshProducts, setRefreshProducts] = useState<boolean>(false);

  async function fetch_products() {
    setIsLoading(true);
    const products_res = await ProductSyncAPI.get_all_products();

    if (products_res) {
      _set_products(products_res.data);
    }

    setIsLoading(false);
  }

  useEffect(() => {
    ProductSyncAPI.set_key(shop);

    fetch_products();
    _set_shop(shop);
  }, []);

  useEffect(() => {
    fetch_products();
  }, [refreshProducts]);

  return (
    <>
      <Head title="Dashboard">
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
      </Head>

      <div className="max-w-4xl mx-auto px-4 py-8">
        <Nav />
        <h1 className="text-3xl">Dashboard</h1>
        <p className="text-sm mt-2 text-[#555]">These products are retrieved from Shopify store directly<br />Refresh if you made any update in Shopify!<br />And synchronize if you need to</p>
        <div className="mt-10 flex gap-3 items-end text-sm">
          <p>Products for shop: <a className="underline" target="_blank" href={`https://${shop}`}>{shop}</a></p>
          <button
            disabled={isLoading}
            className="block flex disabled:opacity-30 ml-auto items-center gap-1 w-fit rounded-sm border border border-black px-2 py-1 cursor-pointer hover:bg-black hover:text-white"
            onClick={() => setRefreshProducts(prev => !prev)}
          >
            refresh
          </button>
          <button
            className="block w-fit disabled:opacity-30 rounded-sm border border border-black px-2 py-1 cursor-pointer hover:bg-black hover:text-white"
            disabled={isLoading}
            onClick={async () => {
              setIsLoading(true);
              await ProductSyncAPI.sync(products);
              setIsLoading(false);
            }}
          >
            synchronize
          </button>
        </div>
        <div className="mt-2 h-[2px] w-full bg-[#a6a6a6]" />
        <div className="mx-3 mt-5">
          {(products.length > 0 && !isLoading) ? (
            products.map(product => <Product key={product.title} {...product} />)
          ) : (
            <p>Loading...</p>
          )}
        </div>
      </div>
    </>
  );
}
