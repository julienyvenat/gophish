<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TemplateForm from '@/Components/TemplateForm.vue';
import { Template } from '@/types';

const props = defineProps<{
    template: Template;
}>();

const submit = (form: any) => {
    form.put(route('templates.update', props.template.id));
};
</script>

<template>
    <Head title="Edit Template" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Template: {{ template.name }}
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <TemplateForm :template="template" @submit="submit" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
