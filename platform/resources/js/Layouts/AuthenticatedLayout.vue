<script setup lang="ts">
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types/models';
import { index as projectsIndex } from '@/routes/projects';
import { logout as logoutRoute } from '@/routes';

const page = usePage<PageProps>();

const loading = ref(false);

router.on('start', () => { loading.value = true; });
router.on('finish', () => { loading.value = false; });

const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

watch(() => page.props.flash, (flash) => {
    if (flash.success) {
        snackbarMessage.value = flash.success;
        snackbarColor.value = 'success';
        snackbar.value = true;
    } else if (flash.error) {
        snackbarMessage.value = flash.error;
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
}, { immediate: true });

function logout() {
    router.post(logoutRoute.url());
}
</script>

<template>
    <v-app>
        <v-app-bar color="primary" density="comfortable">
            <v-app-bar-title>
                <a :href="projectsIndex.url()" class="text-white text-decoration-none font-weight-bold">
                    Laragen
                </a>
            </v-app-bar-title>

            <template #append>
                <v-btn
                    icon
                    variant="text"
                    :href="'/notifications'"
                >
                    <v-badge
                        v-if="page.props.unreadNotificationsCount > 0"
                        :content="page.props.unreadNotificationsCount"
                        color="error"
                    >
                        <v-icon>mdi-bell</v-icon>
                    </v-badge>
                    <v-icon v-else>mdi-bell-outline</v-icon>
                </v-btn>
                <v-menu>
                    <template #activator="{ props }">
                        <v-btn v-bind="props" variant="text">
                            <v-avatar v-if="page.props.auth.user.github_avatar" size="32" class="mr-2">
                                <v-img :src="page.props.auth.user.github_avatar" />
                            </v-avatar>
                            {{ page.props.auth.user.name }}
                            <v-icon end>mdi-chevron-down</v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item :href="'/profile'">
                            <v-list-item-title>Profile</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="logout">
                            <v-list-item-title>Sign Out</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
        </v-app-bar>

        <v-progress-linear
            :active="loading"
            indeterminate
            color="secondary"
            height="3"
        />

        <v-main>
            <v-container>
                <slot />
            </v-container>
        </v-main>

        <v-snackbar
            v-model="snackbar"
            :color="snackbarColor"
            location="top right"
            :timeout="4000"
        >
            {{ snackbarMessage }}
            <template #actions>
                <v-btn variant="text" @click="snackbar = false">Close</v-btn>
            </template>
        </v-snackbar>
    </v-app>
</template>
