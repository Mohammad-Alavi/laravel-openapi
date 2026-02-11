<script setup lang="ts">
import { ref, toRef } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useProjectStatus } from '@/composables/useProjectStatus';
import { useBuildPolling } from '@/composables/useBuildPolling';
import type { Project } from '@/types/models';
import { index, edit, destroy } from '@/routes/projects';

const props = defineProps<{
    project: Project;
}>();

const deleteDialog = ref(false);
const { polling } = useBuildPolling(toRef(props, 'project'));

function deleteProject() {
    router.delete(destroy.url(props.project.id));
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
                @click="deleteDialog = true"
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
                            :color="useProjectStatus(project.status).color"
                            :prepend-icon="useProjectStatus(project.status).icon"
                            size="small"
                        >
                            {{ useProjectStatus(project.status).label }}
                        </v-chip>
                    </template>
                </v-list-item>
                <v-progress-linear
                    v-if="polling"
                    indeterminate
                    color="warning"
                    height="3"
                />
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

        <v-dialog v-model="deleteDialog" max-width="440">
            <v-card>
                <v-card-title>Delete Project</v-card-title>
                <v-card-text>
                    Are you sure you want to delete <strong>{{ project.name }}</strong>? This action cannot be undone.
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="deleteDialog = false">Cancel</v-btn>
                    <v-btn color="error" variant="flat" @click="deleteProject">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AuthenticatedLayout>
</template>
