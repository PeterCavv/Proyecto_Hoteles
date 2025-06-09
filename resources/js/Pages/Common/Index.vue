<template>
    <Head title="Buscar hoteles" />

    <div class="py-10 space-y-8">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ t('messages.index.search_title') }}
        </h1>

        <div class="bg-white shadow rounded-xl p-6 space-y-6 lg:w-[800px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <div>
                    <BaseSelect
                        v-model="cityId"
                        :label="t('messages.index.destination')"
                        :options="cities"
                        optionLabel="name"
                        optionValue="id"
                        :placeholder="t('messages.index.select_destination')"
                        class="max-w-full"
                    />
                </div>

                <div>
                    <BaseInput
                        :label="t('messages.index.hotel_name')"
                        :placeholder="t('messages.index.select_hotel')"
                        v-model="name"
                        class="max-w-full"
                    />
                </div>
            </div>

            <BaseButton
                icon="pi pi-search"
                :label="t('messages.index.search')"
                severity="info"
                @click="submitSearch"
            />
        </div>
    </div>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, router } from '@inertiajs/vue3'
import {onMounted, ref} from 'vue'
import { useI18n } from 'vue-i18n'
import {useCity} from "@/Composables/useCity.js";
import BaseInput from "@/Components/Form/BaseInput.vue"
import BaseSelect from "@/Components/Form/BaseSelect.vue";
import BaseButton from "@/Components/Form/BaseButton.vue";

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
