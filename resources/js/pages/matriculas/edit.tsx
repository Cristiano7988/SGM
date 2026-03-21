import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, Props } from '@/types';
import { Matricula, RelacionadasAMatricula } from '@/types/models';
import Session from '@/components/session';
import { FormMatriculaContent } from '@/components/form-elements/form-matricula-content';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Matrículas', href: '/matriculas' },
    { title: 'Editar Matrícula', href: '#' },
];

export default function Edit(props: RelacionadasAMatricula & Props & { matricula: Matricula }) {
    const { matricula, alunos, turmas, pacotes, situacoes, marcacoes, users, session } = props;
    const { processing, delete: deleteMatricula } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir esta matrícula?')) deleteMatricula(route('matriculas.destroy', matricula.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Matrícula' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Matrícula</h1>

                <FormMatriculaContent
                    initialData={matricula}
                    endpoint={route("matriculas.update", matricula.id)}
                    related={{ alunos, turmas, pacotes, situacoes, marcacoes, users }}
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
