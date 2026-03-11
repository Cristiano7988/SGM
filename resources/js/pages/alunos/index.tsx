import Filtros from '@/components/filtros';
import FlipCardAluno from '@/components/flip-card-aluno';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexPropsAluno, Pagination, SessionType } from '@/types';
import { Aluno, RelacionadasAoAluno } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alunos',
        href: '/alunos',
    },
];

export default function Index(props: IndexPropsAluno) {
    const { pagination, session }: { pagination: Pagination<Aluno & RelacionadasAoAluno>, session: SessionType } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros = [
        {
            tipo: 'select' as const,
            label: 'Matrículas',
            nome: 'matriculas',
            valor: searchParams.get('matriculas') ?? undefined,
            opcoes: props.matriculas,
            ativo: Boolean(searchParams.get('matriculas')),
        },
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
            <Head title="Alunos" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="alunos" />

                {pagination.data.length
                    ? <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                        {pagination.data.map((aluno) => <FlipCardAluno key={aluno.id} aluno={aluno} />)}
                    </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                }
            </div>
        </AppLayout>
    );
}
