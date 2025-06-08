<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <InputText
            v-bind="$attrs"
            v-model="model"
            class="w-full"
            maxlength="250"
        />
    </div>
</template>

<script setup>
import InputText from 'primevue/inputtext';
defineProps({
    label: String
});
const model = defineModel();
</script>
