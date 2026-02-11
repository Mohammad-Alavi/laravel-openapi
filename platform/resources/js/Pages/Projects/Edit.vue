<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/models';
import { show, update } from '@/routes/projects';

interface GitHubRepo {
    full_name: string;
    name: string;
    description: string | null;
    url: string;
    default_branch: string;
    private: boolean;
}

const props = defineProps<{
    project: Project;
}>();

function repoFullName(url: string): string {
    const match = url.match(/github\.com\/(.+)$/);
    return match ? match[1] : '';
}

function repoName(url: string): string {
    const full = repoFullName(url);
    return full.split('/').pop() ?? '';
}

const form = useForm({
    name: props.project.name,
    description: props.project.description ?? '',
    github_repo_url: props.project.github_repo_url,
    github_branch: props.project.github_branch,
});

const repoSearch = ref('');
const repoItems = ref<GitHubRepo[]>([]);
const repoLoading = ref(false);
const selectedRepo = ref<GitHubRepo | null>({
    full_name: repoFullName(props.project.github_repo_url),
    name: repoName(props.project.github_repo_url),
    description: props.project.description,
    url: props.project.github_repo_url,
    default_branch: props.project.github_branch,
    private: false,
});

const branches = ref<string[]>([]);
const branchLoading = ref(false);

let debounceTimer: ReturnType<typeof setTimeout>;

watch(repoSearch, (query) => {
    clearTimeout(debounceTimer);
    if (!query || query.length < 2) {
        repoItems.value = [];
        return;
    }

    repoLoading.value = true;
    debounceTimer = setTimeout(async () => {
        try {
            const response = await fetch(`/github/repos?q=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (response.ok) {
                repoItems.value = await response.json();
            }
        } catch {
            repoItems.value = [];
        } finally {
            repoLoading.value = false;
        }
    }, 300);
});

function onRepoSelected(repo: GitHubRepo | null) {
    if (!repo) {
        selectedRepo.value = null;
        form.github_repo_url = '';
        form.github_branch = '';
        branches.value = [];
        return;
    }

    selectedRepo.value = repo;
    form.github_repo_url = repo.url;
    form.github_branch = repo.default_branch;

    loadBranches(repo.full_name);
}

async function loadBranches(repoFullName: string) {
    branchLoading.value = true;
    branches.value = [];

    try {
        const response = await fetch(`/github/branches?repo=${encodeURIComponent(repoFullName)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        if (response.ok) {
            branches.value = await response.json();
        }
    } catch {
        branches.value = [];
    } finally {
        branchLoading.value = false;
    }
}

onMounted(() => {
    const fullName = repoFullName(props.project.github_repo_url);
    if (fullName) {
        loadBranches(fullName);
    }
});

function submit() {
    form.put(update.url(props.project.slug));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex align-center mb-6">
            <v-btn
                icon="mdi-arrow-left"
                variant="text"
                @click="router.visit(show.url(project.slug))"
            />
            <h1 class="text-h4 ml-2">Edit Project</h1>
        </div>

        <v-card max-width="600" class="pa-6">
            <v-form @submit.prevent="submit">
                <v-autocomplete
                    v-model="selectedRepo"
                    v-model:search="repoSearch"
                    :items="repoItems"
                    :loading="repoLoading"
                    item-title="full_name"
                    item-value="full_name"
                    label="GitHub Repository"
                    placeholder="Search your repositories..."
                    hint="Search by name across all repositories you have access to"
                    persistent-hint
                    :error-messages="form.errors.github_repo_url"
                    no-filter
                    return-object
                    clearable
                    class="mb-4"
                    @update:model-value="onRepoSelected"
                >
                    <template #item="{ props: itemProps, item }">
                        <v-list-item v-bind="itemProps">
                            <template #prepend>
                                <v-icon
                                    :icon="item.raw.private ? 'mdi-lock' : 'mdi-source-repository'"
                                    size="small"
                                    class="mr-2"
                                />
                            </template>
                            <template #subtitle>
                                {{ item.raw.description || 'No description' }}
                            </template>
                        </v-list-item>
                    </template>
                    <template #no-data>
                        <v-list-item v-if="repoSearch && repoSearch.length >= 2">
                            <v-list-item-title>No repositories found</v-list-item-title>
                        </v-list-item>
                        <v-list-item v-else>
                            <v-list-item-title class="text-medium-emphasis">Type at least 2 characters to search</v-list-item-title>
                        </v-list-item>
                    </template>
                </v-autocomplete>

                <v-autocomplete
                    v-model="form.github_branch"
                    :items="branches"
                    :loading="branchLoading"
                    :disabled="!selectedRepo"
                    label="Branch"
                    hint="Builds will use the latest commit from this branch"
                    persistent-hint
                    :error-messages="form.errors.github_branch"
                    class="mb-4"
                >
                    <template #no-data>
                        <v-list-item>
                            <v-list-item-title class="text-medium-emphasis">
                                {{ selectedRepo ? 'No branches found' : 'Select a repository first' }}
                            </v-list-item-title>
                        </v-list-item>
                    </template>
                </v-autocomplete>

                <v-text-field
                    v-model="form.name"
                    label="Project Name"
                    :error-messages="form.errors.name"
                    hint="A display name for this project in Laragen"
                    persistent-hint
                    required
                    class="mb-4"
                />
                <v-textarea
                    v-model="form.description"
                    label="Description"
                    :error-messages="form.errors.description"
                    hint="Optional notes about this project"
                    rows="3"
                    class="mb-6"
                />
                <v-btn
                    type="submit"
                    color="primary"
                    :loading="form.processing"
                    :disabled="form.processing"
                    size="large"
                >
                    Update Project
                </v-btn>
            </v-form>
        </v-card>
    </AuthenticatedLayout>
</template>
