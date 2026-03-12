import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, RelacionadasAoUser, User } from '@/types/models';
import Session from '@/components/session';
import { FormUserContent } from '@/components/form-elements/form-user-content';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuários', href: '/users' },
    { title: 'Editar Usuário', href: '#' },
];

export default function Edit(props: { session: any, user: User & RelacionadasAoUser, alunos: Aluno[] }) {
    const { user, alunos, session } = props;
    const { processing, delete: deleteUser } = useForm();
    
    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este usuário?')) deleteUser(route('users.destroy', user.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Usuário' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Usuário</h1>

                <FormUserContent
                    inicialData={user}
                    endpoint={route("users.update", user.id)}
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
