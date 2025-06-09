<template>
    <Head :title="t('messages.hotels.head')" />

    <div class="min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">
                {{ t('messages.index.search_title') }}
            </h1>

            <HotelSearchCard
                v-model:cityId="cityId"
                v-model:name="name"
                @submit="submitSearch"
                class="mb-8"
            />

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 lg:w-[800px] gap-6 cursor-pointer">
                <div
                    v-for="hotel in hotels"
                    :key="hotel.id"
                    class="bg-white shadow-md rounded-2xl p-4 transition hover:shadow-lg"
                >
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        {{ hotel.name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ hotel.city?.name || t('messages.hotels.no_city') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import HotelSearchCard from '@/Components/HotelSearchCard.vue'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({
    layout: MainLayout
})

defineProps({
    hotels: Array,
})

const { t } = useI18n()

const cityId = ref('')
const name = ref('')

const submitSearch = () => {
    router.get('/hotels/search', {
        city_id: cityId.value,
        name: name.value,
    })
}
</script>


