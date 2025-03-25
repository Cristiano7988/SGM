import FlipCardNucleo from '@/components/flip-card-nucleo';
import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { Nucleo, IndexProps, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Núcleos',
        href: '/nucleos',
    },
];

export default function Index(props: IndexProps<Nucleo>) {
    const { pagination, session } = props;
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Núcleos" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
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
