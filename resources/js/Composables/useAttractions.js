import {ref} from "vue";
import axios from "axios";
import {useToastControl} from "@/Composables/useToastControl.js";

export function useAttractions() {
    const {successManagement, errorManagement} = useToastControl();

    const attractions = ref([]);
    const attraction = ref(null);
    const error = ref({});

    /**
     * Fetches a list of attractions from the API with optional filters.
     *
     * @param {Object} filters - Optional filters to apply as query parameters.
     *
     * @returns {Promise<void>}
     *
     * @example
     * fetchAttractions({ city_id: 5 });
     *
     * On error, sets `error.value` with the error object.
     */
    const fetchAttractions = async (filters = {}) => {
        error.value = null;
        try {
            const response = await axios.get('/api/attractions', { params: filters });
            attractions.value = response.data.data || response.data;
        } catch (err) {
            error.value = err;
        }
    };

    /**
     * Fetches a single attraction by its ID from the API.
     *
     * @param {number|string} id - The ID of the attraction to fetch.
     *
     * @returns {Promise<void>}
     *
     * @example
     * fetchAttraction(10);
     *
     * On error, sets `error.value` with the error object.
     */
    const fetchAttraction = async (id) => {
        error.value = null;
        try {
            const response = await axios.get(`/api/attractions/${id}`);
            attraction.value = response.data.data || response.data;
        } catch (err) {
            error.value = err;
        }
    }

    /**
     * Sends a request to create a new attraction via the API.
     * Shows a success toast and executes an optional callback on success.
     *
     * @param {Object} attraction - The attraction data to save.
     * @param {Function|null} [callbackOnSuccess] - Optional callback executed after success notification.
     *
     * @returns {Promise<void>}
     *
     * @example
     * saveAttraction(newAttraction, () => {
     *   router.push('/attractions');
     * });
     *
     * On error, shows error toast notifications.
     */
    const saveAttraction = async (attraction, callbackOnSuccess) => {
        error.value = null;
        try {
            await axios.post('/api/attractions', attraction);
            successManagement(callbackOnSuccess);
        } catch (err) {
            errorManagement(err);
            error.value = err?.response?.data?.errors;
        }
    }

    /**
     * Sends a request to update an existing attraction via the API.
     * Shows a success toast and executes an optional callback on success.
     *
     * @param {Object} attraction - The attraction data to update. Must include `id`.
     * @param {Function|null} [callbackOnSuccess] - Optional callback executed after success notification.
     *
     * @returns {Promise<void>}
     *
     * @example
     * updateAttraction(updatedAttraction, () => {
     *   router.push('/attractions');
     * });
     *
     * On error, shows error toast notifications.
     */
    const updateAttraction = async (attraction, callbackOnSuccess) => {
        error.value = null;
        try {
            await axios.put(`/api/attractions/${attraction.id}`, attraction);
            successManagement(callbackOnSuccess);
        } catch (err) {
            errorManagement(err);
        }
    };

    return {
        attractions,
        attraction,
        error,
        fetchAttractions,
        fetchAttraction,
        saveAttraction,
        updateAttraction
    }
}
