import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode } from 'react';

interface Link {
    url: string | null;
    active: boolean;
    label: string;
}

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
    pagination?: {
        links: Link[]
    }
}

export default ({ children, breadcrumbs, pagination, ...props }: AppLayoutProps) => (
    <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
        {children}

        <div className="flex justify-center gap-2 p-4">
            {pagination?.links.map((link: Link, index: number) => (
                <a
                    key={index}
                    href={link.url ?? '#'}
                    dangerouslySetInnerHTML={{ __html: link.label }}
                    className={`px-3 py-1 border rounded ${
                        link.active ? 'bg-blue-500 text-white' : ''
                    } ${!link.url ? 'opacity-50 pointer-events-none' : ''}`}
                />
            ))}
        </div>
    </AppLayoutTemplate>
);
