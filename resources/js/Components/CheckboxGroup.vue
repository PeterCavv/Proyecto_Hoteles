<template>
    <div class="w-full">
        <label class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <div class="flex flex-wrap gap-2 mt-2">
            <div v-for="option in options" :key="option.value" class="flex items-center gap-2">
                <Checkbox
                    :inputId="option.value"
                    :value="option.value"
                    v-model="modelValue"
                    :name="name"
                />
                <label :for="option.value">{{ option.label }}</label>
            </div>
        </div>
        <small v-if="error" class="text-red-500">{{ error }}</small>
    </div>
</template>

<script setup>
import Checkbox from 'primevue/checkbox';

defineProps({
    modelValue: Array,
    options: Array,
    name: String,
    label: String,
    error: String
});
defineEmits(['update:modelValue']);
</script>
