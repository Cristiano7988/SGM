import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { User, Professor } from '@/types/models';
import Session from '@/components/session';
import { FormProfessorContent } from '@/components/form-elements/form-professor-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Professores', href: '/professores' },
    { title: 'Criar Professor', href: '#' },
];

export default function Create(props: { session: any, professor: Professor, users: User[] }) {
    const { professor, users } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Professor' />
            <Session session={props.session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Professor</h1>

                <FormProfessorContent
                    initialData={professor}
                    endpoint={route("professores.store")}
                    related={{ users }}
                />
            </div>
        </AppLayout>
    );
}
