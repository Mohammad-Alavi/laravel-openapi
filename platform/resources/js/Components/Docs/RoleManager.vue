<script setup lang="ts">
import { ref } from 'vue';
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

const scopeOptions = [
    ...props.specTags.map(t => t.name),
    ...props.specPaths.map(p => p.path),
];

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
</script>

<template>
    <div>
        <div class="d-flex align-center mb-4">
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

        <v-table v-if="roles.length > 0" density="compact">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Scopes</th>
                    <th>Default</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>{{ role.name }}</td>
                    <td>
                        <v-chip
                            v-for="scope in role.scopes"
                            :key="scope"
                            size="x-small"
                            class="mr-1"
                        >
                            {{ scope }}
                        </v-chip>
                    </td>
                    <td>
                        <v-icon v-if="role.is_default" color="success" size="small">mdi-check</v-icon>
                    </td>
                    <td class="text-right">
                        <v-btn icon="mdi-pencil" size="x-small" variant="text" @click="startEdit(role)" />
                        <v-btn icon="mdi-delete" size="x-small" variant="text" color="error" @click="deleteRole(role)" />
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else type="info" variant="tonal" density="compact">
            No roles created yet. Create a role to control access.
        </v-alert>

        <!-- Create Dialog -->
        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title>Create Role</v-card-title>
                <v-card-text>
                    <v-text-field
                        v-model="form.name"
                        label="Role Name"
                        :error-messages="form.errors.name"
                    />
                    <v-combobox
                        v-model="form.scopes"
                        :items="scopeOptions"
                        label="Scopes"
                        multiple
                        chips
                        closable-chips
                        hint="Select from spec tags/paths or type custom patterns"
                        :error-messages="form.errors.scopes"
                    />
                    <v-checkbox
                        v-model="form.is_default"
                        label="Default role (assigned to new access methods)"
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
                    />
                    <v-combobox
                        v-model="editForm.scopes"
                        :items="scopeOptions"
                        label="Scopes"
                        multiple
                        chips
                        closable-chips
                        :error-messages="editForm.errors.scopes"
                    />
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
