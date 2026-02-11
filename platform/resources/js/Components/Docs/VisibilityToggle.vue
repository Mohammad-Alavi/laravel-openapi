<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import type { DocSetting } from '@/types/models';

const props = defineProps<{
    projectSlug: string;
    setting: DocSetting | null;
}>();

const loading = ref(false);
const visibility = ref(props.setting?.visibility ?? 'private');

function toggle() {
    const newVisibility = visibility.value === 'public' ? 'private' : 'public';
    loading.value = true;
    router.put(`/projects/${props.projectSlug}/doc-settings`, {
        visibility: newVisibility,
    }, {
        preserveScroll: true,
        onSuccess: () => { visibility.value = newVisibility; },
        onFinish: () => { loading.value = false; },
    });
}
</script>

<template>
    <div>
        <p class="text-body-2 text-medium-emphasis mb-4">
            Control whether your API documentation is accessible without authentication.
            Private documentation requires an access link with a valid token.
        </p>
        <div class="d-flex align-center">
            <v-switch
                :model-value="visibility === 'public'"
                :loading="loading"
                color="primary"
                hide-details
                @update:model-value="toggle"
            >
                <template #label>
                    <v-chip
                        :color="visibility === 'public' ? 'success' : 'grey'"
                        :prepend-icon="visibility === 'public' ? 'mdi-earth' : 'mdi-lock'"
                        size="small"
                        class="ml-2"
                    >
                        {{ visibility === 'public' ? 'Public' : 'Private' }}
                    </v-chip>
                </template>
            </v-switch>
        </div>
        <p class="text-caption text-medium-emphasis mt-2">
            {{ visibility === 'public'
                ? 'Anyone with the URL can view your documentation.'
                : 'Only users with a valid access link token can view your documentation.'
            }}
        </p>
    </div>
</template>
