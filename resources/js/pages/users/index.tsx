import Filtros from '@/components/filtros';
import FlipCardUser from '@/components/flip-card-user';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { FiltrosType, type BreadcrumbItem, IndexProps, Pagination, SessionType } from '@/types';
import { User, RelacionadasAoUser } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuários',
        href: '/users',
    },
];

export default function Index(props: IndexProps<User & RelacionadasAoUser>) {
    const { pagination, session }: { pagination: Pagination<User & RelacionadasAoUser>, session: SessionType } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros: FiltrosType[] = [
        {
            tipo: 'select' as const,
            label: 'Alunos',
            nome: 'alunos',
            valor: searchParams.get('alunos') ?? undefined,
            opcoes: props.alunos,
            ativo: Boolean(searchParams.get('alunos')),
        },
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs} pagination={pagination}>
            <Head title="Usuários (Responsáveis)" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="users" />

                {pagination.data.length
                    ? <div className="flex flex-wrap justify-between gap-4">
                        {pagination.data.map((user: any) => <FlipCardUser key={user.id} user={user} />)}
                    </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                }
            </div>
        </AppLayout>
    );
}
