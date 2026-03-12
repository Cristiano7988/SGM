import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Aluno',
        href: '/alunos/{id}',
    },
];

export default function Show(props: any) {
    const { aluno } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Aluno" />

            <div className='flex flex-col gap-4 p-4'>
                <p><strong>Nome:</strong> {aluno.nome}</p>
                <p><strong>Data de Nascimento:</strong> {aluno.data_de_nascimento_formatada}</p>
                <p><strong>Idade:</strong> {aluno.idade}</p>

                <hr />

                <h2 className='text-lg font-bold'>Usuários Vinculados</h2>
                {!!aluno.users.length ? (
                    <ul className='list-disc list-inside'>
                        {aluno.users.map((user: any) => (
                            <li key={user.id}><Link href={`/users/${user.id}`}>{user.nome}</Link></li>
                        ))}
                    </ul>
                ) : (
                    <p>Nenhum usuário vinculado.</p>
                )}

            </div>
        </AppLayout>
    );
}
