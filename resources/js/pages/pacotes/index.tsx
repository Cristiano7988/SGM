import CardPacote from '@/components/card-pacote';
import Filtros from '@/components/filtros';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexPropsPacote } from '@/types';
import { Pacote } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacotes de aulas',
        href: '/pacotes',
    },
];

export default function Index(props: IndexPropsPacote) {
    const { pagination, session } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros = [
        {
            tipo: 'boolean' as const,
            label: 'Ativo',
            nome: 'ativo',
            valor: searchParams.get('ativo') ?? 0,
            opcoes: [],
            ativo: Boolean(searchParams.get('ativo')),
        },
        {
            tipo: 'select' as const,
            label: 'Turma',
            nome: 'turmaId',
            valor: searchParams.get('turmaId') ?? undefined,
            opcoes: props.turmas,
            ativo: Boolean(searchParams.get('turmaId')),
        },
        {
            tipo: 'select' as const,
            label: 'Datas',
            nome: 'datas',
            valor: searchParams.get('datas') ?? undefined,
            opcoes: props.datas,
            ativo: Boolean(searchParams.get('datas')),
        },
    ]
    return (
        <AppLayout breadcrumbs={breadcrumbs} pagination={pagination}>
            <Head title="Pacotes" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="pacotes" />

                {pagination.data.length
                    ? <div className="flex flex-wrap justify-between gap-4">
                        {pagination.data.map((pacote: Pacote) => <CardPacote key={pacote.id} pacote={pacote} />)}
                    </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                }
            </div>
        </AppLayout>
    );
}
