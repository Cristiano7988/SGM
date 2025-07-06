import Filtros from '@/components/filtros';
import FlipCardTurma from '@/components/flip-card-turma';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexTurmaProps, TurmaAndExtraColumns } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Turmas',
        href: '/turmas',
    },
];

export default function Index(props: IndexTurmaProps) {
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
            label: 'Núcleo',
            nome: 'nucleoId',
            valor: searchParams.get('nucleoId') ?? undefined,
            opcoes: props.nucleos,
            ativo: Boolean(searchParams.get('nucleoId')),
        },
        {
            tipo: 'select' as const,
            label: 'Dia',
            nome: 'diaId',
            valor: searchParams.get('diaId') ?? undefined,
            opcoes: props.dias,
            ativo: Boolean(searchParams.get('diaId')),
        },
        {
            tipo: 'select' as const,
            label: 'Tipo de Aula',
            nome: 'tipoDeAulaId',
            valor: searchParams.get('tipoDeAulaId') ?? undefined,
            opcoes: props.tipos_de_aula,
            ativo: Boolean(searchParams.get('tipoDeAulaId')),
        },
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Turmas" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="turmas" />

                {pagination.data.length
                        ? <div className="flex flex-wrap justify-between gap-4">
                            {pagination.data.map((turma: TurmaAndExtraColumns) => <FlipCardTurma key={turma.id} turma={turma} />)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
