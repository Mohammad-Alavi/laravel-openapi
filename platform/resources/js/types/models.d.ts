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

export interface ProjectStats {
    total: number;
    active: number;
    paused: number;
    building: number;
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
}
