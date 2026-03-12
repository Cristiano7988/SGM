import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, RelacionadasAoAluno, Matricula, User } from '@/types/models';
import Session from '@/components/session';
import { FormAlunoContent } from '@/components/form-elements/form-aluno-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alunos', href: '/alunos' },
    { title: 'Criar Aluno', href: '#' },
];

export default function Create(props: { session: any, users: User[], matriculas: Matricula[] }) {
    const { users, matriculas } = props;
    const initialData: Aluno & RelacionadasAoAluno = {
        id: 0,
        nome: '',
        data_de_nascimento: '',
        data_de_nascimento_formatada: '',
        idade: '',
        users,
        matriculas,
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Aluno' />
            <Session session={props.session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Aluno</h1>

                <FormAlunoContent
                    initialData={initialData}
                    endpoint={route("alunos.store")}
                    related={{ users, matriculas }}
                />
            </div>
        </AppLayout>
    );
}
