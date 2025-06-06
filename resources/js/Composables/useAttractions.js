import {ref} from "vue";
import axios from "axios";

export function useAttractions() {
    const attractions = ref([]);
    const error = ref(null);

    /**
     * This function is used to fetch all the attractions.
     * @returns {Promise<void>}
     */
    const fetchAttractions = async () => {
        error.value = null;
        try {
            const response = await axios.get('/api/attractions');
            attractions.value = response.data.data || response.data;
        } catch (err) {
            error.value = err;
        }
    };

    return {
        attractions,
        error,
        fetchAttractions
    }
}
