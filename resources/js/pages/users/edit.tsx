import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, RelacionadasAoUser, User } from '@/types/models';
import Session from '@/components/session';
import { FormUserContent } from '@/components/form-elements/form-user-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Usuários', href: '/users' },
    { title: 'Editar Usuário', href: '#' },
];

export default function Edit(props: { session: any, user: User & RelacionadasAoUser, alunos: Aluno[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Usuário' />
            <Session session={props.session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Usuário</h1>

                <FormUserContent
                    inicialData={props.user}
                    endpoint="users.update"
                    related={{
                        alunos: props.alunos,
                    }}
                />
            </div>
        </AppLayout>
    );
}
