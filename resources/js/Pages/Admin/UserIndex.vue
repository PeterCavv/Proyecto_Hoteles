<template>
    <Head title="Usuarios" />

    <div class="max-w-6xl mx-auto px-4 py-10">
        <h2 class="text-3xl font-extrabold text-gray-900 p-3">
            {{ t('messages.user_search.user_management') }}
        </h2>
        <div class="bg-white shadow-2xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <section class="bg-gray-50 p-6 rounded-xl shadow-inner space-y-4">
                    <h3 class="text-xl font-semibold text-gray-700">
                        {{ t('messages.user_search.filter') }}
                    </h3>

                    <IconField>
                        <InputIcon class="pi pi-search" />
                        <InputText
                            v-model="filterEmail"
                            :placeholder="t('messages.user_search.search_by_email')"
                            class="w-full"
                        />
                    </IconField>

                    <Select
                        id="status"
                        v-model="filterRole"
                        :options="roleOptions"
                        optionLabel="label"
                        optionValue="value"
                        :placeholder="t('messages.user_search.search_role')"
                        class="w-full"
                    />

                    <div class="relative">
                        <Listbox
                            :options="filteredUsers"
                            v-model="selectedUser"
                            class="w-full bg-white border border-gray-300 rounded-md min-h-60 max-h-60 overflow-auto focus:ring-2 focus:ring-blue-500"
                            :aria-labelledby="'users-listbox-label'"
                        >
                            <template #option="{ option, active, selected }">
                                <li
                                    :aria-selected="selected"
                                    tabindex="0"
                                    @keydown.enter="$event.target.click()"
                                    class="px-3 py-1 cursor-pointer"
                                >
                                    {{ option.email }}
                                </li>
                            </template>
                            <template #empty>
                                <li class="text-gray-500 py-2">
                                    {{ t('messages.user_search.no_users_found') }}
                                </li>
                            </template>
                        </Listbox>
                    </div>
                </section>

                <section class="bg-gray-50 p-6 rounded-xl shadow-inner space-y-4">
                    <h3 class="text-xl font-semibold text-gray-700">
                        {{ t('messages.user_profile.user_details') }}
                    </h3>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">
                                {{ t('messages.user_profile.name') }}
                            </label>
                            <InputText :value="selectedUser?.name" disabled class="w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">
                                {{ t('messages.user_profile.email') }}
                            </label>
                            <InputText :value="selectedUser?.email" disabled class="w-full" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">
                                {{ t('messages.user_search.role') }}
                            </label>
                            <InputText :value="selectedUser?.role_name" disabled class="w-full" />
                        </div>
                    </div>

                    <Button
                        :disabled="!selectedUser || selectedUser.id === $page.props.auth.user.id || selectedUser.role_name === 'admin'"
                        :label="t('messages.user_search.impersonate')"
                        icon="pi pi-user"
                        class="w-full mt-2"
                        @click="router.get(`/impersonate/${selectedUser.id}`)"
                        :class="selectedUser && selectedUser.role_name !== 'admin' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 text-gray-600 cursor-not-allowed'"
                    />

                    <div class="grid grid-cols-2 gap-4">
                        <Button
                            :label="t('messages.buttons.edit')"
                            icon="pi pi-pencil"
                            severity="info"
                            class="w-full"
                            @click="router.visit(route('users.edit', { user: selectedUser.id }))"
                            :disabled="!selectedUser"
                        />
                        <Button
                            :label="t('messages.buttons.delete')"
                            icon="pi pi-trash"
                            class="w-full"
                            severity="danger"
                            @click="router.delete(route('users.destroy', { user: selectedUser.id }))"
                            :disabled="!selectedUser || selectedUser.role_name === 'admin'"
                        />
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Head, router} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import 'primeicons/primeicons.css'
import {computed, ref} from "vue";
import InputText from 'primevue/inputtext';
import Listbox from 'primevue/listbox';
import Button from 'primevue/button';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import {useI18n} from "vue-i18n";

const { t } = useI18n();

const props = defineProps({
    users: Object,
    roles: Array,
});

const roleOptions = [
    {label: 'Todos', value: ''},
    {label: 'Administrador', value: 'admin'},
    {label: 'Cliente', value: 'customer'},
    {label: 'Hotel', value: 'hotel'},
];

defineOptions({
    name: "CustomerIndex",
    layout: MainLayout,
});

const filterEmail = ref('')
const filterRole = ref('')
const selectedUser = ref(null)

const filteredUsers = computed(() => {
    return props.users.filter(user => {
        const matchesEmail = user.email.toLowerCase().includes(filterEmail.value.toLowerCase())
        const matchesRole = user.role_name.toLowerCase().includes(filterRole.value.toLowerCase())
        return matchesEmail && matchesRole
    })
})
</script>

