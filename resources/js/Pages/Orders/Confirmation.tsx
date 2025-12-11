import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Confirmation() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Order Confirmation
                </h2>
            }
        >
            <Head title="Order Confirmation" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-center text-gray-900 dark:text-gray-100">
                            <div className="mb-6">
                                <svg
                                    className="mx-auto h-16 w-16 text-green-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <h1 className="mb-4 text-3xl font-bold">Order Placed Successfully!</h1>
                            <p className="mb-8 text-gray-600 dark:text-gray-400">
                                Thank you for your order. Your products will be processed shortly.
                            </p>
                            <div className="flex justify-center space-x-4">
                                <Link
                                    href={route('products.index')}
                                    className="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-sm font-medium text-white hover:bg-indigo-700"
                                >
                                    Continue Shopping
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
