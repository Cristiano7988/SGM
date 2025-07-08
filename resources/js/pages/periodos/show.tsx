import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, ShowPropsPeriodo } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Período',
        href: '/periodos/{id}',
    },
];

export default function Show(props: ShowPropsPeriodo) {
    const { periodo } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Período" />

            <div className="flex flex-wrap gap-4 p-4">
                <div className='flex gap-4 p-4'>
                    <div className="flex flex-col gap-2">
                        <p><strong>Inicio:</strong> {periodo.inicio}</p>
                        <p><strong>Fim:</strong> {periodo.fim}</p>
                        <hr />
                        <p><strong>Pacote:</strong> <Link href={"/pacotes/" + periodo.pacote_id} children={periodo.pacote.nome} /></p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
