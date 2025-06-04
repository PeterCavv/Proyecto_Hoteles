<template>
    <Head :title="t('messages.main_menu.attractions')"/>
    <div class="min-h-screen bg-blue-100 py-10">
        <div class="bg-white rounded-2xl shadow-lg max-w-5xl mx-auto px-4 lg:px-8 py-8 space-y-6">
            <div class="flex flex-row justify-between items-center mb-4">
                <Link
                    href="/"
                    class="underline text-green-600 hover:text-green-800"
                >
                    Volver
                </Link>
                <h2 class="text-2xl font-bold text-gray-800 float-end">HotelFinder</h2>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Atracciones Turísticas</h1>

            <div class="flex items-center gap-4">
                <InputText
                    v-model="search"
                    placeholder="Buscar atracción..."
                    class="w-full md:w-1/2"
                />
                <Button label="Buscar" icon="pi pi-search" />
            </div>

            <DataTable :value="attractions" class="w-full">
                <Column field="name" header="Nombre"></Column>
                <Column field="city.name" header="Ubicación"></Column>
                <Column field="type" header="Tipo"></Column>
            </DataTable>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import {useI18n} from "vue-i18n";
import {Head} from "@inertiajs/vue3";
import {onMounted, ref} from 'vue'
import  InputText from 'primevue/inputtext'
import  Button  from 'primevue/button'
import  DataTable  from 'primevue/datatable'
import  Column  from 'primevue/column'
import {useAttractions} from "@/Composables/useAttractions.js";

const { attractions, error, fetchAttractions } = useAttractions();

const search = ref('')
const {t} = useI18n();

onMounted(() =>{
    fetchAttractions();
})
</script>

