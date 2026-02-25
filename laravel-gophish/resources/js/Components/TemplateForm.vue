<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Editor from 'primevue/editor';
import Tabs from 'primevue/tabs';
import TabList from 'primevue/tablist';
import Tab from 'primevue/tab';
import TabPanels from 'primevue/tabpanels';
import TabPanel from 'primevue/tabpanel';
import Message from 'primevue/message';
import { Template } from '@/types';

const props = defineProps<{
    template?: Template;
}>();

const emit = defineEmits<{
    (e: 'submit', form: any): void;
}>();

const form = useForm({
    name: props.template?.name || '',
    subject: props.template?.subject || '',
    html: props.template?.html || '',
    text: props.template?.text || '',
});

const submit = () => {
    emit('submit', form);
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="field">
            <label for="name" class="block font-medium text-gray-700 mb-2">Name</label>
            <InputText id="name" v-model="form.name" class="w-full" :invalid="!!form.errors.name" />
            <Message v-if="form.errors.name" severity="error" variant="simple" size="small">{{ form.errors.name }}</Message>
        </div>

        <div class="field">
            <label for="subject" class="block font-medium text-gray-700 mb-2">Subject</label>
            <InputText id="subject" v-model="form.subject" class="w-full" :invalid="!!form.errors.subject" />
            <Message v-if="form.errors.subject" severity="error" variant="simple" size="small">{{ form.errors.subject }}</Message>
        </div>

        <div class="field">
            <label class="block font-medium text-gray-700 mb-2">HTML Content</label>
            <Tabs value="edit">
                <TabList>
                    <Tab value="edit">Edit</Tab>
                    <Tab value="preview">Preview</Tab>
                </TabList>
                <TabPanels>
                    <TabPanel value="edit">
                        <Editor v-model="form.html" editorStyle="height: 320px" />
                        <Message v-if="form.errors.html" severity="error" variant="simple" size="small">{{ form.errors.html }}</Message>
                    </TabPanel>
                    <TabPanel value="preview">
                        <div class="border rounded p-4 bg-gray-50 min-h-[320px]" v-html="form.html"></div>
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </div>

        <div class="field">
            <label for="text" class="block font-medium text-gray-700 mb-2">Text Content</label>
            <Textarea id="text" v-model="form.text" rows="5" class="w-full" :invalid="!!form.errors.text" />
            <Message v-if="form.errors.text" severity="error" variant="simple" size="small">{{ form.errors.text }}</Message>
        </div>

        <div class="flex justify-end gap-2">
            <Button type="button" label="Cancel" severity="secondary" @click="route('templates.index')" />
            <Button type="submit" label="Save Template" :loading="form.processing" />
        </div>
    </form>
</template>

<style scoped>
/* Add any custom styles here if Tailwind is not enough */
</style>
