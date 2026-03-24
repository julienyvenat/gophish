<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    domain: '',
});

const submit = () => {
    form.post(route('clients.store'));
};
</script>

<template>
    <Head title="Create Client" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Client</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                                <input v-model="form.name" type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <div v-if="form.errors.name" class="text-red-500 text-xs italic">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label for="domain" class="block text-gray-700 text-sm font-bold mb-2">Domain</label>
                                <input v-model="form.domain" type="text" id="domain" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <div v-if="form.errors.domain" class="text-red-500 text-xs italic">{{ form.errors.domain }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" :disabled="form.processing">
                                    Create Client
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
