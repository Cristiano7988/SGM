import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, Props } from '@/types';
import { Matricula, RelacionadasAMatricula } from '@/types/models';
import Session from '@/components/session';
import { FormMatriculaContent } from '@/components/form-elements/form-matricula-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Matrículas', href: '/matriculas' },
    { title: 'Criar Matrícula', href: '#' },
];

export default function Create(props: RelacionadasAMatricula & Props) {
    const { alunos, pacotes, situacoes, marcacoes, users, session } = props;
    const initialData: Matricula & RelacionadasAMatricula = {
        id: null,
        aluno_id: null,
        pacote_id: null,
        situacao_id: null,
        users: []
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Matrícula' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Matrícula</h1>

                <FormMatriculaContent
                    initialData={initialData}
                    endpoint={route("matriculas.store")}
                    related={{ alunos, pacotes, situacoes, marcacoes, users }}
                />
            </div>
        </AppLayout>
    );
}
