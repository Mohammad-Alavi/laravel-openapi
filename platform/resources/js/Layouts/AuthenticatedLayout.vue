<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types/models';
import { index as projectsIndex } from '@/routes/projects';
import { logout as logoutRoute } from '@/routes';

const page = usePage<PageProps>();

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
                        <v-list-item @click="logout">
                            <v-list-item-title>Sign Out</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </template>
        </v-app-bar>

        <v-main>
            <v-container>
                <v-alert
                    v-if="page.props.flash.success"
                    type="success"
                    closable
                    class="mb-4"
                >
                    {{ page.props.flash.success }}
                </v-alert>
                <v-alert
                    v-if="page.props.flash.error"
                    type="error"
                    closable
                    class="mb-4"
                >
                    {{ page.props.flash.error }}
                </v-alert>
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>
