<script setup lang="ts">
import { ref } from 'vue';
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

function formatDate(dateStr: string | null): string {
    if (!dateStr) return 'Never';
    const date = new Date(dateStr);
    return date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
}
</script>

<template>
    <div>
        <div class="d-flex align-center mb-2">
            <h3 class="text-subtitle-1 font-weight-bold">Access Links</h3>
            <v-spacer />
            <v-tooltip v-if="roles.length === 0" text="Create a role first before generating access links" location="bottom">
                <template #activator="{ props: tp }">
                    <div v-bind="tp">
                        <v-btn
                            color="primary"
                            size="small"
                            prepend-icon="mdi-plus"
                            disabled
                        >
                            Create Link
                        </v-btn>
                    </div>
                </template>
            </v-tooltip>
            <v-btn
                v-else
                color="primary"
                size="small"
                prepend-icon="mdi-plus"
                @click="showCreateDialog = true"
            >
                Create Link
            </v-btn>
        </div>
        <p class="text-body-2 text-medium-emphasis mb-4">
            Generate shareable links that give role-based access to your API documentation. Each link is tied to a role.
        </p>

        <!-- Token Display (shown once after creation) -->
        <v-alert
            v-if="createdToken"
            type="warning"
            variant="tonal"
            class="mb-4"
            closable
            @click:close="createdToken = null"
        >
            <div class="font-weight-bold mb-1">Access link created! Copy the URL now — it won't be shown again.</div>
            <div class="d-flex align-center">
                <code class="text-body-2 mr-2" style="word-break: break-all">{{ getDocUrl(createdToken!) }}</code>
                <v-tooltip :text="copied ? 'Copied!' : 'Copy URL to clipboard'" location="bottom">
                    <template #activator="{ props: tp }">
                        <v-btn
                            v-bind="tp"
                            :icon="copied ? 'mdi-check' : 'mdi-content-copy'"
                            size="x-small"
                            variant="text"
                            @click="copyToClipboard(getDocUrl(createdToken!))"
                        />
                    </template>
                </v-tooltip>
            </div>
        </v-alert>

        <v-table v-if="links.length > 0" density="compact">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>
                        <v-tooltip text="The role determines which endpoints are visible via this link" location="bottom">
                            <template #activator="{ props: tp }">
                                <span v-bind="tp" class="d-inline-flex align-center" style="cursor: help;">
                                    Role
                                    <v-icon size="x-small" class="ml-1">mdi-information-outline</v-icon>
                                </span>
                            </template>
                        </v-tooltip>
                    </th>
                    <th>Expires</th>
                    <th>Last Used</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="link in links" :key="link.id">
                    <td class="font-weight-medium">{{ link.name }}</td>
                    <td>{{ roleName(link.doc_role_id) }}</td>
                    <td>{{ formatDate(link.expires_at) }}</td>
                    <td>{{ formatDate(link.last_used_at) }}</td>
                    <td>
                        <v-chip
                            :color="link.is_expired ? 'error' : 'success'"
                            size="x-small"
                        >
                            {{ link.is_expired ? 'Expired' : 'Active' }}
                        </v-chip>
                    </td>
                    <td class="text-right">
                        <v-tooltip text="Revoke this access link" location="bottom">
                            <template #activator="{ props: tp }">
                                <v-btn
                                    v-bind="tp"
                                    icon="mdi-delete"
                                    size="x-small"
                                    variant="text"
                                    color="error"
                                    @click="revokeLink(link)"
                                />
                            </template>
                        </v-tooltip>
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else-if="roles.length > 0" type="info" variant="tonal" density="compact">
            No access links created yet. Create one to share your documentation with specific users.
        </v-alert>

        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title>Create Access Link</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.name"
                        label="Link Name"
                        hint="A label to identify who this link is for (e.g., Partner API Access)"
                        persistent-hint
                        :error-messages="form.errors.name"
                        class="mb-2"
                    />
                    <v-select
                        v-model="form.doc_role_id"
                        :items="roles"
                        item-title="name"
                        item-value="id"
                        label="Role"
                        hint="Determines which endpoints are visible through this link"
                        persistent-hint
                        :error-messages="form.errors.doc_role_id"
                        class="mb-2"
                    />
                    <v-text-field
                        v-model="form.expires_at"
                        label="Expires At"
                        type="datetime-local"
                        hint="Leave empty for a link that never expires"
                        persistent-hint
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
