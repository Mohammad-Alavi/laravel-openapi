<script setup lang="ts">
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useProjectStatus } from '@/composables/useProjectStatus';
import type { Project, ProjectStats, PaginatedResponse } from '@/types/models';
import { create, show, index } from '@/routes/projects';

const props = defineProps<{
    projects: PaginatedResponse<Project>;
    stats: ProjectStats;
    filters: { search?: string; status?: string };
}>();

const search = ref(props.filters.search ?? '');
const statusFilter = ref(props.filters.status ?? '');

const statusOptions = [
    { title: 'All statuses', value: '' },
    { title: 'Active', value: 'active' },
    { title: 'Paused', value: 'paused' },
    { title: 'Building', value: 'building' },
];

let debounceTimer: ReturnType<typeof setTimeout>;

watch(search, (value) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        applyFilters({ search: value });
    }, 300);
});

watch(statusFilter, (value) => {
    applyFilters({ status: value });
});

function applyFilters(override: Record<string, string> = {}) {
    const params: Record<string, string> = {
        search: search.value,
        status: statusFilter.value,
        ...override,
    };

    // Remove empty values
    Object.keys(params).forEach((key) => {
        if (!params[key]) delete params[key];
    });

    router.visit(index.url(), {
        data: params,
        preserveState: true,
        replace: true,
    });
}

function goToPage(page: number) {
    const params: Record<string, string> = { page: String(page) };
    if (search.value) params.search = search.value;
    if (statusFilter.value) params.status = statusFilter.value;

    router.visit(index.url(), {
        data: params,
        preserveState: true,
    });
}
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

        <!-- Stats cards -->
        <v-row class="mb-6">
            <v-col cols="6" md="3">
                <v-card variant="tonal">
                    <v-card-text class="d-flex align-center">
                        <v-icon color="primary" class="mr-3">mdi-folder-multiple</v-icon>
                        <div>
                            <div class="text-h5">{{ stats.total }}</div>
                            <div class="text-caption">Total</div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="6" md="3">
                <v-card variant="tonal" color="success">
                    <v-card-text class="d-flex align-center">
                        <v-icon class="mr-3">mdi-check-circle</v-icon>
                        <div>
                            <div class="text-h5">{{ stats.active }}</div>
                            <div class="text-caption">Active</div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="6" md="3">
                <v-card variant="tonal">
                    <v-card-text class="d-flex align-center">
                        <v-icon class="mr-3">mdi-pause-circle</v-icon>
                        <div>
                            <div class="text-h5">{{ stats.paused }}</div>
                            <div class="text-caption">Paused</div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="6" md="3">
                <v-card variant="tonal" color="warning">
                    <v-card-text class="d-flex align-center">
                        <v-icon class="mr-3">mdi-progress-wrench</v-icon>
                        <div>
                            <div class="text-h5">{{ stats.building }}</div>
                            <div class="text-caption">Building</div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Search and filter -->
        <v-row class="mb-4">
            <v-col cols="12" md="8">
                <v-text-field
                    v-model="search"
                    prepend-inner-icon="mdi-magnify"
                    label="Search projects..."
                    hide-details
                    clearable
                    density="compact"
                />
            </v-col>
            <v-col cols="12" md="4">
                <v-select
                    v-model="statusFilter"
                    :items="statusOptions"
                    label="Status"
                    hide-details
                    density="compact"
                />
            </v-col>
        </v-row>

        <template v-if="projects.data.length === 0 && !search && !statusFilter">
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

        <template v-else-if="projects.data.length === 0">
            <v-card class="text-center pa-8" variant="outlined">
                <v-icon size="48" color="grey-lighten-1" class="mb-3">mdi-magnify</v-icon>
                <h2 class="text-h6 mb-2">No matching projects</h2>
                <p class="text-body-2 text-grey">Try adjusting your search or filter criteria.</p>
            </v-card>
        </template>

        <template v-else>
            <v-row>
                <v-col v-for="project in projects.data" :key="project.id" cols="12" md="6" lg="4">
                    <v-card
                        class="h-100"
                        @click="router.visit(show.url(project.slug))"
                        hover
                    >
                        <v-card-title>{{ project.name }}</v-card-title>
                        <v-card-subtitle>{{ project.github_repo_url }}</v-card-subtitle>
                        <v-card-text v-if="project.description">
                            {{ project.description }}
                        </v-card-text>
                        <v-card-actions>
                            <v-chip
                                :color="useProjectStatus(project.status).color"
                                :prepend-icon="useProjectStatus(project.status).icon"
                                size="small"
                            >
                                {{ useProjectStatus(project.status).label }}
                            </v-chip>
                            <v-spacer />
                            <v-chip size="small" variant="outlined">
                                {{ project.github_branch }}
                            </v-chip>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <div v-if="projects.last_page > 1" class="d-flex justify-center mt-6">
                <v-pagination
                    :model-value="projects.current_page"
                    :length="projects.last_page"
                    @update:model-value="goToPage"
                />
            </div>
        </template>
    </AuthenticatedLayout>
</template>
