<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import type { DocVisibilityRule, SpecTag, SpecPath } from '@/types/models';

const props = defineProps<{
    projectId: number;
    rules: DocVisibilityRule[];
    specTags: SpecTag[];
    specPaths: SpecPath[];
}>();

const showCreateDialog = ref(false);
const visibilityOptions = ['public', 'internal', 'restricted', 'hidden'];
const visibilityColors: Record<string, string> = {
    public: 'success',
    internal: 'info',
    restricted: 'warning',
    hidden: 'error',
};

const form = useForm({
    rule_type: 'tag' as 'tag' | 'path',
    identifier: '',
    visibility: 'public' as string,
});

const identifierOptions = ref<string[]>([]);

function updateIdentifierOptions() {
    identifierOptions.value = form.rule_type === 'tag'
        ? props.specTags.map(t => t.name)
        : props.specPaths.map(p => p.path);
}
updateIdentifierOptions();

function createRule() {
    form.post(`/projects/${props.projectId}/doc-rules`, {
        preserveScroll: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            form.reset();
            updateIdentifierOptions();
        },
    });
}

function updateVisibility(rule: DocVisibilityRule, newVisibility: string) {
    router.put(`/projects/${props.projectId}/doc-rules/${rule.id}`, {
        visibility: newVisibility,
    }, { preserveScroll: true });
}

function deleteRule(rule: DocVisibilityRule) {
    router.delete(`/projects/${props.projectId}/doc-rules/${rule.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="d-flex align-center mb-4">
            <h3 class="text-subtitle-1 font-weight-bold">Endpoint Visibility Rules</h3>
            <v-spacer />
            <v-btn
                color="primary"
                size="small"
                prepend-icon="mdi-plus"
                @click="showCreateDialog = true"
            >
                Add Rule
            </v-btn>
        </div>

        <v-table v-if="rules.length > 0" density="compact">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Identifier</th>
                    <th>Visibility</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="rule in rules" :key="rule.id">
                    <td>
                        <v-chip size="x-small" :color="rule.rule_type === 'tag' ? 'primary' : 'secondary'">
                            {{ rule.rule_type }}
                        </v-chip>
                    </td>
                    <td><code>{{ rule.identifier }}</code></td>
                    <td>
                        <v-select
                            :model-value="rule.visibility"
                            :items="visibilityOptions"
                            density="compact"
                            hide-details
                            variant="outlined"
                            style="max-width: 160px"
                            @update:model-value="(v: string) => updateVisibility(rule, v)"
                        >
                            <template #selection="{ item }">
                                <v-chip :color="visibilityColors[item.value]" size="x-small">
                                    {{ item.value }}
                                </v-chip>
                            </template>
                        </v-select>
                    </td>
                    <td class="text-right">
                        <v-btn icon="mdi-delete" size="x-small" variant="text" color="error" @click="deleteRule(rule)" />
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else type="info" variant="tonal" density="compact">
            No visibility rules. All endpoints default to public.
        </v-alert>

        <v-dialog v-model="showCreateDialog" max-width="500">
            <v-card>
                <v-card-title>Add Visibility Rule</v-card-title>
                <v-card-text>
                    <v-btn-toggle
                        v-model="form.rule_type"
                        mandatory
                        color="primary"
                        class="mb-4"
                        @update:model-value="updateIdentifierOptions"
                    >
                        <v-btn value="tag">Tag</v-btn>
                        <v-btn value="path">Path</v-btn>
                    </v-btn-toggle>
                    <v-combobox
                        v-model="form.identifier"
                        :items="identifierOptions"
                        :label="form.rule_type === 'tag' ? 'Tag name or pattern' : 'Path or pattern'"
                        hint="Supports wildcards: payments.*, /api/v2/*"
                        :error-messages="form.errors.identifier"
                    />
                    <v-select
                        v-model="form.visibility"
                        :items="visibilityOptions"
                        label="Visibility"
                        :error-messages="form.errors.visibility"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn @click="showCreateDialog = false">Cancel</v-btn>
                    <v-btn color="primary" :loading="form.processing" @click="createRule">Create</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>
