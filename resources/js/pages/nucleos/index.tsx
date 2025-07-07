import FlipCardNucleo from '@/components/flip-card-nucleo';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, FiltrosType, IndexPropsNucleo } from '@/types';
import { Head } from '@inertiajs/react';
import Filtros from '@/components/filtros';
import { Nucleo } from '@/types/models';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Núcleos',
        href: '/nucleos',
    },
];

export default function Index(props: IndexPropsNucleo) {
    const { pagination, session } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros: FiltrosType[] = [
        {
            tipo: 'select',
            label: 'Turma',
            nome: 'turmas',
            valor: searchParams.get('turmas') ?? undefined,
            opcoes: props.turmas,
            ativo: Boolean(searchParams.get('turmas')),
        },
        {
            tipo: 'select',
            label: 'Pacote',
            nome: 'pacotes',
            valor: searchParams.get('pacotes') ?? undefined,
            opcoes: props.pacotes,
            ativo: Boolean(searchParams.get('pacotes')),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Núcleos" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="nucleos" />
                
                {pagination.data.length
                        ? <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                            {pagination.data.map((nucleo: Nucleo) => <FlipCardNucleo key={nucleo.id} nucleo={nucleo} />)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
