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
                    size="small"
                    class="ml-2"
                >
                    {{ visibility === 'public' ? 'Public' : 'Private' }}
                </v-chip>
            </template>
        </v-switch>
    </div>
</template>
