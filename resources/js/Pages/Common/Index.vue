<template>
    <Head title="Buscar hoteles" />

    <div class="py-10 space-y-8">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ t('messages.index.search_title') }}
        </h1>

        <HotelSearchCard v-model:cityId="cityId" v-model:name="name" @submit="submitSearch" />
    </div>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, router } from '@inertiajs/vue3'
import {onMounted, ref} from 'vue'
import { useI18n } from 'vue-i18n'
import {useCity} from "@/Composables/useCity.js";
import HotelSearchCard from "@/Components/HotelSearchCard.vue";

const { t } = useI18n()

const { cities, fetchCities } = useCity();

defineOptions({
    layout: MainLayout,
})

const cityId = ref('')
const name = ref('')

const submitSearch = () => {
    console.log(cityId.value)
    router.get('/hotels/search', {
        city_id: cityId.value,
        name: name.value
    })
}

onMounted(() => {
    fetchCities();
});
</script>
