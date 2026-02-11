export interface User {
    id: number;
    name: string;
    email: string;
    github_id: string;
    github_avatar: string | null;
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
    latest_build_id: number | null;
    created_at: string;
    updated_at: string;
}

export interface DocSetting {
    project_id: number;
    visibility: 'public' | 'private';
}

export interface DocRole {
    id: string;
    name: string;
    scopes: string[];
    is_default: boolean;
}

export interface DocVisibilityRule {
    id: string;
    rule_type: 'tag' | 'path';
    identifier: string;
    visibility: 'public' | 'internal' | 'restricted' | 'hidden';
}

export interface DocAccessLink {
    id: string;
    doc_role_id: string;
    name: string;
    expires_at: string | null;
    last_used_at: string | null;
    is_expired: boolean;
}

export interface SpecTag {
    name: string;
    description: string | null;
}

export interface SpecPath {
    path: string;
    methods: string[];
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
        project_slug: string;
        project_name: string;
        build_ulid: string;
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
