<template>
    <Head :title="t('messages.main_menu.attractions')"/>
    <div class="min-h-screen bg-blue-100 py-10">
        <div class="bg-white rounded-2xl shadow-lg max-w-5xl mx-auto px-4 lg:px-8 py-8 space-y-6">
            <div class="flex flex-row justify-between items-center mb-4">
                <Link
                    href="/"
                    class="underline text-green-600 hover:text-green-800"
                >
                    {{ t('messages.common.back') }}
                </Link>
                <h2 class="text-2xl font-bold text-gray-800 float-end">{{ t('messages.app_name') }}</h2>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">{{ t('messages.main_menu.attractions') }}</h1>

            <Link
                href="/attractions/create"
                class="p-button p-component flex items-center gap-2"
            >
                <span class="pi pi-plus" />
            </Link>

            <div class="flex flex-col md:flex-row items-center gap-4">
                <InputText
                    v-model="search.name"
                    :placeholder="t('messages.attractions.search.name_placeholder')"
                    class="w-full md:w-1/3"
                />
                <InputText
                    v-model="search.city"
                    :placeholder="t('messages.attractions.search.city_placeholder')"
                    class="w-full md:w-1/3"
                />
                <Dropdown
                    v-model="search.type"
                    :options="typeOptions"
                    optionLabel="label"
                    optionValue="value"
                    :placeholder="t('messages.attractions.search.type_placeholder')"
                    class="w-full md:w-1/3"
                    showClear
                />
                <Button
                    :label="t('messages.common.search')"
                    icon="pi pi-search"
                    @click="searchAttractions"/>
            </div>

            <DataTable :value="attractions" class="w-full" paginator :rows="5">
                <Column field="name" :header="t('messages.attractions.table.name')"/>
                <Column field="city.name" :header="t('messages.attractions.table.city')"/>
                <Column
                    :field="typeTemplate"
                    :header="t('messages.attractions.table.type')"
                />
                <Column
                    :header="t('messages.attractions.table.actions')"
                >
                    <template #body="{ data }">
                        <Link :href="`/attractions/${data.id}`" class="pi pi-eye mr-2 text-blue-600 hover:text-blue-800"></Link>
                        <span class="pi pi-file-edit mr-2 text-green-600 hover:text-green-800"></span>
                        <span class="pi pi-trash text-red-600 hover:text-red-800"></span>
                    </template>
                </Column>
            </DataTable>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";
import {Head} from "@inertiajs/vue3";
import {onMounted, reactive, ref} from 'vue'
import  InputText from 'primevue/inputtext'
import  Button  from 'primevue/button'
import  DataTable  from 'primevue/datatable'
import  Column  from 'primevue/column'
import  Dropdown  from 'primevue/dropdown'
import {useAttractions} from "@/Composables/useAttractions.js";
import 'primeicons/primeicons.css'

const { attractions, error, fetchAttractions } = useAttractions();

const {t} = useI18n();

const search = reactive({
    name: '',
    city: '',
    type: null,
});

const typeTemplate = (row) => {
    return  t(`messages.attractions.form.${row.type}`);
}

const searchAttractions = () => {
    fetchAttractions({
        name: search.name,
        city: search.city,
        type: search.type,
    });
};

const typeOptions = [
    { label: t('messages.attractions.form.free'), value: 'free' },
    { label: t('messages.attractions.form.pay'), value: 'pay' },
];

onMounted(() =>{
    fetchAttractions();
})
</script>

