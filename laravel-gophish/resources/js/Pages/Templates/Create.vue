<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TemplateForm from '@/Components/TemplateForm.vue';

const submit = (form: any) => {
    form.post(route('templates.store'));
};
</script>

<template>
    <Head title="New Template" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                New Template
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <TemplateForm @submit="submit" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
