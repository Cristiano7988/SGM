import Filtros from '@/components/filtros';
import FlipCardMatricula from '@/components/flip-card-matricula';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { FiltrosType, type BreadcrumbItem, IndexProps, Pagination, SessionType } from '@/types';
import { Matricula, RelacionadasAMatricula } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matrículas',
        href: '/matriculas',
    },
];

export default function Index(props: IndexProps<Matricula & RelacionadasAMatricula>) {
    const { pagination, session }: { pagination: Pagination<Matricula & RelacionadasAMatricula>, session: SessionType } = props;
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
        {
            tipo: 'select' as const,
            label: 'Turmas',
            nome: 'turmas',
            valor: searchParams.get('turmas') ?? undefined,
            opcoes: props.turmas,
            ativo: Boolean(searchParams.get('turmas')),
        },
        {
            tipo: 'select' as const,
            label: 'Pacotes',
            nome: 'pacotes',
            valor: searchParams.get('pacotes') ?? undefined,
            opcoes: props.pacotes,
            ativo: Boolean(searchParams.get('pacotes')),
        },
        {
            tipo: 'select' as const,
            label: 'Situações',
            nome: 'situacoes',
            valor: searchParams.get('situacoes') ?? undefined,
            opcoes: props.situacoes,
            ativo: Boolean(searchParams.get('situacoes')),
        },
        {
            tipo: 'select' as const,
            label: 'Marcações',
            nome: 'marcacoes',
            valor: searchParams.get('marcacoes') ?? undefined,
            opcoes: props.marcacoes,
            ativo: Boolean(searchParams.get('marcacoes')),
        },
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs} pagination={pagination}>
            <Head title="Matrículas" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="matriculas" />

                {pagination.data.length
                    ? <div className="flex flex-wrap justify-between gap-4">
                        {pagination.data.map((matricula: any) => <FlipCardMatricula key={matricula.id} matricula={matricula} />)}
                    </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                }
            </div>
        </AppLayout>
    );
}
