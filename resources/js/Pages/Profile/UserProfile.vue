<template>
    <Toast />
    <Head title="Perfil de Usuario" />

    <div class="max-w-6xl mx-auto px-4 py-10 lg:w-[800px]">
        <div class="bg-white shadow-2xl rounded-2xl p-8 ring-1 ring-gray-200 space-y-8">
            <div class="flex flex-row justify-between items-center mb-4">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    {{ t('messages.user_profile.user_details') }}
                </h2>
                <SplitButton
                    v-if="$page.props.auth.user.id === user.id"
                    severity="info"
                    :label="t('messages.user_profile.log_out')"
                    @click="logOut(form)"
                    dropdownIcon="pi pi-cog"
                    :model="items"
                />
            </div>
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

            <Link
                :href="`/profile/${user.id}/reviews/`"
                icon="pi pi-list"
                severity="contrast"
                class="p-button p-button-contrast w-full"
            >
                <span>{{ t('messages.user_profile.show_reviews') }}</span>
            </Link>

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
                <form @submit.prevent="submitForm(form, user, () => { ifEdit = false })">
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.name"
                                :class="{ 'p-invalid': form.errors.name }"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.name') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.email"
                                :class="{ 'p-invalid': form.errors.email }"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.email') }}</label>
                        </FloatLabel>
                        <template v-if="user.role_name === 'customer'">
                            <FloatLabel variant="on">
                                <InputText
                                    v-model="form.dni"
                                    :class="{ 'p-invalid': form.errors.dni }"
                                    class="w-full"
                                />
                                <label for="on_label">{{ t('messages.user_profile.dni') }}</label>
                            </FloatLabel>
                        </template>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.phone_number"
                                :class="{ 'p-invalid': form.errors.phone_number }"
                                class="w-full"
                            />
                            <label for="on_label">{{ t('messages.user_profile.telephone') }}</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputText
                                v-model="form.city"
                                :class="{ 'p-invalid': form.errors.city }"
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
                <form v-if="confirmPassword === true" @submit.prevent ="deleteAccount(form, user)">
                    <InputText
                        v-model="form.password"
                        :class="{ 'p-invalid': form.errors.password }"
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

<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {useI18n} from "vue-i18n";
import Dialog from "primevue/dialog";
import {ref} from "vue";
import InputText from "primevue/inputtext";
import {FloatLabel} from "primevue";
import Toast from 'primevue/toast';
import {useUserProfile} from "@/Composables/useUserProfile.js";
import SplitButton from "primevue/splitbutton";

const {user} = defineProps({
    user: Object,
});

const {logOut, submitForm, deleteAccount} = useUserProfile();

const ifEdit = ref(false);
const ifDelete = ref(false);
const confirmPassword = ref(false);
const { t } = useI18n();

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

const items = [
    {
        label: t('messages.user_profile.edit_profile'),
        icon: 'pi pi-refresh',
        command: () => {
            ifEdit.value = true;
        }
    },
    {
        separator: true
    },
    {
        label: t('messages.buttons.delete'),
        icon: 'pi pi-times',
        command: () => {
            ifDelete.value = true;
        }
    }
];
</script>
