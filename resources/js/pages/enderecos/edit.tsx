import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, Endereco } from '@/types/models';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormEnderecoContent } from '@/components/form-elements/form-endereco-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Endereços', href: '/enderecos' },
    { title: 'Editar Endereço', href: '#' },
];

export default function Edit(props: { session: any, endereco: Endereco, alunos: Aluno[] }) {
    const { endereco, alunos, session } = props;
    const { processing, delete: deleteUser } = useForm();
    
    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este endereço?')) deleteUser(route('enderecos.destroy', endereco.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Endereço' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Endereço</h1>

                <FormEnderecoContent
                    initialData={endereco}
                    endpoint={route("enderecos.update", endereco.id)}
                    related={{ alunos }}
                />

                <form onSubmit={submit} className='mt-4'>
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
