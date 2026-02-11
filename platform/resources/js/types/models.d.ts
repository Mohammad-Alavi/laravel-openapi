export interface User {
    id: number;
    name: string;
    email: string;
    github_id: string;
    github_avatar: string | null;
}

export interface Project {
    id: number;
    user_id: number;
    name: string;
    slug: string;
    description: string | null;
    github_repo_url: string;
    github_branch: string;
    status: 'active' | 'paused' | 'building';
    last_built_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Build {
    id: number;
    project_id: number;
    commit_sha: string;
    status: 'pending' | 'building' | 'completed' | 'failed';
    output_path: string | null;
    error_log: string | null;
    started_at: string | null;
    completed_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface ProjectStats {
    total: number;
    active: number;
    paused: number;
    building: number;
}

export interface AppNotification {
    id: string;
    type: string;
    data: {
        project_id: number;
        project_name: string;
        build_id: number;
        status: string;
        commit_sha: string;
    };
    read_at: string | null;
    created_at: string;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

export interface PageProps {
    auth: {
        user: User;
    };
    flash: {
        success?: string;
        error?: string;
    };
    unreadNotificationsCount: number;
}
