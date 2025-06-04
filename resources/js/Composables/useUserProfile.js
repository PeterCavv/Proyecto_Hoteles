import {useToast} from "primevue/usetoast";
import {useI18n} from "vue-i18n";

export function useUserProfile(){
    const toast = useToast();
    const {t} = useI18n();

    /**
     * This function is used to submit the user profile form.
     * @param form
     * @param user
     * @param callbackOnSuccess
     */
    const submitForm = (form, user, callbackOnSuccess) => {
        form.submit('put', route('profile.update', user.id), {
            onSuccess: () => {
                 toast.add({
                    severity: 'success',
                    summary: t('messages.state.saved'),
                    detail: t('messages.state.saved_successfully'),
                    life: 3000,
                });
                if (callbackOnSuccess) callbackOnSuccess();
            },
            onError: (errors) => {
                errorManagement(errors);
                console.error('Errores:', errors);
            },
        });
    };

    /**
     * This function is used to delete the user account.
     * @param form
     * @param user
     */
    const deleteAccount = (form, user) => {
        form.delete(route('profile.destroy', user.id), {
            onError: (errors) => {
                errorManagement(errors);
            },
        });
    };

    /**
     * This function is used to log out the user.
     * @param form
     */
    const logOut = (form) => {
        form.post(route('logout'));
    };

    /**
     * This function handles error management by displaying error messages.
     * @param errors
     */
    const errorManagement = (errors) => {
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
            } else {
                toast.add({
                    severity: 'error',
                    summary: t('messages.state.error'),
                    detail: errorMessages,
                    life: 4000,
                });
            }
        });
    }

    return {
        submitForm,
        deleteAccount,
        logOut,
    };
}
