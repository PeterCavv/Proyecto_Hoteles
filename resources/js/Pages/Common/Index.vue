<template>
    <Head title="Buscar hoteles" />

    <div class="py-10 space-y-8">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ t('messages.index.search_title') }}
        </h1>

        <div class="bg-white shadow rounded-xl p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ t('messages.index.destination') }}
                    </label>
                    <Select v-model="destination"
                            :options="cities"
                            filter optionLabel="destination"
                            :placeholder="t('messages.index.select_destination')"
                            class="w-full md:w-56"
                    >
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
                                <div>{{ slotProps.value.name }}</div>
                            </div>
                            <span v-else>
                                {{ slotProps.placeholder }}
                            </span>
                        </template>
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                        <template #emptyfilter>
                            {{ t('messages.index.no_content') }}
                        </template>
                    </Select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ t('messages.index.check_in') }}
                    </label>
                    <input
                        v-model="checkIn"
                        type="date"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ t('messages.index.check_out') }}
                    </label>
                    <input
                        v-model="checkOut"
                        type="date"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    />
                </div>
            </div>

            <div class="text-right">
                <Button
                    @click="submitSearch"
                    severity="info"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition"
                >
                    {{ t('messages.index.search') }}
                </Button>
            </div>
        </div>
    </div>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, router } from '@inertiajs/vue3'
import {onMounted, ref} from 'vue'
import { useI18n } from 'vue-i18n'
import {useCity} from "@/Composables/useCity.js";
import Select from "primevue/select";

const { t } = useI18n()

const { cities, fetchCities } = useCity();

defineOptions({
    layout: MainLayout,
})

const destination = ref('')
const checkIn = ref('')
const checkOut = ref('')

const submitSearch = () => {
    router.get('/hotels/search', {
        destination: destination.value,
        check_in: checkIn.value,
        check_out: checkOut.value,
    })
}

onMounted(() => {
    fetchCities();
});
</script>
