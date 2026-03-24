<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    groups: Object,
});

const form = useForm({});

const deleteGroup = (id) => {
    if (confirm('Are you sure you want to delete this group?')) {
        form.delete(route('groups.destroy', id));
    }
};
</script>

<template>
    <Head title="Recipient Groups" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Recipient Groups</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-end mb-4">
                            <Link :href="route('groups.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                New Group
                            </Link>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="group in groups.data" :key="group.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ group.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ group.recipients_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ new Date(group.created_at).toLocaleDateString() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <Link :href="route('groups.edit', group.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                        <button @click="deleteGroup(group.id)" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Pagination here if needed -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
