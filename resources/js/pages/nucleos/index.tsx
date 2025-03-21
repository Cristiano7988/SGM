import Session from '@/components/session';
import AppLayout from '@/layouts/app-layout';
import { Nucleo, Props, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Núcleos',
        href: '/nucleos',
    },
];

export default function Index({ pagination, ...props }: Props<Nucleo>) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />

            <Session {...props}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                {pagination.data.length
                        ? <div className="grid auto-rows-min gap-4 md:grid-cols-3">
                            {pagination.data.map((nucleo: Nucleo) => <div key={nucleo.id.toString()} className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                                <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center gap-4">
                                    <div className='flex flex-col m-auto gap-4'>
                                        <figure className='m-auto w-24 h-24 rounded-full overflow-hidden border border-gray-300'>
                                            <img src={nucleo.imagem} alt={nucleo.nome} className="w-full h-full object-cover" />
                                        </figure>
                                        <b>{nucleo.nome}</b>
                                    </div>
                                    <div className='flex flex-col m-auto gap-6'>
                                        <div className='flex flex-col gap-1'>
                                            <p><strong>Público alvo:</strong></p>
                                            <div>
                                                <p><strong>De:</strong> {nucleo.idade_minima.idade} {nucleo.idade_minima.medida_de_tempo.tipo}</p>
                                                <p><strong>Até:</strong> {nucleo.idade_maxima.idade} {nucleo.idade_maxima.medida_de_tempo.tipo}</p>
                                            </div>
                                        </div>
                                        <div className='flex flex-col gap-1 bg-secondary p-2 rounded-md'>
                                            <p><strong>Período de matrícula:</strong></p>
                                            <div>
                                                <p><strong>De:</strong> {nucleo.inicio_rematricula}</p>
                                                <p><strong>Até:</strong> {nucleo.fim_rematricula}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
