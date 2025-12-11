import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { FormEventHandler } from 'react';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        description: '',
        price: '',
        stock_quantity: '',
        low_stock_threshold: '10',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('admin.products.store'));
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Create New Product
                    </h2>
                    <Link
                        href={route('admin.products.index')}
                        className="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    >
                        ← Back to Products
                    </Link>
                </div>
            }
        >
            <Head title="Create Product" />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <form onSubmit={submit} className="p-6 space-y-6">
                            <div>
                                <InputLabel htmlFor="name" value="Product Name" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    className="mt-1 block w-full"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                    autoFocus
                                />
                                <InputError message={errors.name} className="mt-2" />
                            </div>

                            <div>
                                <InputLabel htmlFor="description" value="Description" />
                                <textarea
                                    id="description"
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    rows={4}
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    required
                                />
                                <InputError message={errors.description} className="mt-2" />
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel htmlFor="price" value="Price ($)" />
                                    <TextInput
                                        id="price"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        className="mt-1 block w-full"
                                        value={data.price}
                                        onChange={(e) => setData('price', e.target.value)}
                                        required
                                    />
                                    <InputError message={errors.price} className="mt-2" />
                                </div>

                                <div>
                                    <InputLabel htmlFor="stock_quantity" value="Stock Quantity" />
                                    <TextInput
                                        id="stock_quantity"
                                        type="number"
                                        min="0"
                                        className="mt-1 block w-full"
                                        value={data.stock_quantity}
                                        onChange={(e) => setData('stock_quantity', e.target.value)}
                                        required
                                    />
                                    <InputError message={errors.stock_quantity} className="mt-2" />
                                </div>

                                <div>
                                    <InputLabel htmlFor="low_stock_threshold" value="Low Stock Alert" />
                                    <TextInput
                                        id="low_stock_threshold"
                                        type="number"
                                        min="1"
                                        className="mt-1 block w-full"
                                        value={data.low_stock_threshold}
                                        onChange={(e) => setData('low_stock_threshold', e.target.value)}
                                    />
                                    <InputError message={errors.low_stock_threshold} className="mt-2" />
                                    <p className="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Alert when stock drops to this level
                                    </p>
                                </div>
                            </div>

                            <div className="flex items-center justify-end gap-4 pt-4">
                                <Link
                                    href={route('admin.products.index')}
                                    className="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                                >
                                    Cancel
                                </Link>
                                <PrimaryButton disabled={processing}>
                                    {processing ? 'Creating...' : 'Create Product'}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
