<template>
    <Head :title="hotel.name"/>

    <p>Name: {{hotel.name}}</p>
</template>

<script setup>
import {Head} from '@inertiajs/vue3'
import {useI18n} from "vue-i18n";
import MainLayout from "@/Layouts/MainLayout.vue";

const {t} = useI18n();

defineProps({
    hotel: Object
})

defineOptions({
    layout: MainLayout,
});
</script>
