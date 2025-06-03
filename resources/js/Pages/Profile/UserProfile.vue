<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {useI18n} from "vue-i18n";
import Dialog from "primevue/dialog";
import {ref} from "vue";
import InputText from "primevue/inputtext";
import {FloatLabel} from "primevue";
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

const {user} = defineProps({
    user: Object,
});

const ifEdit = ref(false);
const ifDelete = ref(false);
const confirmPassword = ref(false);
const { t } = useI18n();
const toast = useToast();

defineOptions({
    layout: MainLayout,
});

const form = useForm({
    name: user.name || '',
    email: user.email || '',
    dni: user.customer?.dni || '',
    phone_number: user.phone_number || '',
    city: user.city || '',
    password: '',
});

const submitForm = () => {
    form.submit('put', route('profile.update', user.id), {
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: t('messages.state.saved'),
                detail: t('messages.state.saved_successfully'),
                life: 3000,
            });
            ifEdit.value = false;
        },
        onError: (errors) => {
            // Recorrer todos los errores y mostrar uno por uno
            Object.values(errors).forEach((errorMessages) => {
                // errorMessages puede ser un string o un array de strings
                if (Array.isArray(errorMessages)) {
                    errorMessages.forEach((msg) => {
                        toast.add({
                            severity: 'error',
                            summary: t('messages.state.error'),
                            detail: msg,
                            life: 4000,
                        });
                    });
                } else {
                    toast.add({
                        severity: 'error',
                        summary: t('messages.state.error'),
                        detail: errorMessages,
                        life: 4000,
                    });
                }
            });

            console.error('Errores:', errors);
        },
    });
};

const deleteAccount = () => {
    form.delete(route('profile.destroy', user.id), {
        onError: (errors) => {
            Object.values(errors).forEach((errorMessages) => {
                if (Array.isArray(errorMessages)) {
                    errorMessages.forEach((msg) => {
                        toast.add({
                            severity: 'error',
                            summary: t('messages.state.error'),
                            detail: msg,
                            life: 4000,
                        });
                    });
                } else {
                    toast.add({
                        severity: 'error',
                        summary: t('messages.state.error'),
                        detail: errorMessages,
                        life: 4000,
                    });
                }
            });

            console.error('Errores al eliminar la cuenta:', errors);
        },
    });
};
</script>

<template>
    <Toast />
    <Head title="Perfil de Usuario" />

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="bg-white shadow-2xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8">
            <h2 class="text-3xl font-extrabold text-gray-900">Perfil de Usuario</h2>
            <p class="text-gray-600">Aquí puedes ver y editar tu perfil.</p>
            <div class="mt-6">
                <p class="text-lg font-semibold text-gray-800">Nombre: {{ user.name }}</p>
                <p class="text-lg font-semibold text-gray-800">Email: {{ user.email }}</p>
                <p class="text-lg font-semibold text-gray-800">Rol: {{ user.role_name }}</p>
            </div>

            <Button
                :label="t('messages.buttons.edit')"
                icon="pi pi-pencil"
                class="p-button-info w-full"
                @click="ifEdit = true"/>

            <Button
                v-if="user.role_name !== 'admin'"
                :label="t('messages.buttons.delete')"
                icon="pi pi-trash"
                class="p-button-danger w-full"
                @click="ifDelete = true"/>

            <Dialog v-model:visible="ifEdit" modal header="Editar Cuenta" :style="{ width: '50vw' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <p class="m-0">
                    Aquí puedes editar tu información de perfil. Asegúrate de que todos los campos sean correctos antes de guardar.
                </p>
                <form @submit.prevent = submitForm>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <FloatLabel variant="on">
                            <InputText v-model="form.name" class="w-full"/>
                            <label for="on_label">{{ t('messages.user_profile.name') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText v-model="form.email" class="w-full"/>
                            <label for="on_label">{{ t('messages.user_profile.email') }}</label>
                        </FloatLabel>
                        <template v-if="user.role_name === 'customer'">
                            <FloatLabel variant="on">
                                <InputText v-model="form.dni" class="w-full"/>
                                <label for="on_label">{{ t('messages.user_profile.dni') }}</label>
                            </FloatLabel>
                        </template>
                        <FloatLabel variant="on">
                            <InputText v-model="form.phone_number" class="w-full"/>
                            <label for="on_label">{{ t('messages.user_profile.telephone') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText v-model="form.city" class="w-full"/>
                            <label for="on_label">{{ t('messages.user_profile.address') }}</label>
                        </FloatLabel>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <Button
                            :label="t('messages.buttons.cancel')"
                            icon="pi pi-times"
                            class="p-button-secondary"
                            @click="ifEdit = false" />
                        <Button
                            :label="t('messages.buttons.save_changes')"
                            icon="pi pi-check"
                            class="p-button-success"
                            type="submit"/>
                    </div>
                </form>
            </Dialog>

            <Dialog v-model:visible="ifDelete"
                    modal
                    :header="t('messages.deleting_account.title')"
                    :style="{ width: '50vw' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
                <p class="m-0">
                    {{ t('messages.deleting_account.description') }}
                </p>
                <form v-if="confirmPassword === true" @submit.prevent ="deleteAccount">
                    <InputText
                        v-model="form.password"
                        type="password"
                        class="w-full mt-4"
                        placeholder="Contraseña de confirmación"
                        :aria-label="t('messages.deleting_account.confirm_password')"/>
                    <Button
                        :label="t('messages.buttons.delete')"
                        icon="pi pi-check"
                        type="submit"
                        class="p-button-danger w-full mt-4"
                    />
                </form>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <Button
                        :label="t('messages.deleting_account.cancel')"
                        icon="pi pi-times"
                        class="p-button-secondary"
                        @click="ifDelete = false; confirmPassword = false"/>
                    <Button
                        :label="t('messages.deleting_account.confirm')"
                        icon="pi pi-check"
                        class="p-button-danger"
                        :disabled="confirmPassword === true"
                        @click="confirmPassword = true" />
                </div>
            </Dialog>
        </div>
    </div>
</template>
