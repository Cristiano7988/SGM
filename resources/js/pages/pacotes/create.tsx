import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsPacote } from '@/types';
import Session from '@/components/session';
import { FormPacoteContent } from '@/components/form-elements/form-pacote-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pacotes', href: '/pacotes' },
    { title: 'Editar Pacote', href: '#' },
];

export default function Create(props: CreatePropsPacote) {
    const { session, nucleos, periodos } = props;
    const initialData = {
        nome: '',
        valor: 0,
        valor_formatado: '',
        tipo: '',
        ativo: false,
        nucleo_id: null,
        periodos,
        nucleos
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Pacote' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Pacote</h1>

                <FormPacoteContent
                    initialData={initialData}
                    endpoint={route("pacotes.store")}
                    related={{ nucleos, periodos }}
                />
            </div>
        </AppLayout>
    );
}
