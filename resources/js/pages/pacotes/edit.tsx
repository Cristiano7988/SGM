import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, EditPropsPacote } from '@/types';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormPacoteContent } from '@/components/form-elements/form-pacote-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pacotes', href: '/pacotes' },
    { title: 'Editar Pacote', href: '#' },
];

export default function Edit(props: EditPropsPacote) {
    const { pacote, session, nucleos } = props;

    const { processing, delete: deletePacote } = useForm();

    const submitDeletion = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este pacote?')) deletePacote(route('pacotes.destroy', pacote.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Pacote' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Pacote</h1>

                <FormPacoteContent
                    initialData={pacote}
                    endpoint={route('pacotes.update', pacote.id)}
                    related={{ nucleos }}
                />

                <form onSubmit={submitDeletion} className='mt-4'>
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Excluindo..."
                        buttonText="Excluir"
                        classes="bg-red-500 hover:bg-red-600 focus:ring-red-500 focus:ring-offset-red-200"
                    />
                </form>
            </div>
        </AppLayout>
    );
}
