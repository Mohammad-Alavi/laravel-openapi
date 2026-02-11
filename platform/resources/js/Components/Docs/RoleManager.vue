<script setup lang="ts">
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import type { DocRole, SpecTag, SpecPath } from '@/types/models';

const props = defineProps<{
    projectSlug: string;
    roles: DocRole[];
    specTags: SpecTag[];
    specPaths: SpecPath[];
}>();

const showCreateDialog = ref(false);
const editingRole = ref<DocRole | null>(null);

const form = useForm({
    name: '',
    scopes: [] as string[],
    is_default: false,
});

const editForm = useForm({
    name: '',
    scopes: [] as string[],
    is_default: false,
});

const hasSpecData = computed(() => props.specTags.length > 0 || props.specPaths.length > 0);

const scopeItems = computed(() => {
    const items: Array<{ title: string; value: string; props: { subtitle?: string; prependIcon?: string } }> = [];

    if (props.specTags.length > 0) {
        items.push({ title: 'Tags', value: '__header_tags__', props: { subtitle: 'Group endpoints by feature area' } });
        for (const tag of props.specTags) {
            items.push({
                title: tag.name,
                value: tag.name,
                props: {
                    subtitle: tag.description ?? undefined,
                    prependIcon: 'mdi-tag-outline',
                },
            });
        }
    }

    if (props.specPaths.length > 0) {
        items.push({ title: 'Paths', value: '__header_paths__', props: { subtitle: 'Individual API endpoints' } });
        for (const path of props.specPaths) {
            items.push({
                title: path.path,
                value: path.path,
                props: {
                    subtitle: path.methods.join(', '),
                    prependIcon: 'mdi-api',
                },
            });
        }
    }

    return items;
});

function isHeader(value: string): boolean {
    return value.startsWith('__header_');
}

function createRole() {
    form.post(`/projects/${props.projectSlug}/doc-roles`, {
        preserveScroll: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            form.reset();
        },
    });
}

function startEdit(role: DocRole) {
    editingRole.value = role;
    editForm.name = role.name;
    editForm.scopes = [...role.scopes];
    editForm.is_default = role.is_default;
}

function updateRole() {
    if (!editingRole.value) return;
    editForm.put(`/projects/${props.projectSlug}/doc-roles/${editingRole.value.id}`, {
        preserveScroll: true,
        onSuccess: () => { editingRole.value = null; },
    });
}

function deleteRole(role: DocRole) {
    router.delete(`/projects/${props.projectSlug}/doc-roles/${role.id}`, {
        preserveScroll: true,
    });
}

function scopeIcon(scope: string): string {
    return props.specPaths.some(p => p.path === scope) ? 'mdi-api' : 'mdi-tag-outline';
}
</script>

