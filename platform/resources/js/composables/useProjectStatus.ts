import type { Project } from '@/types/models';

interface StatusConfig {
    color: string;
    icon: string;
    label: string;
}

const statusMap: Record<Project['status'], StatusConfig> = {
    active: { color: 'success', icon: 'mdi-check-circle', label: 'Active' },
    paused: { color: 'default', icon: 'mdi-pause-circle', label: 'Paused' },
    building: { color: 'warning', icon: 'mdi-progress-wrench', label: 'Building' },
};

export function useProjectStatus(status: Project['status']): StatusConfig {
    return statusMap[status];
}
