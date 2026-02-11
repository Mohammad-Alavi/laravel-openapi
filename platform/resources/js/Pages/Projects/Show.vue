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
import type { Project, Build, DocSetting, DocRole, DocVisibilityRule, DocAccessLink, SpecTag, SpecPath } from '@/types/models';
import { index, edit, destroy } from '@/routes/projects';

const props = defineProps<{
    project: Project;
    recentBuilds: Build[];
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
const expandedBuild = ref<string | null>(null);
useBuildPolling(toRef(props, 'project'));

const buildStatusConfig: Record<string, { color: string; icon: string; label: string }> = {
    pending: { color: 'default', icon: 'mdi-clock-outline', label: 'Pending' },
    building: { color: 'warning', icon: 'mdi-progress-wrench', label: 'Building' },
    completed: { color: 'success', icon: 'mdi-check-circle', label: 'Completed' },
    failed: { color: 'error', icon: 'mdi-alert-circle', label: 'Failed' },
};

function formatDuration(build: Build): string {
    if (!build.started_at || !build.completed_at) return '-';
    const seconds = Math.round((new Date(build.completed_at).getTime() - new Date(build.started_at).getTime()) / 1000);
    if (seconds < 60) return `${seconds}s`;
    return `${Math.floor(seconds / 60)}m ${seconds % 60}s`;
}

function formatTimeAgo(dateStr: string): string {
    const seconds = Math.round((Date.now() - new Date(dateStr).getTime()) / 1000);
    if (seconds < 60) return 'just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
    return `${Math.floor(seconds / 86400)}d ago`;
}

function toggleErrorLog(buildId: string) {
    expandedBuild.value = expandedBuild.value === buildId ? null : buildId;
}

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
                    v-if="project.status === 'building'"
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

        <!-- Build History -->
        <v-card class="pa-6 mt-6">
            <v-card-title class="text-h6 pa-0 mb-4">Build History</v-card-title>

            <v-alert
                v-if="recentBuilds.length === 0"
                type="info"
                variant="tonal"
                density="compact"
            >
                No builds yet. Click "Rebuild" to generate your API documentation.
            </v-alert>

            <v-table v-else density="compact">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Commit</th>
                        <th>Duration</th>
                        <th>When</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="build in recentBuilds" :key="build.id">
                        <tr>
                            <td>
                                <v-chip
                                    :color="buildStatusConfig[build.status].color"
                                    :prepend-icon="buildStatusConfig[build.status].icon"
                                    size="small"
                                >
                                    {{ buildStatusConfig[build.status].label }}
                                </v-chip>
                            </td>
                            <td>
                                <code class="text-caption">{{ build.commit_sha.slice(0, 7) }}</code>
                            </td>
                            <td class="text-caption">{{ formatDuration(build) }}</td>
                            <td class="text-caption">
                                {{ build.completed_at ? formatTimeAgo(build.completed_at) : (build.started_at ? 'in progress' : 'queued') }}
                            </td>
                            <td>
                                <v-btn
                                    v-if="build.error_log"
                                    variant="text"
                                    size="small"
                                    :icon="expandedBuild === build.id ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                                    @click="toggleErrorLog(build.id)"
                                />
                            </td>
                        </tr>
                        <tr v-if="expandedBuild === build.id && build.error_log">
                            <td colspan="5" class="pa-0">
                                <v-sheet color="grey-darken-4" class="pa-4">
                                    <pre class="text-caption" style="white-space: pre-wrap; word-break: break-word; max-height: 300px; overflow-y: auto;">{{ build.error_log }}</pre>
                                </v-sheet>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </v-table>
        </v-card>

        <!-- API Documentation Management -->
        <v-card class="pa-6 mt-6">
            <div class="d-flex align-center mb-4">
                <v-card-title class="text-h6 pa-0">API Documentation</v-card-title>
                <v-spacer />
                <v-btn
                    v-if="project.has_builds"
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
                v-if="!project.has_builds"
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
