<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head } from '@inertiajs/vue3'
import {useI18n} from "vue-i18n";

const {t} = useI18n();

defineProps({
    features: Array
})

defineOptions({
    layout: MainLayout,
})
</script>

<template>
    <Head :title="t('messages.hotels.features')" />

</template>
