<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <Dropdown
            v-bind="$attrs"
            v-model="model"
            :options="options"
            :optionLabel="optionLabel"
            :optionValue="optionValue"
            class="w-full"
            showClear
        />
    </div>
</template>

<script setup>
import Dropdown from 'primevue/dropdown';
defineProps({
    label: String,
    options: Array,
    optionLabel: {
        type: String,
        default: 'label'
    },
    optionValue: {
        type: String,
        default: 'value'
    }
});
const model = defineModel();
</script>
