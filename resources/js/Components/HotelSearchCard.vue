<template>
    <div class="bg-white shadow rounded-xl p-6 space-y-6 lg:w-[800px]">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div>
                <BaseSelect
                    v-model="cityIdModel"
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
                    v-model="nameModel"
                    class="max-w-full"
                />
            </div>
        </div>

        <BaseButton
            icon="pi pi-search"
            :label="t('messages.index.search')"
            severity="info"
            @click="$emit('submit', { cityId: cityIdModel, name: nameModel })"
        />
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';
import { useCity } from '@/Composables/useCity.js';
import { onMounted } from 'vue';
import BaseInput from '@/Components/Form/BaseInput.vue';
import BaseSelect from '@/Components/Form/BaseSelect.vue';
import BaseButton from '@/Components/Form/BaseButton.vue';

const { t } = useI18n();
const { cities, fetchCities } = useCity();

const cityIdModel = defineModel('cityId');
const nameModel = defineModel('name');

onMounted(() => {
    fetchCities();
});
</script>
