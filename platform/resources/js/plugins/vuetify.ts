import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import { createVuetify as createVuetifyInstance } from 'vuetify';
import { aliases, mdi } from 'vuetify/iconsets/mdi';

export function createVuetify() {
    return createVuetifyInstance({
        icons: {
            defaultSet: 'mdi',
            aliases,
            sets: { mdi },
        },
        theme: {
            defaultTheme: 'light',
            themes: {
                light: {
                    colors: {
                        primary: '#1867C0',
                        secondary: '#5CBBF6',
                    },
                },
                dark: {
                    dark: true,
                    colors: {
                        primary: '#2196F3',
                        secondary: '#5CBBF6',
                    },
                },
            },
        },
    });
}
