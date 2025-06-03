<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

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
</script>

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
                    <input
                        v-model="destination"
                        type="text"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ej. Buenos Aires"
                    />
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
                <button
                    @click="submitSearch"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition"
                >
                    {{ t('messages.index.search') }}
                </button>
            </div>
        </div>
    </div>
</template>
