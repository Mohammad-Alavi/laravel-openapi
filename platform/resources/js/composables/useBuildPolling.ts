import { watch, onUnmounted, type Ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { echo } from '@/plugins/echo';
import type { Project } from '@/types/models';

export function useBuildPolling(project: Ref<Project>) {
    let channelName: string | null = null;
    let intervalId: ReturnType<typeof setInterval> | null = null;
    let catchUpId: ReturnType<typeof setTimeout> | null = null;

    function startPollingFallback() {
        if (intervalId !== null) return;
        intervalId = setInterval(() => {
            router.reload();
        }, 5000);
    }

    function stopPollingFallback() {
        if (intervalId !== null) {
            clearInterval(intervalId);
            intervalId = null;
        }
    }

    function subscribe() {
        if (channelName) return;
        channelName = `projects.${project.value.slug}`;

        echo.private(channelName)
            .listen('BuildStatusChanged', () => {
                router.reload();
            });

        // Catch events that fired before subscription was ready
        catchUpId = setTimeout(() => {
            router.reload();
            catchUpId = null;
        }, 2000);

        // Fall back to polling only if Reverb connection fails
        echo.connector.pusher.connection.bind('unavailable', startPollingFallback);
        echo.connector.pusher.connection.bind('failed', startPollingFallback);
        echo.connector.pusher.connection.bind('connected', stopPollingFallback);
    }

    function unsubscribe() {
        if (catchUpId !== null) {
            clearTimeout(catchUpId);
            catchUpId = null;
        }
        if (channelName) {
            echo.connector.pusher.connection.unbind('unavailable', startPollingFallback);
            echo.connector.pusher.connection.unbind('failed', startPollingFallback);
            echo.connector.pusher.connection.unbind('connected', stopPollingFallback);
            echo.leave(channelName);
            channelName = null;
        }
        stopPollingFallback();
    }

    watch(() => project.value.status, (status) => {
        if (status === 'building') {
            subscribe();
        } else {
            unsubscribe();
        }
    }, { immediate: true });

    onUnmounted(() => {
        unsubscribe();
    });
}
