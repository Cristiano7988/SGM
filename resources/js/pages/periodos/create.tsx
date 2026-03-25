import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsPeriodo } from '@/types';
import Session from '@/components/session';
import { FormPeriodoContent } from '@/components/form-elements/form-periodo-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Períodos', href: '/periodos' },
    { title: 'Editar Período', href: '#' },
];

export default function Create(props: CreatePropsPeriodo) {
    const { session, pacotes } = props;
    const initialData = {
        id: null,
        inicio: '',
        fim: '',
        pacote_id: null,
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Período' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Período</h1>

                <FormPeriodoContent
                    initialData={initialData}
                    endpoint={route('periodos.store')}
                    related={{ pacotes }}
                />
            </div>
        </AppLayout>
    );
}
