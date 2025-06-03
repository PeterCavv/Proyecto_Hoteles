import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import PrimeVue from 'primevue/config';
import { Ziggy } from './ziggy';
import { ZiggyVue } from 'ziggy-js';
import Aura from '@primeuix/themes/aura';
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
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'light',
                        cssLayer: false,
                    },
                },
            })
            .use(ZiggyVue, Ziggy)
            .component('Button', Button)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
