import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useState } from 'react';
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';

interface Product {
    id: number;
    name: string;
    price: number;
    stock_quantity: number;
}

interface CartItem {
    id: number;
    quantity: number;
    product: Product;
}

interface Props {
    cartItems: CartItem[];
    total: number;
}

export default function Index({ cartItems, total }: Props) {
    const [processing, setProcessing] = useState<number | null>(null);

    const updateQuantity = (cartItemId: number, quantity: number) => {
        setProcessing(cartItemId);
        router.patch(route('cart.update', cartItemId), {
            quantity,
        }, {
            preserveScroll: true,
            onFinish: () => setProcessing(null),
        });
    };

    const removeItem = (cartItemId: number) => {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            setProcessing(cartItemId);
            router.delete(route('cart.destroy', cartItemId), {
                preserveScroll: true,
                onFinish: () => setProcessing(null),
            });
        }
    };

    const checkout = () => {
        router.post(route('checkout.store'));
    };

    if (cartItems.length === 0) {
        return (
            <AuthenticatedLayout
                header={
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Shopping Cart
                    </h2>
                }
            >
                <Head title="Cart" />

                <div className="py-12">
                    <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                            <div className="p-6 text-center text-gray-900 dark:text-gray-100">
                                <p className="mb-4 text-lg">Your cart is empty</p>
                                <Link
                                    href={route('products.index')}
                                    className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700"
                                >
                                    Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </AuthenticatedLayout>
        );
    }

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Shopping Cart
                    </h2>
                    <Link
                        href={route('products.index')}
                        className="text-sm text-indigo-600 hover:text-indigo-700 dark:text-indigo-400"
                    >
                        Continue Shopping
                    </Link>
                </div>
            }
        >
            <Head title="Cart" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="space-y-4">
                                {cartItems.map((item) => (
                                    <div
                                        key={item.id}
                                        className="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                                    >
                                        <div className="flex-1">
                                            <h3 className="text-lg font-semibold">
                                                {item.product.name}
                                            </h3>
                                            <p className="text-sm text-gray-600 dark:text-gray-400">
                                                ${item.product.price.toFixed(2)} each
                                            </p>
                                        </div>
                                        <div className="flex items-center space-x-4">
                                            <div className="flex items-center space-x-2">
                                                <button
                                                    onClick={() => updateQuantity(item.id, item.quantity - 1)}
                                                    disabled={item.quantity <= 1 || processing === item.id}
                                                    className="rounded-md bg-gray-200 px-3 py-1 text-gray-700 hover:bg-gray-300 disabled:opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                                >
                                                    -
                                                </button>
                                                <span className="min-w-[2rem] text-center">
                                                    {item.quantity}
                                                </span>
                                                <button
                                                    onClick={() => updateQuantity(item.id, item.quantity + 1)}
                                                    disabled={
                                                        item.quantity >= item.product.stock_quantity ||
                                                        processing === item.id
                                                    }
                                                    className="rounded-md bg-gray-200 px-3 py-1 text-gray-700 hover:bg-gray-300 disabled:opacity-50 dark:bg-gray-700 dark:text-gray-300"
                                                >
                                                    +
                                                </button>
                                            </div>
                                            <div className="min-w-[6rem] text-right">
                                                <span className="font-semibold">
                                                    ${(item.quantity * item.product.price).toFixed(2)}
                                                </span>
                                            </div>
                                            <DangerButton
                                                onClick={() => removeItem(item.id)}
                                                disabled={processing === item.id}
                                            >
                                                Remove
                                            </DangerButton>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <div className="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700">
                                <div className="flex items-center justify-between">
                                    <div className="text-xl font-bold">Total:</div>
                                    <div className="text-2xl font-bold">
                                        ${total.toFixed(2)}
                                    </div>
                                </div>
                                <div className="mt-6">
                                    <PrimaryButton
                                        onClick={checkout}
                                        className="w-full justify-center py-3"
                                    >
                                        Proceed to Checkout
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
