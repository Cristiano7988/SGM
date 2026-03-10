import Filtros from '@/components/filtros';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { FiltrosType, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: '/users',
    },
];

export default function Index(props: any) {
    const { pagination, session } = props;
    const filtros: FiltrosType[] = [
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Usuários" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="users" />

                {pagination.data.length
                        ? <div className="flex flex-wrap justify-between gap-4">
                            {pagination.data.map((users: any) => <></>)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
