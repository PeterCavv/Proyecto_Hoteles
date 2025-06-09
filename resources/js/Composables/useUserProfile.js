import {useToastControl} from "@/Composables/useToastControl.js";

export function useUserProfile(){
    const { successManagement, errorManagement } = useToastControl();

    /**
     * Submits a form to update a user's profile using the PUT method.
     * Displays success or error toast notifications based on the outcome.
     *
     * @param {object} form - The form object (e.g., Inertia form) to submit.
     * @param {object} user - The user object, must include an `id` property.
     * @param {Function|null} [callbackOnSuccess] - Optional callback to execute after a successful update.
     *
     * @example
     * submitForm(profileForm, currentUser, () => {
     *   router.push('/profile');
     * });
     *
     * On error, logs errors to the console and shows error toast notifications.
     */
    const submitForm = (form, user, callbackOnSuccess) => {
        form.submit('put', route('profile.update', user.id), {
            onSuccess: () => {
                successManagement(callbackOnSuccess);
            },
            onError: (errors) => {
                errorManagement(errors);
                console.error('Errores:', errors);
            },
        });
    };

    /**
     * Sends a DELETE request to delete a user's account.
     * Displays error toast notifications if deletion fails.
     *
     * @param {object} form - The form object (e.g., Inertia form) to submit.
     * @param {object} user - The user object, must include an `id` property.
     *
     * @example
     * deleteAccount(profileForm, currentUser);
     *
     * On error, shows error toast notifications.
     */
    const deleteAccount = (form, user) => {
        form.delete(route('profile.destroy', user.id), {
            onError: (errors) => {
                errorManagement(errors);
            },
        });
    };

    /**
     * Submits a POST request to log out the current user.
     *
     * @param {object} form - The form object (e.g., Inertia form) to submit.
     *
     * @example
     * logOut(profileForm);
     */
    const logOut = (form) => {
        form.post(route('logout'));
    };

    return {
        submitForm,
        deleteAccount,
        logOut,
    };
}
