<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    campaigns: Object,
});
</script>

<template>
    <Head title="Campaigns" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaigns
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-end mb-4">
                    <Link :href="route('campaigns.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        New Campaign
                    </Link>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Created Date
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="campaign in campaigns.data" :key="campaign.id">
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ campaign.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ new Date(campaign.created_at).toLocaleString() }}
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ campaign.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium">
                                <Link :href="route('campaigns.show', campaign.id)" class="text-indigo-600 hover:text-indigo-900">Details</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
