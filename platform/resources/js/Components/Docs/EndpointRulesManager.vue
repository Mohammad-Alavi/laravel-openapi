<script setup lang="ts">
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import type { DocVisibilityRule, SpecTag, SpecPath } from '@/types/models';

const props = defineProps<{
    projectSlug: string;
    rules: DocVisibilityRule[];
    specTags: SpecTag[];
    specPaths: SpecPath[];
}>();

const showCreateDialog = ref(false);
const visibilityOptions = [
    { title: 'Public', value: 'public' },
    { title: 'Internal', value: 'internal' },
    { title: 'Restricted', value: 'restricted' },
    { title: 'Hidden', value: 'hidden' },
];
const visibilityColors: Record<string, string> = {
    public: 'success',
    internal: 'info',
    restricted: 'warning',
    hidden: 'error',
};
const visibilityDescriptions: Record<string, string> = {
    public: 'Visible to everyone',
    internal: 'Visible to authenticated users only',
    restricted: 'Visible only to roles with matching scopes',
    hidden: 'Not visible in documentation',
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
    form.post(`/projects/${props.projectSlug}/doc-rules`, {
        preserveScroll: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            form.reset();
            updateIdentifierOptions();
        },
    });
}

function updateVisibility(rule: DocVisibilityRule, newVisibility: string) {
    router.put(`/projects/${props.projectSlug}/doc-rules/${rule.id}`, {
        visibility: newVisibility,
    }, { preserveScroll: true });
}

function deleteRule(rule: DocVisibilityRule) {
    router.delete(`/projects/${props.projectSlug}/doc-rules/${rule.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <div class="d-flex align-center mb-2">
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
        <p class="text-body-2 text-medium-emphasis mb-4">
            Override the default visibility of specific endpoints by tag or path. Rules are evaluated in order.
        </p>

        <v-table v-if="rules.length > 0" density="compact">
            <thead>
                <tr>
                    <th>
                        <v-tooltip text="Match endpoints by OpenAPI tag or URL path" location="bottom">
                            <template #activator="{ props: tp }">
                                <span v-bind="tp" class="d-inline-flex align-center" style="cursor: help;">
                                    Type
                                    <v-icon size="x-small" class="ml-1">mdi-information-outline</v-icon>
                                </span>
                            </template>
                        </v-tooltip>
                    </th>
                    <th>Identifier</th>
                    <th>
                        <v-tooltip text="Controls who can see matching endpoints in the documentation" location="bottom">
                            <template #activator="{ props: tp }">
                                <span v-bind="tp" class="d-inline-flex align-center" style="cursor: help;">
                                    Visibility
                                    <v-icon size="x-small" class="ml-1">mdi-information-outline</v-icon>
                                </span>
                            </template>
                        </v-tooltip>
                    </th>
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
                                    {{ item.title }}
                                </v-chip>
                            </template>
                        </v-select>
                    </td>
                    <td class="text-right">
                        <v-tooltip text="Delete rule" location="bottom">
                            <template #activator="{ props: tp }">
                                <v-btn v-bind="tp" icon="mdi-delete" size="x-small" variant="text" color="error" @click="deleteRule(rule)" />
                            </template>
                        </v-tooltip>
                    </td>
                </tr>
            </tbody>
        </v-table>

        <v-alert v-else type="info" variant="tonal" density="compact">
            No visibility rules configured. All endpoints use their default visibility.
        </v-alert>

        <!-- Visibility legend -->
        <div class="mt-4">
            <p class="text-caption font-weight-medium mb-1">Visibility levels:</p>
            <div class="d-flex flex-wrap ga-2">
                <v-tooltip v-for="(desc, level) in visibilityDescriptions" :key="level" :text="desc" location="bottom">
                    <template #activator="{ props: tp }">
                        <v-chip v-bind="tp" :color="visibilityColors[level]" size="x-small" style="cursor: help;">
                            {{ level }}
                        </v-chip>
                    </template>
                </v-tooltip>
            </div>
        </div>

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
                        persistent-hint
                        :error-messages="form.errors.identifier"
                        class="mb-2"
                    />
                    <v-select
                        v-model="form.visibility"
                        :items="visibilityOptions"
                        label="Visibility"
                        :error-messages="form.errors.visibility"
                    >
                        <template #item="{ props: itemProps, item }">
                            <v-list-item v-bind="itemProps">
                                <template #append>
                                    <span class="text-caption text-medium-emphasis">{{ visibilityDescriptions[item.value] }}</span>
                                </template>
                            </v-list-item>
                        </template>
                    </v-select>
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
