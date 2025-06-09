<template>
    <Button
        :label="label"
        :icon="icon"
        :iconPos="iconPos"
        :severity="severity"
        :type="type"
        class="w-full"
        v-bind="$attrs"
        @click="$emit('click', $event)"
    >
        <slot />
    </Button>
</template>

<script setup>
import Button from 'primevue/button'

defineProps({
    label: String,
    icon: String,
    iconPos: {
        type: String,
        default: 'left',
    },
    severity: {
        type: String,
        default: 'primary',
    },
    type: {
        type: String,
        default: 'button',
    },
});

defineEmits(['click']);
</script>
