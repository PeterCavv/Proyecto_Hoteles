import {ref} from "vue";
import axios from "axios";

export function useAttractions() {
    const attractions = ref([]);
    const attraction = ref(null);
    const error = ref(null);

    /**
     * Asynchronously fetches a list of attractions from the server based on the provided filters.
     *
     * This function sends a GET request to the `/api/attractions` endpoint.
     * The `filters` parameter is used to specify query parameters for filtering the attractions.
     * The fetched data is stored in the `attractions` variable.
     * If an error occurs during the API call, the error is captured in the `error` variable.
     *
     * @param {Object} [filters={}] Optional filters to apply as query parameters when retrieving attractions.
     * @returns {Promise<void>} A promise that resolves once the data is fetched and the relevant variables are updated.
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
     * Asynchronously fetches attraction details from the server.
     *
     * This function makes a GET request to the endpoint `/api/cities/{attraction}`
     * to retrieve details about the specified attraction. If successful, it
     * returns the data from the response. If an error occurs during the request,
     * the error is captured in the error variable.
     *
     * @returns {Promise<Object>} A Promise that resolves to the attraction data if the request is successful.
     * @throws Will capture and store any request errors in the `error` variable.
     * @param id
     */
    const fetchAttraction = async (id) => {
        console.log("hola");
        error.value = null;
        try {
            const response = await axios.get(`/api/attractions/${id}`);
            attraction.value = response.data.data || response.data;
        } catch (err) {
            error.value = err;
        }
    }

    /**
     * Saves an attraction to the server by making a POST request to the '/api/attractions' endpoint.
     *
     * @async
     * @function
     * @param {Object} attraction - The attraction object to be saved.
     * @returns {Promise<Object>} A promise that resolves to the response data from the server.
     * @throws Throws an error if the request fails.
     */
    const saveAttraction = async (attraction) => {
        error.value = null;
        try {
            const response = await axios.post('/api/attractions', attraction);
            return response.data;
        } catch (err) {
            error.value = err;
        }
    }

    const updateAttraction = async (attraction) => {
        error.value = null;
        try {
            const response = await axios.put(`/api/attractions/${attraction.id}`, attraction);
            return response.data;
        } catch (err) {
            error.value = err;
        }
    }

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
