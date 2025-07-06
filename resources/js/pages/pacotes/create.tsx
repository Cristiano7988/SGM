import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, FormPacoteProps } from '@/types';
import Session from '@/components/session';
import { FormPacoteContent } from '@/components/form-elements/form-pacote-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Pacotes', href: '/pacotes' },
    { title: 'Editar Pacote', href: '#' },
];

export default function Create(props: FormPacoteProps) {
    const { session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        id: 0,
        nome: '',
        nucleo_id: 0,
        ativo: false,
        valor: 0,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('pacotes.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Pacote' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Pacote</h1>

                <FormPacoteContent
                    data={formData}
                    processing={processing}
                    submit={submit}
                    setData={setData}
                    errors={errors}
                    props={props}
                />
            </div>
        </AppLayout>
    );
}
