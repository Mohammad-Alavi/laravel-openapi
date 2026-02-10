<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/types/models';
import { show, update } from '@/routes/projects';

const props = defineProps<{
    project: Project;
}>();

const statusOptions = [
    { title: 'Active', value: 'active' },
    { title: 'Paused', value: 'paused' },
    { title: 'Building', value: 'building' },
];

const form = useForm({
    name: props.project.name,
    description: props.project.description ?? '',
    github_repo_url: props.project.github_repo_url,
    github_branch: props.project.github_branch,
    status: props.project.status,
});

function submit() {
    form.put(update.url(props.project.id));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="d-flex align-center mb-6">
            <v-btn
                icon="mdi-arrow-left"
                variant="text"
                @click="router.visit(show.url(project.id))"
            />
            <h1 class="text-h4 ml-2">Edit Project</h1>
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
                    class="mb-4"
                />
                <v-text-field
                    v-model="form.github_branch"
                    label="Branch"
                    :error-messages="form.errors.github_branch"
                    class="mb-4"
                />
                <v-select
                    v-model="form.status"
                    label="Status"
                    :items="statusOptions"
                    :error-messages="form.errors.status"
                    class="mb-4"
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
