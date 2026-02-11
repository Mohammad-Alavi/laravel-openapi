<script setup lang="ts">
import { ref, toRef } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VisibilityToggle from '@/Components/Docs/VisibilityToggle.vue';
import RoleManager from '@/Components/Docs/RoleManager.vue';
import EndpointRulesManager from '@/Components/Docs/EndpointRulesManager.vue';
import AccessLinkManager from '@/Components/Docs/AccessLinkManager.vue';
import { useProjectStatus } from '@/composables/useProjectStatus';
import { useBuildPolling } from '@/composables/useBuildPolling';
import type { Project, DocSetting, DocRole, DocVisibilityRule, DocAccessLink, SpecTag, SpecPath } from '@/types/models';
import { index, edit, destroy } from '@/routes/projects';

const props = defineProps<{
    project: Project;
    docSetting: DocSetting | null;
    docRoles: DocRole[];
    docRules: DocVisibilityRule[];
    docLinks: DocAccessLink[];
    specTags: SpecTag[];
    specPaths: SpecPath[];
}>();

const deleteDialog = ref(false);
const rebuildLoading = ref(false);
const docsTab = ref('settings');
const { polling } = useBuildPolling(toRef(props, 'project'));

function deleteProject() {
    router.delete(destroy.url(props.project.slug));
}

function rebuild() {
    rebuildLoading.value = true;
    router.post(`/projects/${props.project.slug}/rebuild`, {}, {
        preserveScroll: true,
        onFinish: () => { rebuildLoading.value = false; },
    });
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
                prepend-icon="mdi-refresh"
                variant="outlined"
                class="mr-2"
                :disabled="project.status === 'building'"
                :loading="rebuildLoading"
                @click="rebuild"
            >
                Rebuild
            </v-btn>
            <v-btn
                color="primary"
                variant="outlined"
                class="mr-2"
                @click="router.visit(edit.url(project.slug))"
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

        <!-- API Documentation Management -->
        <v-card class="pa-6 mt-6">
            <div class="d-flex align-center mb-4">
                <v-card-title class="text-h6 pa-0">API Documentation</v-card-title>
                <v-spacer />
                <v-btn
                    v-if="project.latest_build_id"
                    color="primary"
                    variant="outlined"
                    size="small"
                    prepend-icon="mdi-open-in-new"
                    :href="`/docs/${project.slug}`"
                    target="_blank"
                >
                    View Docs
                </v-btn>
            </div>

            <v-alert
                v-if="!project.latest_build_id"
                type="info"
                variant="tonal"
                density="compact"
                class="mb-4"
            >
                Build the project first to enable documentation management.
            </v-alert>

            <template v-else>
                <v-tabs v-model="docsTab" class="mb-4">
                    <v-tab value="settings">Settings</v-tab>
                    <v-tab value="roles">Roles</v-tab>
                    <v-tab value="rules">Endpoint Rules</v-tab>
                    <v-tab value="links">Access Links</v-tab>
                </v-tabs>

                <v-tabs-window v-model="docsTab">
                    <v-tabs-window-item value="settings">
                        <VisibilityToggle
                            :project-slug="project.slug"
                            :setting="docSetting"
                        />
                    </v-tabs-window-item>

                    <v-tabs-window-item value="roles">
                        <RoleManager
                            :project-slug="project.slug"
                            :roles="docRoles"
                            :spec-tags="specTags"
                            :spec-paths="specPaths"
                        />
                    </v-tabs-window-item>

                    <v-tabs-window-item value="rules">
                        <EndpointRulesManager
                            :project-slug="project.slug"
                            :rules="docRules"
                            :spec-tags="specTags"
                            :spec-paths="specPaths"
                        />
                    </v-tabs-window-item>

                    <v-tabs-window-item value="links">
                        <AccessLinkManager
                            :project-slug="project.slug"
                            :links="docLinks"
                            :roles="docRoles"
                        />
                    </v-tabs-window-item>
                </v-tabs-window>
            </template>
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
