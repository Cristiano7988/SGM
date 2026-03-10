import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuário',
        href: '/users/{id}',
    },
];

export default function Show(props: any) {
    const { user } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Usuário" />

            <div className="flex flex-wrap gap-4 p-4">
                <div className='flex gap-4 p-4'>
                    <div className="flex flex-col gap-2">

                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
