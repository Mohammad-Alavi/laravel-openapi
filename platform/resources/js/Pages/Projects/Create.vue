<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { index, store } from '@/routes/projects';

const form = useForm({
    name: '',
    description: '',
    github_repo_url: '',
    github_branch: 'main',
});

const repoValidation = ref<{ checking: boolean; valid: boolean | null; error: string | null; defaultBranch: string | null }>({
    checking: false,
    valid: null,
    error: null,
    defaultBranch: null,
});

let debounceTimer: ReturnType<typeof setTimeout>;

watch(() => form.github_repo_url, (url) => {
    clearTimeout(debounceTimer);
    repoValidation.value = { checking: false, valid: null, error: null, defaultBranch: null };

    if (!url || !url.match(/^https:\/\/github\.com\/[^/]+\/[^/]+$/)) {
        return;
    }

    repoValidation.value.checking = true;
    debounceTimer = setTimeout(async () => {
        try {
            const response = await fetch('/github/validate-repo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-XSRF-TOKEN': decodeURIComponent(document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1] ?? ''),
                },
                body: JSON.stringify({
                    github_repo_url: url,
                    branch: form.github_branch || undefined,
                }),
            });
            const data = await response.json();
            repoValidation.value = {
                checking: false,
                valid: data.valid,
                error: data.error ?? null,
                defaultBranch: data.default_branch ?? null,
            };
        } catch {
            repoValidation.value = { checking: false, valid: null, error: null, defaultBranch: null };
        }
    }, 500);
});

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
                <v-text-field
                    v-model="form.github_repo_url"
                    label="GitHub Repository URL"
                    placeholder="https://github.com/user/repo"
                    :error-messages="form.errors.github_repo_url"
                    required
                    class="mb-1"
                />
                <div class="mb-4">
                    <v-progress-linear v-if="repoValidation.checking" indeterminate height="2" />
                    <v-alert v-else-if="repoValidation.valid === true" type="success" density="compact" variant="tonal" class="mt-1">
                        Repository verified{{ repoValidation.defaultBranch ? ` (default branch: ${repoValidation.defaultBranch})` : '' }}
                    </v-alert>
                    <v-alert v-else-if="repoValidation.valid === false" type="warning" density="compact" variant="tonal" class="mt-1">
                        {{ repoValidation.error }}
                    </v-alert>
                </div>
                <v-text-field
                    v-model="form.github_branch"
                    label="Branch"
                    :error-messages="form.errors.github_branch"
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
