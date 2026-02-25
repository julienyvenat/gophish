export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Template {
    id: number;
    name: string;
    subject: string;
    html: string;
    text: string;
    created_at: string;
    updated_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ziggy: {
        location: string;
        url: string;
        port: number | null;
        defaults: Record<string, unknown>;
        routes: Record<string, unknown>;
    };
};
