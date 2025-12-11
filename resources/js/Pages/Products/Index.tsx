import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { FormEventHandler, useState } from 'react';
import PrimaryButton from '@/Components/PrimaryButton';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    stock_quantity: number;
    low_stock_threshold: number;
}

interface Props {
    products: Product[];
}

export default function Index({ products }: Props) {
    const [processing, setProcessing] = useState<number | null>(null);

    const addToCart = (productId: number) => {
        setProcessing(productId);
        router.post(route('cart.store'), {
            product_id: productId,
            quantity: 1,
        }, {
            preserveScroll: true,
            onFinish: () => setProcessing(null),
        });
    };

    const getStockStatus = (product: Product) => {
        if (product.stock_quantity === 0) {
            return { text: 'Out of Stock', className: 'text-red-600 font-semibold' };
        } else if (product.stock_quantity <= product.low_stock_threshold) {
            return { text: `Low Stock (${product.stock_quantity} left)`, className: 'text-yellow-600 font-semibold' };
        } else {
            return { text: `In Stock (${product.stock_quantity})`, className: 'text-green-600' };
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Products
                    </h2>
                    <Link
                        href={route('cart.index')}
                        className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        View Cart
                    </Link>
                </div>
            }
        >
            <Head title="Products" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                {products.map((product) => {
                                    const stockStatus = getStockStatus(product);
                                    const isOutOfStock = product.stock_quantity === 0;

                                    return (
                                        <div
                                            key={product.id}
                                            className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition-shadow hover:shadow-md dark:border-gray-700 dark:bg-gray-900"
                                        >
                                            <div className="p-6">
                                                <h3 className="mb-2 text-lg font-semibold text-gray-900 dark:text-white">
                                                    {product.name}
                                                </h3>
                                                <p className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                                    {product.description}
                                                </p>
                                                <div className="mb-4">
                                                    <span className="text-2xl font-bold text-gray-900 dark:text-white">
                                                        ${product.price.toFixed(2)}
                                                    </span>
                                                </div>
                                                <div className="mb-4">
                                                    <span className={`text-sm ${stockStatus.className}`}>
                                                        {stockStatus.text}
                                                    </span>
                                                </div>
                                                <PrimaryButton
                                                    onClick={() => addToCart(product.id)}
                                                    disabled={isOutOfStock || processing === product.id}
                                                    className={`w-full justify-center ${
                                                        isOutOfStock ? 'opacity-50 cursor-not-allowed' : ''
                                                    }`}
                                                >
                                                    {processing === product.id
                                                        ? 'Adding...'
                                                        : isOutOfStock
                                                          ? 'Out of Stock'
                                                          : 'Add to Cart'}
                                                </PrimaryButton>
                                            </div>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
