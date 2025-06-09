import { ref } from 'vue'
import axios from 'axios'

export function useReservations() {
    const reservations = ref([])
    const loading = ref(false)
    const error = ref(null)

    async function fetchReservations() {
        loading.value = true
        error.value = null
        try {
            console.log("hols")
            const response = await axios.get('/api/reservations')
            reservations.value = response.data
        } catch (err) {
            error.value = err
        } finally {
            loading.value = false
        }
    }

    return {
        reservations,
        loading,
        error,
        fetchReservations,
    }
}
