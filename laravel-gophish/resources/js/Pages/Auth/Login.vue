<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    status: String,
    errors: Object,
});
</script>

<template>
    <Head title="Log in" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-4 text-center">
               <h1 class="text-2xl font-bold">Laravel Gophish</h1>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div v-if="errors.email" class="mb-4 font-medium text-sm text-red-600">
                {{ errors.email }}
            </div>

            <div class="flex items-center justify-center mt-4">
                <a :href="route('login.oauth', 'azure')" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login with Azure AD
                </a>
            </div>

            <div class="flex items-center justify-center mt-4">
                <a :href="route('login.oauth', 'oidc')" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login with OIDC
                </a>
            </div>
        </div>
    </div>
</template>
