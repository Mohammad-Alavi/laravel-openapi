import { computed } from 'vue';
import { useTheme as useVuetifyTheme } from 'vuetify';

const STORAGE_KEY = 'laragen-theme';

export function useTheme() {
    const theme = useVuetifyTheme();

    const isDark = computed(() => theme.global.current.value.dark);

    function toggle() {
        const next = isDark.value ? 'light' : 'dark';
        theme.global.name.value = next;
        localStorage.setItem(STORAGE_KEY, next);
    }

    function initialize() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved === 'dark' || saved === 'light') {
            theme.global.name.value = saved;
        }
    }

    return { isDark, toggle, initialize };
}
