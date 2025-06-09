<template>
    <div class="w-full">
        <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <Dropdown
            :id="name"
            v-model="modelValue"
            :options="options"
            :optionLabel="optionLabel"
            :optionValue="optionValue"
            :placeholder="placeholder"
            class="mt-1 w-full"
            showClear
            :class="{ 'p-invalid': error }"
            @update:modelValue="$emit('update:modelValue', $event)"
        />
        <small v-if="error" class="text-red-500">{{ error }}</small>
    </div>
</template>

<script setup>
defineProps({
    modelValue: [String, Number, Object],
    name: String,
    label: String,
    options: Array,
    optionLabel: { type: String, default: 'label' },
    optionValue: { type: String, default: 'value' },
    placeholder: String,
    error: String
});
defineEmits(['update:modelValue']);
</script>
