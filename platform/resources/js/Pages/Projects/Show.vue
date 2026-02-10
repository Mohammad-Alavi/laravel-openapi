<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/models';
import { index, edit, destroy } from '@/routes/projects';

defineProps<{
    project: Project;
}>();

function deleteProject(id: number) {
    if (confirm('Are you sure you want to delete this project?')) {
        router.delete(destroy.url(id));
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex align-center mb-6">
            <v-btn
                icon="mdi-arrow-left"
                variant="text"
                @click="router.visit(index.url())"
            />
            <h1 class="text-h4 ml-2">{{ project.name }}</h1>
            <v-spacer />
            <v-btn
                color="primary"
                variant="outlined"
                class="mr-2"
                @click="router.visit(edit.url(project.id))"
            >
                Edit
            </v-btn>
            <v-btn
                color="error"
                variant="outlined"
                @click="deleteProject(project.id)"
            >
                Delete
            </v-btn>
        </div>

        <v-card class="pa-6">
            <v-list>
                <v-list-item>
                    <template #prepend>
                        <v-icon>mdi-tag</v-icon>
                    </template>
                    <v-list-item-title>Status</v-list-item-title>
                    <template #append>
                        <v-chip
                            :color="project.status === 'active' ? 'success' : project.status === 'building' ? 'warning' : 'default'"
                            size="small"
                        >
                            {{ project.status }}
                        </v-chip>
                    </template>
                </v-list-item>
                <v-list-item>
                    <template #prepend>
                        <v-icon>mdi-github</v-icon>
                    </template>
                    <v-list-item-title>Repository</v-list-item-title>
                    <v-list-item-subtitle>{{ project.github_repo_url }}</v-list-item-subtitle>
                </v-list-item>
                <v-list-item>
                    <template #prepend>
                        <v-icon>mdi-source-branch</v-icon>
                    </template>
                    <v-list-item-title>Branch</v-list-item-title>
                    <v-list-item-subtitle>{{ project.github_branch }}</v-list-item-subtitle>
                </v-list-item>
                <v-list-item v-if="project.description">
                    <template #prepend>
                        <v-icon>mdi-text</v-icon>
                    </template>
                    <v-list-item-title>Description</v-list-item-title>
                    <v-list-item-subtitle>{{ project.description }}</v-list-item-subtitle>
                </v-list-item>
                <v-list-item v-if="project.last_built_at">
                    <template #prepend>
                        <v-icon>mdi-clock</v-icon>
                    </template>
                    <v-list-item-title>Last Built</v-list-item-title>
                    <v-list-item-subtitle>{{ project.last_built_at }}</v-list-item-subtitle>
                </v-list-item>
            </v-list>
        </v-card>

        <v-card class="pa-6 mt-6" variant="outlined">
            <v-card-title class="text-h6">API Documentation</v-card-title>
            <v-card-text class="text-grey">
                OpenAPI documentation will appear here once the project has been built.
            </v-card-text>
        </v-card>
    </AuthenticatedLayout>
</template>
