<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    campaign: Object,
});

const stats = computed(() => {
    const results = props.campaign.results;
    return {
        total: results.length,
        sent: results.filter(r => r.status !== 'Scheduled' && r.status !== 'Error').length,
        opened: results.filter(r => ['Opened', 'Clicked', 'Submitted'].includes(r.status)).length,
        clicked: results.filter(r => ['Clicked', 'Submitted'].includes(r.status)).length,
        submitted: results.filter(r => r.status === 'Submitted').length,
    };
});
</script>

<template>
    <Head :title="campaign.name" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ campaign.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Sent</div>
                        <div class="text-2xl font-bold">{{ stats.sent }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Opened</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ stats.opened }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Clicked</div>
                        <div class="text-2xl font-bold text-orange-600">{{ stats.clicked }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="text-gray-500 text-sm">Submitted Data</div>
                        <div class="text-2xl font-bold text-red-600">{{ stats.submitted }}</div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Timeline</h3>
                        <ul class="space-y-4">
                            <li v-for="event in campaign.events" :key="event.id" class="flex space-x-3">
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-sm font-medium">{{ event.message }}</h3>
                                        <p class="text-sm text-gray-500">{{ new Date(event.time).toLocaleString() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ event.email }}</p>
                                    <p v-if="event.details" class="text-xs text-gray-400">{{ event.details }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Recipients</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="result in campaign.results" :key="result.id">
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">{{ result.email }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">{{ result.status }}</td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">{{ result.send_date ? new Date(result.send_date).toLocaleString() : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
