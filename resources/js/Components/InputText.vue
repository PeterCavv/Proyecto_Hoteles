<template>
    <div class="w-full">
        <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <InputText
            :id="name"
            v-model="modelValue"
            :placeholder="placeholder"
            class="mt-1 w-full"
            :class="{ 'p-invalid': error }"
            @update:modelValue="$emit('update:modelValue', $event)"
        />
        <small v-if="error" class="text-red-500">{{ error }}</small>
    </div>
</template>

<script setup>
defineProps({
    modelValue: String,
    name: String,
    label: String,
    placeholder: String,
    error: String
});
defineEmits(['update:modelValue']);
</script>