<template>
    <div>
        <div class="d-flex align-center mb-2">
            <h3 class="text-subtitle-1 font-weight-bold">Roles</h3>
            <v-spacer />
            <v-btn
                color="primary"
                size="small"
                prepend-icon="mdi-plus"
                @click="showCreateDialog = true"
            >
                Add Role
            </v-btn>
        </div>
        <p class="text-body-2 text-medium-emphasis mb-4">
            Roles define which API endpoints a user can see. Assign scopes (tags or paths) to control visibility per role.
        </p>

        <v-alert v-if="!hasSpecData" type="warning" variant="tonal" density="compact" class="mb-4">
            No spec data available. Build the project first to enable tag and path autocompletion for scopes.
            You can still create roles with manual scope entries.
        </v-alert>
        <v-alert v-else type="info" variant="tonal" density="compact" class="mb-4" icon="mdi-auto-fix">
            {{ specTags.length }} tag{{ specTags.length !== 1 ? 's' : '' }} and {{ specPaths.length }} path{{ specPaths.length !== 1 ? 's' : '' }} detected from your latest build.
        </v-alert>

        <v-table v-if="roles.length > 0" density="compact">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>
                        <v-tooltip text="Tags or paths this role grants access to" location="bottom">
                            <template #activator="{ props: tp }">
                                <span v-bind="tp" class="d-inline-flex align-center" style="cursor: help;">
                                    Scopes
                                    <v-icon size="x-small" class="ml-1">mdi-information-outline</v-icon>
                                </span>
                            </template>
                        </v-tooltip>
                    </th>
                    <th>
                        <v-tooltip text="Default roles are auto-assigned to new access links" location="bottom">
                            <template #activator="{ props: tp }">
                                <span v-bind="tp" class="d-inline-flex align-center" style="cursor: help;">
                                    Default
                                    <v-icon size="x-small" class="ml-1">mdi-information-outline</v-icon>
                                </span>
                            </template>
                        </v-tooltip>
                    </th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td class="font-weight-medium">{{ role.name }}</td>
                    <td>
                        <v-chip
                            v-for="scope in role.scopes"
                            :key="scope"
                            :prepend-icon="scopeIcon(scope)"
                            size="x-small"
                            class="mr-1 mb-1"
                        >
                            {{ scope }}
                        </v-chip>
                        <span v-if="role.scopes.length === 0" class="text-caption text-medium-emphasis">All endpoints</span>
                    </td>
                    <td>
                        <v-icon v-if="role.is_default" color="success" size="small">mdi-check</v-icon>
                    </td>
                    <td class="text-right">
                        <v-tooltip text="Edit role" location="bottom">
                            <template #activator="{ props: tp }">
                                <v-btn v-bind="tp" icon="mdi-pencil" size="x-small" variant="text" @click="startEdit(role)" />
                            </template>
                        </v-tooltip>
                        <v-tooltip text="Delete role" location="bottom">
                            <template #activator="{ props: tp }">
                                <v-btn v-bind="tp" icon="mdi-delete" size="x-small" variant="text" color="error" @click="deleteRole(role)" />
                            </template>
                        </v-tooltip>
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else type="info" variant="tonal" density="compact">
            No roles created yet. Add a role to start controlling who can see which endpoints.
        </v-alert>

        <!-- Create Dialog -->
        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title>Create Role</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.name"
                        label="Role Name"
                        hint="e.g., Partner, Internal, Admin"
                        persistent-hint
                        :error-messages="form.errors.name"
                        class="mb-2"
                    />
                    <v-combobox
                        v-model="form.scopes"
                        :items="scopeItems"
                        item-title="title"
                        item-value="value"
                        label="Scopes"
                        multiple
                        chips
                        closable-chips
                        :hint="hasSpecData ? 'Select from detected tags/paths, or type custom values (e.g., payments.*, /api/v2/*)' : 'No spec data yet. Type scope values manually (e.g., users, /api/v1/orders)'"
                        persistent-hint
                        :error-messages="form.errors.scopes"
                        class="mb-2"
                    >
                        <template #item="{ props: itemProps, item }">
                            <v-list-subheader v-if="isHeader(item.value as string)">
                                <v-icon size="small" class="mr-1">{{ item.value === '__header_tags__' ? 'mdi-tag-multiple' : 'mdi-api' }}</v-icon>
                                {{ item.title }}
                                <span class="text-caption text-medium-emphasis ml-2">{{ item.props?.subtitle }}</span>
                            </v-list-subheader>
                            <v-list-item v-else v-bind="itemProps">
                                <template v-if="item.props?.subtitle" #subtitle>
                                    {{ item.props.subtitle }}
                                </template>
                            </v-list-item>
                        </template>
                        <template #chip="{ props: chipProps, item }">
                            <v-chip v-bind="chipProps" :prepend-icon="scopeIcon(item.value as string)" size="small">
                                {{ item.title }}
                            </v-chip>
                        </template>
                    </v-combobox>
                    <v-checkbox
                        v-model="form.is_default"
                        label="Default role (auto-assigned to new access links)"
                        hide-details
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="showCreateDialog = false">Cancel</v-btn>
                    <v-btn color="primary" :loading="form.processing" @click="createRole">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!-- Edit Dialog -->
        <v-dialog :model-value="editingRole !== null" max-width="500" @update:model-value="editingRole = null">
            <v-card>
                <v-card-title>Edit Role</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="editForm.name"
                        label="Role Name"
                        :error-messages="editForm.errors.name"
                        class="mb-2"
                    />
                    <v-combobox
                        v-model="editForm.scopes"
                        :items="scopeItems"
                        item-title="title"
                        item-value="value"
                        label="Scopes"
                        multiple
                        chips
                        closable-chips
                        :hint="hasSpecData ? 'Select from detected tags/paths, or type custom values (e.g., payments.*, /api/v2/*)' : 'No spec data yet. Type scope values manually (e.g., users, /api/v1/orders)'"
                        persistent-hint
                        :error-messages="editForm.errors.scopes"
                        class="mb-2"
                    >
                        <template #item="{ props: itemProps, item }">
                            <v-list-subheader v-if="isHeader(item.value as string)">
                                <v-icon size="small" class="mr-1">{{ item.value === '__header_tags__' ? 'mdi-tag-multiple' : 'mdi-api' }}</v-icon>
                                {{ item.title }}
                                <span class="text-caption text-medium-emphasis ml-2">{{ item.props?.subtitle }}</span>
                            </v-list-subheader>
                            <v-list-item v-else v-bind="itemProps">
                                <template v-if="item.props?.subtitle" #subtitle>
                                    {{ item.props.subtitle }}
                                </template>
                            </v-list-item>
                        </template>
                        <template #chip="{ props: chipProps, item }">
                            <v-chip v-bind="chipProps" :prepend-icon="scopeIcon(item.value as string)" size="small">
                                {{ item.title }}
                            </v-chip>
                        </template>
                    </v-combobox>
                    <v-checkbox
                        v-model="editForm.is_default"
                        label="Default role"
                        hide-details
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="editingRole = null">Cancel</v-btn>
                    <v-btn color="primary" :loading="editForm.processing" @click="updateRole">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
