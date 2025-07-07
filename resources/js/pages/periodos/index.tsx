import CardPeriodo from '@/components/card-periodo';
import Filtros from '@/components/filtros';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, IndexPropsPeriodo } from '@/types';
import { Periodo } from '@/types/models';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Periodos',
        href: '/periodos',
    },
];

export default function Index(props: IndexPropsPeriodo) {
    const { pagination, session } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtros = [
        {
            tipo: 'select' as const,
            label: 'Pacote',
            nome: 'pacoteId',
            valor: searchParams.get('pacoteId') ?? undefined,
            opcoes: props.pacotes,
            ativo: Boolean(searchParams.get('pacoteId')),
        }
    ]
   
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Periodos" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Filtros dados={filtros} tabela="periodos" />

                {pagination.data.length
                        ? <div className="flex flex-wrap justify-between gap-4">
                            {pagination.data.map((periodo: Periodo) => <CardPeriodo key={periodo.id} periodo={periodo} />)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
