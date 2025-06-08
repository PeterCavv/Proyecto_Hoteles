<template>
    <div class="w-full">
        <label class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <Chips
            v-model="modelValue"
            class="mt-1 w-full"
            :class="{ 'p-invalid': error }"
            @update:modelValue="$emit('update:modelValue', $event)"
        />
        <small v-if="error" class="text-red-500">{{ error }}</small>
    </div>
</template>

<script setup>
import Chips from 'primevue/chips';

defineProps({
    modelValue: Array,
    label: String,
    error: String
});
defineEmits(['update:modelValue']);
</script>
