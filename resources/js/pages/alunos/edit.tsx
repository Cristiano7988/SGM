import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, Matricula, RelacionadasAoAluno, User } from '@/types/models';
import Session from '@/components/session';
import { FormAlunoContent } from '@/components/form-elements/form-aluno-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alunos', href: '/alunos' },
    { title: 'Editar Aluno', href: '#' },
];

export default function Edit(props: { session: any, aluno: Aluno & RelacionadasAoAluno, users: User[], matriculas: Matricula[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Aluno' />
            <Session session={props.session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Aluno</h1>

                <FormAlunoContent
                    inicialData={props.aluno}
                    endpoint="alunos.update"
                    related={{
                        users: props.users,
                        matriculas: props.matriculas,
                    }}
                />
            </div>
        </AppLayout>
    );
}
