import Filtros from '@/components/filtros';
import FlipCardAluno from '@/components/flip-card-aluno';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexPropsAluno } from '@/types';
import { Aluno } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Alunos',
        href: '/alunos',
    },
];

export default function Index(props: IndexPropsAluno) {
    const { pagination, session } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros = [
        {
            tipo: 'boolean' as const,
            label: 'Disponível',
            nome: 'disponivel',
            valor: searchParams.get('disponivel') ?? 0,
            opcoes: [],
            ativo: Boolean(searchParams.get('disponivel')),
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
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Alunos" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="alunos" />

                {pagination.data.length
                        ? <div className="flex flex-wrap justify-between gap-4">
                            {pagination.data.map((aluno: Aluno) => <FlipCardAluno key={aluno.id} aluno={aluno} />)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
