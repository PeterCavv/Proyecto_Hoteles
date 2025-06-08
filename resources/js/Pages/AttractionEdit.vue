<template>
    <AttractionForm :model-value="attraction" @submit="handleSubmit"/>
</template>

<script setup>
import {useCity} from "@/Composables/useCity.js";
import {onMounted} from "vue";
import AttractionForm from "@/Components/AttractionForm.vue";

const {fetchCities} = useCity();

onMounted(() => {
    fetchCities();
})
</script>
