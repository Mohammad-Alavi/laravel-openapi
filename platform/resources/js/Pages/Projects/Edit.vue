<script setup lang="ts">
import { ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/models';
import { show, update } from '@/routes/projects';

const props = defineProps<{
    project: Project;
}>();

const form = useForm({
    name: props.project.name,
    description: props.project.description ?? '',
    github_repo_url: props.project.github_repo_url,
    github_branch: props.project.github_branch,
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
                    class="mb-4"
                />
                <v-text-field
                    v-model="form.github_repo_url"
                    label="GitHub Repository URL"
                    placeholder="https://github.com/owner/repo"
                    :error-messages="form.errors.github_repo_url"
                    hint="The full URL of the GitHub repository containing your Laravel project"
                    persistent-hint
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
                    hint="Builds will use the latest commit from this branch"
                    persistent-hint
                    class="mb-6"
                />
                <v-btn
                    type="submit"
                    color="primary"
                    :loading="form.processing"
                    :disabled="form.processing"
                >
                    Update Project
                </v-btn>
            </v-form>
        </v-card>
    </AuthenticatedLayout>
</template>
