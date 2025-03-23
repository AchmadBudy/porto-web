import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;


export type CategoryForm = {
    name: string;
    description?: string;
    _method?: string;
}

export type Category = {
    id: number;
    name: string;
    slug: string;
    description?: string;
}

export type Gallery = {
    id: number;
    image: string;
    project_id: number;
}

export type ProjectForm = {
    name: string;
    description: string;
    image: File | null;
    attributes: Array<{ label: string; value: string }>;
    categories: number[];
    galleries: File[];
    old_galleries?: Array[];
    _method?: string;
}

export type Project = {
    id: number;
    name: string;
    slug: string;
    description: string;
    image: string;
    attributes: Array<{ label: string; value: string }>;
    categories: Category[];
    galleries: Gallery[];
    created_at: string;
    updated_at: string;
}