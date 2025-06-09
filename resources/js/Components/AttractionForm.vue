<template>
    <form @submit.prevent="submitForm" class="space-y-6">
        <BaseInput
            v-model="form.name"
            :label="t('messages.attractions.form.name')"
            :placeholder="t('messages.attractions.form.name_placeholder')"
            :error="error?.name[0]"
        />

        <BaseTextarea
            v-model="form.description"
            :label="t('messages.attractions.form.description')"
            :placeholder="t('messages.attractions.form.description_placeholder')"
        />

        <BaseSelect
            v-model="form.type"
            :label="t('messages.attractions.form.type')"
            :options="typeOptions"
            :placeholder="t('messages.attractions.form.type_placeholder')"
        />

        <BaseSelect
            v-model="form.city_id"
            :label="t('messages.attractions.form.city')"
            :options="citiesOptions"
            option-label="name"
            option-value="id"
            :placeholder="t('messages.attractions.form.city_placeholder')"
        />

        <div class="flex justify-end">
            <Button type="submit" :label="t('messages.common.save')" icon="pi pi-save" />
        </div>
    </form>
</template>

<script setup>
import {reactive, watch, computed, onMounted} from 'vue';
import { useI18n } from 'vue-i18n';
import BaseInput from '@/Components/Form/BaseInput.vue';
import BaseSelect from '@/Components/Form/BaseSelect.vue';
import Button from 'primevue/button';
import { useCity } from '@/Composables/useCity.js';
import BaseTextarea from "@/Components/Form/BaseTextarea.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: false,
        default: () => ({})
    }
});

const emits = defineEmits(['submit']);

const { t } = useI18n();
const { fetchCities, cities, error } = useCity();

const form = reactive({
    name: props.modelValue?.name || '',
    type: props.modelValue?.type || null,
    description: props.modelValue?.description || '',
    city_id: props.modelValue?.city_id || null,
});

const typeOptions = [
    { label: t('messages.attractions.form.free'), value: 'free' },
    { label: t('messages.attractions.form.pay'), value: 'pay' },
];

const citiesOptions = computed(() => cities.value || []);

watch(() => props.modelValue, (newVal) => {
    Object.assign(form, newVal);
});

const submitForm = () => {
    emits('submit', { ...form });
};

onMounted(() => {
    fetchCities()
})
</script>
