<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    group: Object,
});

const form = useForm({
    name: props.group.name,
    recipients: props.group.recipients || [],
});

const csvFile = ref(null);

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (e) => {
        const text = e.target.result;
        parseCSV(text);
    };
    reader.readAsText(file);
};

const parseCSV = (csvText) => {
    const lines = csvText.split('\n');
    const headers = lines[0].split(',').map(h => h.trim().toLowerCase().replace(/^"|"$/g, ''));

    const headerMap = {
        'first name': 'first_name',
        'last name': 'last_name',
        'email': 'email',
        'position': 'position'
    };

    const newRecipients = [];

    for (let i = 1; i < lines.length; i++) {
        const line = lines[i].trim();
        if (!line) continue;

        const values = line.split(',');
        const recipient = {
            first_name: '',
            last_name: '',
            email: '',
            position: ''
        };

        headers.forEach((header, index) => {
            const key = headerMap[header];
            if (key) {
                let val = values[index] ? values[index].trim() : '';
                val = val.replace(/^"|"$/g, '');
                recipient[key] = val;
            }
        });

        if (recipient.email) {
            newRecipients.push(recipient);
        }
    }

    form.recipients = [...form.recipients, ...newRecipients];
};

const removeRecipient = (index) => {
    form.recipients.splice(index, 1);
};

const submit = () => {
    form.put(route('groups.update', props.group.id));
};
</script>

<template>
    <Head title="Edit Recipient Group" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Recipient Group</h2>
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
                                <label class="block text-gray-700 text-sm font-bold mb-2">Import CSV</label>
                                <input type="file" @change="handleFileUpload" accept=".csv" class="mb-2">
                                <p class="text-xs text-gray-500">Format: First Name, Last Name, Email, Position</p>
                            </div>

                            <div class="mb-4">
                                <h3 class="font-bold mb-2">Recipients</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="(recipient, index) in form.recipients" :key="index">
                                                <td class="px-6 py-4 whitespace-nowrap"><input v-model="recipient.first_name" class="border rounded px-2 py-1 w-full"></td>
                                                <td class="px-6 py-4 whitespace-nowrap"><input v-model="recipient.last_name" class="border rounded px-2 py-1 w-full"></td>
                                                <td class="px-6 py-4 whitespace-nowrap"><input v-model="recipient.email" class="border rounded px-2 py-1 w-full"></td>
                                                <td class="px-6 py-4 whitespace-nowrap"><input v-model="recipient.position" class="border rounded px-2 py-1 w-full"></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button type="button" @click="removeRecipient(index)" class="text-red-600 hover:text-red-900">Remove</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" @click="form.recipients.push({first_name:'', last_name:'', email:'', position:''})" class="mt-2 text-blue-500 text-sm">+ Add Recipient</button>
                                <div v-if="form.errors.recipients" class="text-red-500 text-xs italic">{{ form.errors.recipients }}</div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" :disabled="form.processing">
                                    Update Group
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
