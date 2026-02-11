<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { index, store } from '@/routes/projects';

interface GitHubRepo {
    full_name: string;
    name: string;
    description: string | null;
    url: string;
    default_branch: string;
    private: boolean;
}

const form = useForm({
    name: '',
    description: '',
    github_repo_url: '',
    github_branch: '',
});

const repoSearch = ref('');
const repoItems = ref<GitHubRepo[]>([]);
const repoLoading = ref(false);
const selectedRepo = ref<GitHubRepo | null>(null);

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

    if (!form.name) {
        form.name = repo.name;
    }
    if (!form.description && repo.description) {
        form.description = repo.description;
    }

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

function submit() {
    form.post(store.url());
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
            <h1 class="text-h4 ml-2">Create Project</h1>
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
                            <v-list-item-title>Type at least 2 characters to search</v-list-item-title>
                        </v-list-item>
                    </template>
                </v-autocomplete>

                <v-autocomplete
                    v-model="form.github_branch"
                    :items="branches"
                    :loading="branchLoading"
                    :disabled="!selectedRepo"
                    label="Branch"
                    :error-messages="form.errors.github_branch"
                    class="mb-4"
                >
                    <template #no-data>
                        <v-list-item>
                            <v-list-item-title>
                                {{ selectedRepo ? 'No branches found' : 'Select a repository first' }}
                            </v-list-item-title>
                        </v-list-item>
                    </template>
                </v-autocomplete>

                <v-text-field
                    v-model="form.name"
                    label="Project Name"
                    :error-messages="form.errors.name"
                    required
                    class="mb-4"
                />
                <v-textarea
                    v-model="form.description"
                    label="Description"
                    :error-messages="form.errors.description"
                    rows="3"
                    class="mb-4"
                />
                <v-btn
                    type="submit"
                    color="primary"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Create Project
                </v-btn>
            </v-form>
        </v-card>
    </AuthenticatedLayout>
</template>
