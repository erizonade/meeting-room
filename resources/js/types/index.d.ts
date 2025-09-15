export interface Auth {
    role: any;
    user: User;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
};

export interface User {
    role: any;
    id: number;
    name: string;
    email: string;
    role: string;
    unit_id: int;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface Units {
    id: number;
    name: string;
}

export interface MeetinRoom {
    id: number;
    name: string;
    capacity: number;
}

export interface Consumption {
    id: number;
    name: string;
    price: number;
}