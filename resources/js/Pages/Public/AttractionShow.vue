<template>
    <Head :title="attraction.name"/>
    <div class="min-h-screen bg-blue-100 py-10">
        <div class="bg-white rounded-2xl shadow-lg max-w-3xl mx-auto px-6 py-8">
            <div class="mb-4">
                <Link href="/attractions" class="underline text-green-600 hover:text-green-800">
                    {{ t('messages.common.back') }}
                </Link>
            </div>

            <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ attraction.name }}</h1>

            <div class="mb-4">
                <strong>{{ t('messages.attractions.form.type') }}: </strong>
                <span>{{ typeTemplate(attraction) }}</span>
            </div>

            <div class="mb-4">
                <strong>{{ t('messages.attractions.form.city') }}: </strong>
                <span>{{ attraction.city?.name || '-' }}</span>
            </div>

            <div>
                <strong>{{ t('messages.attractions.form.description') }}:</strong>
                <p>{{ attraction.description || '-' }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {useI18n} from "vue-i18n";
import {useAttractions} from "@/Composables/useAttractions.js";
import {onMounted, watchEffect} from "vue";
import {Head, Link} from "@inertiajs/vue3";

const {t} = useI18n();
const { attraction, fetchAttraction } = useAttractions();

const props = defineProps({
    id: Number,
})

const typeTemplate = (attraction) => {
    return  t(`messages.attractions.form.${attraction.type}`);
}

watchEffect(() => {
    if (props.id) {
        console.log('fetchAttraction triggered with id:', props.id)
        fetchAttraction(props.id)
    }
})
</script>
