<template>
    <Checkbox
        v-bind="$attrs"
        :modelValue="modelValue"
        :value="value"
        :binary="binary"
        @update:modelValue="updateValue"
    />
</template>

<script setup>
import Checkbox from 'primevue/checkbox'

const props = defineProps({
    modelValue: [Boolean, Array],
    value: [String, Number, Boolean],
    binary: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

function updateValue(val) {
    emit('update:modelValue', val)
}
</script>
