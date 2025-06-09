<template>
    <div>
        <div v-if="loading">Cargando reservas...</div>
        <div v-if="error">Error: {{ error.message }}</div>
        <ul v-if="!loading && !error">
            <li v-for="reservation in reservations" :key="reservation.id">
                Reserva ID: {{ reservation.id }} - Hotel: {{ reservation.hotel.name }}
            </li>
        </ul>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useReservations } from '@/Composables/useReservations.js'

const { reservations, loading, error, fetchReservations } = useReservations()

onMounted(() => {
    fetchReservations()
})
</script>
