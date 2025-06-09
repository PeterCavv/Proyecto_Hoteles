import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import setupI18n from './i18n'
import ToastService from 'primevue/toastservice';
import PrimeVue from 'primevue/config';
import { Ziggy } from './ziggy';
import { ZiggyVue } from 'ziggy-js';
import Material from '@primeuix/themes/material';
import Button from "primevue/button"

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const i18n = setupI18n(props.initialPage.props.translations, props.initialPage.props.locale);

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(PrimeVue, {
                theme: {
                    preset: Material,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'light',
                        cssLayer: false,
                    },
                },
            })
            .use(ZiggyVue, Ziggy)
            .use(ToastService)
            .component('Button', Button)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
