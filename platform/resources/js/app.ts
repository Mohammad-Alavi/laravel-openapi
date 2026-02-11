import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createVuetify } from './plugins/vuetify';
import '../css/app.scss';

createInertiaApp({
    title: (title) => title ? `${title} - Laragen` : 'Laragen',
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const vuetify = createVuetify();

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(vuetify);

        app.mount(el);

        // Restore saved theme preference
        const savedTheme = localStorage.getItem('laragen-theme');
        if (savedTheme === 'dark' || savedTheme === 'light') {
            vuetify.theme.global.name.value = savedTheme;
        }
    },
    progress: {
        color: '#4B5563',
    },
});
