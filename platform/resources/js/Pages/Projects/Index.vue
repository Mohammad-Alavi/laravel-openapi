<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/models';
import { create, show } from '@/routes/projects';

defineProps<{
    projects: Project[];
}>();
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex justify-space-between align-center mb-6">
            <h1 class="text-h4">Projects</h1>
            <v-btn
                color="primary"
                prepend-icon="mdi-plus"
                @click="router.visit(create.url())"
            >
                Create Project
            </v-btn>
        </div>

        <template v-if="projects.length === 0">
            <v-card class="text-center pa-12" variant="outlined">
                <v-icon size="64" color="grey-lighten-1" class="mb-4">mdi-folder-open-outline</v-icon>
                <h2 class="text-h6 mb-2">No projects yet</h2>
                <p class="text-body-2 text-grey mb-4">
                    Create your first project to start generating OpenAPI documentation.
                </p>
                <v-btn
                    color="primary"
                    @click="router.visit(create.url())"
                >
                    Create Your First Project
                </v-btn>
            </v-card>
        </template>

        <v-row v-else>
            <v-col v-for="project in projects" :key="project.id" cols="12" md="6" lg="4">
                <v-card
                    class="h-100"
                    @click="router.visit(show.url(project.id))"
                    hover
                >
                    <v-card-title>{{ project.name }}</v-card-title>
                    <v-card-subtitle>{{ project.github_repo_url }}</v-card-subtitle>
                    <v-card-text v-if="project.description">
                        {{ project.description }}
                    </v-card-text>
                    <v-card-actions>
                        <v-chip
                            :color="project.status === 'active' ? 'success' : project.status === 'building' ? 'warning' : 'default'"
                            size="small"
                        >
                            {{ project.status }}
                        </v-chip>
                        <v-spacer />
                        <v-chip size="small" variant="outlined">
                            {{ project.github_branch }}
                        </v-chip>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </AuthenticatedLayout>
</template>
