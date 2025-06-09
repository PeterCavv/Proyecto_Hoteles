<template>
    <Head :title="t('messages.user_reviews.title')" />

    <Button
        icon="pi pi-file-pdf"
        class="p-button-sm p-button-outlined text-red-500 mb-4"
        :label="t('messages.user_reviews.generate_pdf')"
        :disabled="selectedReviews.length === 0"
        @click="openPdfDialog"
    />

    <Dialog
        v-model:visible="showDialog"
        modal
        :header="t('messages.pdf.preview')"
        style="width: 80vw; height: 90vh;"
    >
        <iframe
            v-if="pdfUrl"
            :src="pdfUrl"
            class="w-full h-[80vh]"
        ></iframe>
    </Dialog>

    <div class="p-4 space-y-4 lg:w-[800px]">
        <div class="flex flex-row justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">{{ t('messages.user_reviews.title') }}</h1>
            <Link
                :href="`/profile/${user.id}`"
                class="text-blue-500 hover:underline"
            >
                {{ t('messages.user_reviews.back') }}
            </Link>
        </div>

        <div v-if="reviews.length > 0" class="space-y-4">
            <div class="flex items-center mb-2">
                <BaseCheckbox
                    :modelValue="selectedReviews.length === reviews.length"
                    :binary="true"
                    @update:modelValue="toggleSelectAll"
                />
                <label class="ml-2 font-semibold">{{ t('messages.user_reviews.select_all') }}</label>
            </div>

            <div
                v-for="review in reviews"
                :key="review.id"
                class="p-4 bg-white rounded-xl shadow border flex items-start gap-3"
            >
                <BaseCheckbox
                    :modelValue="selectedReviews"
                    :value="review.id"
                    :binary="false"
                    @update:modelValue="val => selectedReviews = val"
                />
                <div>
                    <p class="text-gray-800">
                        <strong>{{ t('messages.user_reviews.review') }}:</strong>
                        {{ review.review }}
                    </p>
                    <p class="text-gray-600">
                        <strong>{{ t('messages.user_reviews.hotel') }}:</strong>
                        {{ review.hotel?.name ?? '-' }}
                    </p>
                    <p class="text-gray-500 text-sm">
                        <strong>{{ t('messages.user_reviews.date') }}:</strong>
                        {{ review.published_at }}
                    </p>
                </div>
            </div>
        </div>

        <p v-else class="text-gray-600">
            {{ t('messages.user_reviews.no_reviews') }}
        </p>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import {useI18n} from "vue-i18n";
import MainLayout from "@/Layouts/MainLayout.vue";
import {ref} from "vue";
import Dialog from "primevue/dialog";
import BaseCheckbox from '@/Components/Form/BasCheckbox.vue'

const {t} = useI18n();
const showDialog = ref(false)
const pdfUrl = ref('')
const selectedReviews = ref([])

defineOptions({
    layout: MainLayout
})
const props = defineProps({
    reviews: Array,
    user: Object
})

function toggleSelectAll() {
    if (selectedReviews.value.length === props.reviews.length) {
        selectedReviews.value = []
    } else {
        selectedReviews.value = props.reviews.map(r => r.id)
    }
}

function openPdfDialog() {
    if (selectedReviews.value.length === 0) return

    pdfUrl.value = route('reports.reviews.pdf', { user: props.user.id }) +
        '?ids=' + selectedReviews.value.join(',')
    showDialog.value = true
}
</script>
