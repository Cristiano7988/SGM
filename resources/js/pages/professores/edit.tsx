import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { User, Professor } from '@/types/models';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormProfessorContent } from '@/components/form-elements/form-professor-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Professores', href: '/professores' },
    { title: 'Editar Professor', href: '#' },
];

export default function Edit(props: { session: any, professor: Professor, users: User[] }) {
    const { session, professor, users } = props;
    const { processing, delete: deleteProfessor } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este professor?')) deleteProfessor(route('professores.destroy', professor.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Professor' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Professor</h1>

                <FormProfessorContent
                    initialData={professor}
                    endpoint={route("professores.update", professor.id)}
                    related={{ users }}
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
