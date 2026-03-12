import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, User, RelacionadasAoUser } from '@/types/models';
import Session from '@/components/session';
import { FormUserContent } from '@/components/form-elements/form-user-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuários', href: '/users' },
    { title: 'Criar Usuário', href: '#' },
];

export default function Create(props: { session: any, alunos: Aluno[] }) {
    const { session, alunos } = props;
    const initialData: User & RelacionadasAoUser = {
        id: 0,
        nome: '',
        email: '',
        email_nf: '',
        cpf: '',
        cnpj: '',
        vinculo: '',
        whatsapp: '',
        instagram: '',
        cep: '',
        pais: '',
        estado: '',
        cidade: '',
        bairro: '',
        logradouro: '',
        numero: undefined,
        complemento: '',
        alunos: props.alunos,
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Usuário' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Usuário</h1>

                <FormUserContent
                    initialData={initialData}
                    endpoint={route("users.store")}
                    related={{ alunos }}
                />
            </div>
        </AppLayout>
    );
}
