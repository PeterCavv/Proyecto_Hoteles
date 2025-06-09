import { ref } from 'vue';
import axios from 'axios';

export function useCity() {
    const cities = ref([]);
    const error = ref(null);

    /**
     * This function is used to fetch all the cities.
     * @returns {Promise<void>}
     */
    const fetchCities = async () => {
        error.value = null;
        try {
            const response = await axios.get('/api/cities');
            cities.value = response.data.data || response.data;
        } catch (err) {
            error.value = err;
        }
    };

    return {
        cities,
        error,
        fetchCities,
    };
}
