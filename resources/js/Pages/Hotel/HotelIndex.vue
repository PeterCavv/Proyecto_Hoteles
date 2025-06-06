<template>
    <Head :title="t('messages.hotels.head')"/>

    <div v-for="hotel in hotels">
        <p>{{hotel.name}}</p>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/vue3'
import {useI18n} from "vue-i18n";
import MainLayout from "@/Layouts/MainLayout.vue";

const {t} = useI18n();

defineProps({
    hotels: Array,
    filters: Object
})

defineOptions({
    layout: MainLayout,
});
</script>
