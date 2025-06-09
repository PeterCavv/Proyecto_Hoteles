<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <InputText
            v-bind="$attrs"
            v-model="model"
            :class="['w-full', { 'p-invalid': error }]"
            maxlength="250"
        />
        <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
import InputText from 'primevue/inputtext';

defineProps({
    label: String,
    error: String,
});

const model = defineModel();
</script>
