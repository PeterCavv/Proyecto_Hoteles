<template>
    <Head :title="t('messages.attractions.form.edit_title')" />

    <div class="min-h-screen bg-blue-100 py-10">
        <div class="bg-white rounded-2xl shadow-lg max-w-3xl mx-auto px-6 py-8">
            <div class="mb-4">
                <Link
                    href="/attractions"
                    class="underline text-green-600 hover:text-green-800"
                >
                    {{ t('messages.common.back') }}
                </Link>
            </div>

            <h1 class="text-2xl font-bold mb-6 text-gray-800">
                {{ t('messages.attractions.form.edit_title') }}
            </h1>

            <AttractionForm :model-value="attraction" @submit="handleSubmit" />
        </div>
    </div>
</template>

<script setup>
import AttractionForm from "@/Components/AttractionForm.vue";
import {Head, Link, router} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";
import 'primeicons/primeicons.css'
import {useAttractions} from "@/Composables/useAttractions.js";
import {watchEffect} from "vue";

const { updateAttraction, fetchAttraction, attraction } = useAttractions();

const {t} = useI18n();
const handleSubmit = (data) => {
    updateAttraction(data);
    router.push('/attractions');
};

const props = defineProps({
    id: Number,
})

watchEffect(() => {
    if (props.id) {
        console.log('fetchAttraction triggered with id:', props.id)
        fetchAttraction(props.id)
    }
})
</script>
