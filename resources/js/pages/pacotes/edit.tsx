import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, FormPacoteProps } from '@/types';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormPacoteContent } from '@/components/form-elements/form-pacote-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pacotes', href: '/pacotes' },
    { title: 'Editar Pacote', href: '#' },
];

export default function Edit(props: FormPacoteProps) {
    const { pacote, session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        id: pacote.id,
        nome: pacote.nome,
        valor: pacote.valor,
        ativo: pacote.ativo,
        nucleo_id: pacote.nucleo_id,
    });

    const { processing: processingDeletion, delete: deletePacote } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('pacotes.update', pacote.id));
    };

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
                    data={formData}
                    processing={processing}
                    submit={submit}
                    setData={setData}
                    errors={errors}
                    props={props}
                />

                <form onSubmit={submitDeletion} className='mt-4'>
                    <ButtonSubmitContent
                        processing={processingDeletion}
                        processingText="Excluindo..."
                        buttonText="Excluir"
                        classes="bg-red-500 hover:bg-red-600 focus:ring-red-500 focus:ring-offset-red-200"
                    />
                </form>
            </div>
        </AppLayout>
    );
}
