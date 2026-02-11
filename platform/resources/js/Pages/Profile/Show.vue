<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useTheme } from '@/composables/useTheme';
import type { User } from '@/types/models';

defineProps<{
    user: User;
}>();

const { isDark, toggle } = useTheme();

const syncing = ref(false);
const deleteDialog = ref(false);

function syncGitHub() {
    syncing.value = true;
    router.post('/profile/sync', {}, {
        onFinish: () => { syncing.value = false; },
    });
}

function deleteAccount() {
    router.delete('/profile');
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex align-center mb-6">
            <h1 class="text-h4">Profile & Settings</h1>
        </div>

        <!-- GitHub Account -->
        <v-card class="mb-6">
            <v-card-title>GitHub Account</v-card-title>
            <v-card-text>
                <div class="d-flex align-center mb-4">
                    <v-avatar v-if="user.github_avatar" size="64" class="mr-4">
                        <v-img :src="user.github_avatar" />
                    </v-avatar>
                    <v-avatar v-else size="64" color="primary" class="mr-4">
                        <v-icon size="32">mdi-account</v-icon>
                    </v-avatar>
                    <div>
                        <div class="text-h6">{{ user.name }}</div>
                        <div class="text-body-2 text-grey">{{ user.email }}</div>
                        <div class="text-caption text-grey">GitHub ID: {{ user.github_id }}</div>
                    </div>
                </div>
            </v-card-text>
            <v-card-actions>
                <v-btn
                    color="primary"
                    variant="outlined"
                    prepend-icon="mdi-sync"
                    :loading="syncing"
                    @click="syncGitHub"
                >
                    Re-sync from GitHub
                </v-btn>
            </v-card-actions>
        </v-card>

        <!-- Appearance -->
        <v-card class="mb-6">
            <v-card-title>Appearance</v-card-title>
            <v-card-text>
                <v-switch
                    :model-value="isDark"
                    label="Dark mode"
                    color="primary"
                    hide-details
                    @update:model-value="toggle"
                />
            </v-card-text>
        </v-card>

        <!-- Danger Zone -->
        <v-card variant="outlined" color="error">
            <v-card-title class="text-error">Danger Zone</v-card-title>
            <v-card-text>
                <p class="text-body-2 mb-4">
                    Deleting your account will permanently remove all your data, including all projects.
                    This action cannot be undone.
                </p>
                <v-btn
                    color="error"
                    variant="flat"
                    @click="deleteDialog = true"
                >
                    Delete Account
                </v-btn>
            </v-card-text>
        </v-card>

        <v-dialog v-model="deleteDialog" max-width="440">
            <v-card>
                <v-card-title>Delete Account</v-card-title>
                <v-card-text>
                    Are you sure you want to delete your account? All projects and data will be permanently removed.
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="deleteDialog = false">Cancel</v-btn>
                    <v-btn color="error" variant="flat" @click="deleteAccount">Delete Account</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>
