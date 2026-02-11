import { ref, watch, onUnmounted, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { Project } from '@/types/models';

export function useBuildPolling(project: Ref<Project>) {
    const polling = ref(false);
    let intervalId: ReturnType<typeof setInterval> | null = null;

    function startPolling() {
        if (intervalId !== null) return;
        polling.value = true;

        intervalId = setInterval(async () => {
            try {
                const response = await fetch(`/projects/${project.value.id}/status`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) return;

                const data = await response.json();

                if (data.status !== 'building') {
                    stopPolling();
                    router.reload();
                }
            } catch {
                // Silently ignore fetch errors during polling
            }
        }, 5000);
    }

    function stopPolling() {
        if (intervalId !== null) {
            clearInterval(intervalId);
            intervalId = null;
        }
        polling.value = false;
    }

    watch(() => project.value.status, (status) => {
        if (status === 'building') {
            startPolling();
        } else {
            stopPolling();
        }
    }, { immediate: true });

    onUnmounted(() => {
        stopPolling();
    });

    return { polling };
}
