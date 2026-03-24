<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    templates: Object,
});

const form = useForm({});

const deleteTemplate = (id) => {
    if (confirm('Are you sure you want to delete this template?')) {
        form.delete(route('templates.destroy', id));
    }
};
</script>

<template>
    <Head title="Email Templates" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Email Templates
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-end mb-4">
                            <Link :href="route('templates.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                New Template
                            </Link>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Modified Date
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="template in templates.data" :key="template.id">
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        {{ template.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        {{ new Date(template.updated_at).toLocaleString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                                        <Link :href="route('templates.edit', template.id)" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</Link>
                                        <button @click="deleteTemplate(template.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
