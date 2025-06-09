import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";
export function useToastControl() {
    const toast = useToast();
    const {t} = useI18n();

    /**
     * Displays a success toast message and optionally executes a callback function.
     *
     * @param {Function|null} callbackOnSuccess - Optional. A callback to be executed after the toast is shown.
     *
     * @example
     * successManagement(() => {
     *     router.push('/attractions');
     * });
     *
     * The toast uses translations:
     * - t('messages.state.saved')              → e.g., "Saved"
     * - t('messages.state.saved_successfully') → e.g., "The data was saved successfully."
     *
     * This function is intended for use in Vue apps with PrimeVue and Vue I18n.
     */
    const successManagement = (callbackOnSuccess) => {
        toast.add({
            severity: 'success',
            summary: t('messages.state.saved'),
            detail: t('messages.state.saved_successfully'),
            life: 3000,
        });
        if (callbackOnSuccess) callbackOnSuccess();
    }

    /**
     * Displays error toast messages based on the provided error input.
     * Supports Laravel validation errors (HTTP 422), error objects, strings, and fallback for unexpected errors.
     *
     * @param {object|string} error - The error data to display. Can be:
     *   - An Axios error response object with validation errors (status 422).
     *   - An object containing error messages or arrays of messages.
     *   - A simple string error message.
     *
     * @example
     * // Using with Axios validation error
     * try {
     *   await apiCall();
     * } catch (err) {
     *   errorManagement(err);
     * }
     *
     * // Using with custom error object
     * errorManagement({
     *   name: ['The name field is required.'],
     *   email: ['The email must be valid.']
     * });
     *
     * // Using with simple error string
     * errorManagement('Something went wrong.');
     *
     * Translation keys used:
     * - t('messages.state.error')           // e.g., "Error"
     * - t('messages.state.unexpected_error') // e.g., "An unexpected error occurred. Please try again."
     */
    const errorManagement = (error) => {
        if (error?.response?.status === 422 && error.response.data?.errors) {
            const errors = error.response.data.errors;
            Object.values(errors).forEach((errorMessages) => {
                if (Array.isArray(errorMessages)) {
                    errorMessages.forEach((msg) => {
                        toast.add({
                            severity: 'error',
                            summary: t('messages.state.error'),
                            detail: msg,
                            life: 4000,
                        });
                    });
                }
            });
        } else if (typeof error === 'object') {
            Object.values(error).forEach((msgOrList) => {
                if (Array.isArray(msgOrList)) {
                    msgOrList.forEach((msg) => {
                        toast.add({
                            severity: 'error',
                            summary: t('messages.state.error'),
                            detail: msg,
                            life: 4000,
                        });
                    });
                } else {
                    toast.add({
                        severity: 'error',
                        summary: t('messages.state.error'),
                        detail: msgOrList,
                        life: 4000,
                    });
                }
            });
        } else if (typeof error === 'string') {
            toast.add({
                severity: 'error',
                summary: t('messages.state.error'),
                detail: error,
                life: 4000,
            });
        } else {
            toast.add({
                severity: 'error',
                summary: t('messages.state.error'),
                detail: t('messages.state.unexpected_error'),
                life: 4000,
            });
        }
    };


    return {
        successManagement,
        errorManagement
    }

}
