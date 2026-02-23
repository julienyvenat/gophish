<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    templates: Array,
    pages: Array,
    profiles: Array,
    groups: Array,
});

const form = useForm({
    name: '',
    email_template_id: null,
    landing_page_id: null,
    sending_profile_id: null,
    recipient_group_ids: [],
    url: '',
    launch_date: '',
    send_by_date: '',
});

const submit = () => {
    form.post(route('campaigns.store'));
};
</script>

<template>
    <Head title="New Campaign" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Campaign
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                    Name
                                </label>
                                <input v-model="form.name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" type="text" placeholder="Campaign Name">
                                <div v-if="form.errors.name" class="text-red-500 text-xs italic">{{ form.errors.name }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Email Template
                                </label>
                                <select v-model="form.email_template_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="template in templates" :key="template.id" :value="template.id">{{ template.name }}</option>
                                </select>
                                <div v-if="form.errors.email_template_id" class="text-red-500 text-xs italic">{{ form.errors.email_template_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Landing Page
                                </label>
                                <select v-model="form.landing_page_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="page in pages" :key="page.id" :value="page.id">{{ page.name }}</option>
                                </select>
                                <div v-if="form.errors.landing_page_id" class="text-red-500 text-xs italic">{{ form.errors.landing_page_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    URL
                                </label>
                                <input v-model="form.url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="http://example.com">
                                <div v-if="form.errors.url" class="text-red-500 text-xs italic">{{ form.errors.url }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Launch Date
                                </label>
                                <input v-model="form.launch_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="datetime-local">
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Sending Profile
                                </label>
                                <select v-model="form.sending_profile_id" class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">
                                    <option v-for="profile in profiles" :key="profile.id" :value="profile.id">{{ profile.name }}</option>
                                </select>
                                <div v-if="form.errors.sending_profile_id" class="text-red-500 text-xs italic">{{ form.errors.sending_profile_id }}</div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">
                                    Recipient Groups
                                </label>
                                <div v-for="group in groups" :key="group.id">
                                    <input type="checkbox" :value="group.id" v-model="form.recipient_group_ids"> {{ group.name }}
                                </div>
                                <div v-if="form.errors.recipient_group_ids" class="text-red-500 text-xs italic">{{ form.errors.recipient_group_ids }}</div>
                            </div>

                            <div class="flex items-center justify-between">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" :disabled="form.processing">
                                    Launch Campaign
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
