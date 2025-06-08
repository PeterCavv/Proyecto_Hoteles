<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <Textarea
            v-bind="$attrs"
            v-model="model"
            class="w-full"
            maxlength="500"
            autoResize
        />
    </div>
</template>

<script setup>
import Textarea from 'primevue/textarea';
defineProps({
    label: String
});
const model = defineModel();
</script>
