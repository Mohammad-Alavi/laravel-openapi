<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { AppNotification, PaginatedResponse } from '@/types/models';

defineProps<{
    notifications: PaginatedResponse<AppNotification>;
}>();

function markAsRead(id: string) {
    router.patch(`/notifications/${id}/read`, {}, { preserveScroll: true });
}

function markAllAsRead() {
    router.post('/notifications/mark-all-read', {}, { preserveScroll: true });
}

function statusColor(status: string): string {
    return status === 'completed' ? 'success' : 'error';
}

function timeAgo(dateString: string): string {
    const seconds = Math.floor((Date.now() - new Date(dateString).getTime()) / 1000);
    if (seconds < 60) return 'just now';
    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes}m ago`;
    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours}h ago`;
    const days = Math.floor(hours / 24);
    return `${days}d ago`;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex justify-space-between align-center mb-6">
            <h1 class="text-h4">Notifications</h1>
            <v-btn
                v-if="notifications.data.some(n => !n.read_at)"
                variant="outlined"
                @click="markAllAsRead"
            >
                Mark all as read
            </v-btn>
        </div>

        <template v-if="notifications.data.length === 0">
            <v-card class="text-center pa-12" variant="outlined">
                <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-bell-off-outline</v-icon>
                <h2 class="text-h6 mb-2">No notifications</h2>
                <p class="text-body-2 text-grey">You're all caught up.</p>
            </v-card>
        </template>

        <template v-else>
            <v-list>
                <v-list-item
                    v-for="notification in notifications.data"
                    :key="notification.id"
                    :class="{ 'bg-blue-lighten-5': !notification.read_at }"
                    class="mb-1 rounded"
                    :href="`/projects/${notification.data.project_slug}`"
                >
                    <template #prepend>
                        <v-icon :color="statusColor(notification.data.status)">
                            {{ notification.data.status === 'completed' ? 'mdi-check-circle' : 'mdi-alert-circle' }}
                        </v-icon>
                    </template>
                    <v-list-item-title>
                        {{ notification.data.project_name }}
                        <v-chip
                            :color="statusColor(notification.data.status)"
                            size="x-small"
                            class="ml-2"
                        >
                            {{ notification.data.status }}
                        </v-chip>
                    </v-list-item-title>
                    <v-list-item-subtitle>
                        Commit {{ notification.data.commit_sha.substring(0, 7) }}
                        &middot; {{ timeAgo(notification.created_at) }}
                    </v-list-item-subtitle>
                    <template #append>
                        <v-btn
                            v-if="!notification.read_at"
                            icon="mdi-email-open"
                            variant="text"
                            size="small"
                            @click.prevent="markAsRead(notification.id)"
                        />
                    </template>
                </v-list-item>
            </v-list>

            <div v-if="notifications.last_page > 1" class="d-flex justify-center mt-6">
                <v-pagination
                    :model-value="notifications.current_page"
                    :length="notifications.last_page"
                    @update:model-value="(page: number) => router.visit(`/notifications?page=${page}`, { preserveState: true })"
                />
            </div>
        </template>
    </AuthenticatedLayout>
</template>
