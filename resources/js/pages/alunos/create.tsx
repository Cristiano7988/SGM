import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsAluno } from '@/types';
import Session from '@/components/session';
import { FormAlunoContent } from '@/components/form-elements/form-aluno-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alunos', href: '/alunos' },
    { title: 'Criar Aluno', href: '#' },
];

export default function Create(props: CreatePropsAluno) {
    const { session } = props;
    const { data: formData, setData, post, processing, errors, hasErrors } = useForm({
        id: 0,
        nome: '',
        data_de_nascimento: '',
        users: [],
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('alunos.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Aluno' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Aluno</h1>

                <FormAlunoContent
                    data={formData}
                    processing={processing}
                    submit={submit}
                    setData={setData}
                    errors={errors}
                    hasErrors={hasErrors}
                    props={props}
                />
            </div>
        </AppLayout>
    );
}
