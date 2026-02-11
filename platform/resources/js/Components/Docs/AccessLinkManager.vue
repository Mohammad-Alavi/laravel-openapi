<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import type { DocAccessLink, DocRole, PageProps } from '@/types/models';

const props = defineProps<{
    projectSlug: string;
    links: DocAccessLink[];
    roles: DocRole[];
}>();

const page = usePage<PageProps>();
const showCreateDialog = ref(false);
const createdToken = ref<string | null>(null);
const copied = ref(false);

const form = useForm({
    doc_role_id: null as string | null,
    name: '',
    expires_at: '',
});

const plainToken = computed(() => page.props.flash as Record<string, string>);

function createLink() {
    form.post(`/projects/${props.projectSlug}/doc-links`, {
        preserveScroll: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            form.reset();
            const flash = page.props.flash as Record<string, string>;
            if (flash.plain_token) {
                createdToken.value = flash.plain_token;
            }
        },
    });
}

function revokeLink(link: DocAccessLink) {
    router.delete(`/projects/${props.projectSlug}/doc-links/${link.id}`, {
        preserveScroll: true,
    });
}

function getDocUrl(token: string): string {
    return `${window.location.origin}/docs/${props.projectSlug}?token=${token}`;
}

async function copyToClipboard(text: string) {
    await navigator.clipboard.writeText(text);
    copied.value = true;
    setTimeout(() => { copied.value = false; }, 2000);
}

function roleName(roleId: string): string {
    return props.roles.find(r => r.id === roleId)?.name ?? 'Unknown';
}
</script>

<template>
    <div>
        <div class="d-flex align-center mb-4">
            <h3 class="text-subtitle-1 font-weight-bold">Access Links</h3>
            <v-spacer />
            <v-btn
                color="primary"
                size="small"
                prepend-icon="mdi-plus"
                :disabled="roles.length === 0"
                @click="showCreateDialog = true"
            >
                Create Link
            </v-btn>
        </div>

        <v-alert v-if="roles.length === 0" type="warning" variant="tonal" density="compact" class="mb-4">
            Create a role first before generating access links.
        </v-alert>

        <!-- Token Display (shown once after creation) -->
        <v-alert
            v-if="createdToken"
            type="warning"
            variant="tonal"
            class="mb-4"
            closable
            @click:close="createdToken = null"
        >
            <div class="font-weight-bold mb-1">Access link created! Copy the token now - it won't be shown again.</div>
            <div class="d-flex align-center">
                <code class="text-body-2 mr-2" style="word-break: break-all">{{ createdToken }}</code>
                <v-btn
                    :icon="copied ? 'mdi-check' : 'mdi-content-copy'"
                    size="x-small"
                    variant="text"
                    @click="copyToClipboard(getDocUrl(createdToken!))"
                />
            </div>
            <div class="text-caption mt-1">
                Full URL: <code>{{ getDocUrl(createdToken!) }}</code>
            </div>
        </v-alert>

        <v-table v-if="links.length > 0" density="compact">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Expires</th>
                    <th>Last Used</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="link in links" :key="link.id">
                    <td>{{ link.name }}</td>
                    <td>{{ roleName(link.doc_role_id) }}</td>
                    <td>{{ link.expires_at ?? 'Never' }}</td>
                    <td>{{ link.last_used_at ?? 'Never' }}</td>
                    <td>
                        <v-chip
                            :color="link.is_expired ? 'error' : 'success'"
                            size="x-small"
                        >
                            {{ link.is_expired ? 'Expired' : 'Active' }}
                        </v-chip>
                    </td>
                    <td class="text-right">
                        <v-btn
                            icon="mdi-delete"
                            size="x-small"
                            variant="text"
                            color="error"
                            @click="revokeLink(link)"
                        />
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else-if="roles.length > 0" type="info" variant="tonal" density="compact">
            No access links created yet.
        </v-alert>

        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title>Create Access Link</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.name"
                        label="Link Name"
                        hint="e.g., Partner API Access"
                        :error-messages="form.errors.name"
                    />
                    <v-select
                        v-model="form.doc_role_id"
                        :items="roles"
                        item-title="name"
                        item-value="id"
                        label="Role"
                        :error-messages="form.errors.doc_role_id"
                    />
                    <v-text-field
                        v-model="form.expires_at"
                        label="Expires At (optional)"
                        type="datetime-local"
                        :error-messages="form.errors.expires_at"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="showCreateDialog = false">Cancel</v-btn>
                    <v-btn color="primary" :loading="form.processing" @click="createLink">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
