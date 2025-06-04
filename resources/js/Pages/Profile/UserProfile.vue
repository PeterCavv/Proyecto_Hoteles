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

/**
 * This function is used to submit the form data to update the user profile.
 * It uses the Inertia form helper to send a PUT request to the specified route.
 * On success, it shows a success message and closes the edit dialog.
 * On error, it displays error messages for each validation error.
 */
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

            console.error('Errores:', errors);
        },
    });
};

/**
 * This function is used to delete the user account.
 * It uses the Inertia form helper to send a DELETE request to the specified route.
 * On error, it displays error messages for each validation error.
 */
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

/**
 * This function is used to log out the user.
 * It uses the Inertia form helper to send a POST request to the logout route.
 */
const logOut = () => {
    form.post(route('logout'));
};
</script>

<template>
    <Toast />
    <Head title="Perfil de Usuario" />

    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="bg-white shadow-2xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8">
            <h2 class="text-3xl font-extrabold text-gray-900">
                {{ t('messages.user_profile.user_details') }}
            </h2>
            <p class="text-gray-600">
                {{ t('messages.user_profile.user_details_description') }}
            </p>
            <div class="mt-6">
                <p class="text-lg font-semibold text-gray-800">
                    {{ t('messages.user_profile.name') }}: {{ user.name }}
                </p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ t('messages.user_profile.email') }}: {{ user.email }}
                </p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ t('messages.user_search.role') }}: {{ user.role_name }}
                </p>
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

            <Button
                v-if="$page.props.auth.user.id === user.id"
                :label="t('messages.user_profile.log_out')"
                icon="pi pi-trash"
                class="p-button-secondary w-full"
                @click="logOut"
            />

            <Dialog
                    v-model:visible="ifEdit"
                    modal
                    :header="t('messages.user_profile.edit_profile')"
                    :style="{ width: '50vw' }"
                    :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
            >
                <p class="m-0">
                    {{ t('messages.user_profile.edit_profile_description') }}
                </p>
                <form @submit.prevent = submitForm>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.name"
                                :invalid="form.errors.name"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.name') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.email"
                                :invalid="form.errors.email"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.email') }}</label>
                        </FloatLabel>
                        <template v-if="user.role_name === 'customer'">
                            <FloatLabel variant="on">
                                <InputText
                                    v-model="form.dni"
                                    :invalid="form.errors.dni"
                                    class="w-full"
                                />
                                <label for="on_label">{{ t('messages.user_profile.dni') }}</label>
                            </FloatLabel>
                        </template>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.phone_number"
                                :invalid="form.errors.phone_number"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.telephone') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.city"
                                :invalid="form.errors.city"
                                class="w-full"
                            />
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
                        :invalid="form.errors.password"
                        type="password"
                        class="w-full mt-4"
                        :placeholder="t('messages.deleting_account.confirm_password')"
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
