<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { index, store } from '@/routes/projects';

const form = useForm({
    name: '',
    description: '',
    github_repo_url: '',
    github_branch: 'main',
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
                    class="mb-4"
                />
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
