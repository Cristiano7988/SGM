import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, Matricula, RelacionadasAoAluno, User } from '@/types/models';
import Session from '@/components/session';
import { FormAlunoContent } from '@/components/form-elements/form-aluno-content';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alunos', href: '/alunos' },
    { title: 'Editar Aluno', href: '#' },
];

export default function Edit(props: { session: any, aluno: Aluno & RelacionadasAoAluno, users: User[], matriculas: Matricula[] }) {
    const { session, aluno, users, matriculas } = props;
    const { processing, delete: deleteAluno } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este aluno?')) deleteAluno(route('alunos.destroy', aluno.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Aluno' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Aluno</h1>

                <FormAlunoContent
                    initialData={aluno}
                    endpoint={route("alunos.update", aluno.id)}
                    related={{ users, matriculas }}
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
