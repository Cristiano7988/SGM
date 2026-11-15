import Filtros from '@/components/filtros';
import FlipCardProfessor from '@/components/flip-card-professor';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexProps, Pagination, SessionType } from '@/types';
import { Professor } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Professores',
        href: '/professores',
    },
];

export default function Index(props: IndexProps<Professor>) {
    const { pagination, session }: { pagination: Pagination<Professor>, session: SessionType } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros = [
        {
            tipo: 'select' as const,
            label: 'Usuários',
            nome: 'users',
            valor: searchParams.get('users') ?? undefined,
            opcoes: props.users,
            ativo: Boolean(searchParams.get('users')),
        },
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs} pagination={pagination}>
            <Head title="Professores" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="professores" />

                {pagination.data.length
                    ? <div className="flex flex-wrap justify-between gap-4">
                        {pagination.data.map((professor) => <FlipCardProfessor key={professor.id} professor={professor} />)}
                    </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                }
            </div>
        </AppLayout>
    );
}
