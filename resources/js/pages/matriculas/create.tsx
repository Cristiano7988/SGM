import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Aluno, Marcacao, Matricula, Pacote, RelacionadasAMatricula, Situacao, Turma } from '@/types/models';
import Session from '@/components/session';
import { FormMatriculaContent } from '@/components/form-elements/form-matricula-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Matrículas', href: '/matriculas' },
    { title: 'Criar Matrícula', href: '#' },
];

export default function Create(props: { session: any, alunos: Aluno[], turmas: Turma[], pacotes: Pacote[], situacoes: Situacao[], marcacoes: Marcacao[] }) {
    const { alunos, turmas, pacotes, situacoes, marcacoes, session } = props;
    const initialData: Matricula & RelacionadasAMatricula = {
        id: 0,
        aluno_id: 0,
        turma_id: 0,
        pacote_id: 0,
        situacao_id: 0,
        alunos,
        turmas,
        pacotes,
        situacoes,
        marcacoes
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
                    related={{ alunos, turmas, pacotes, situacoes, marcacoes }}
                />
            </div>
        </AppLayout>
    );
}
