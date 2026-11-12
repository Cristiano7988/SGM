import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Usuário',
        href: '/users/{id}',
    },
];

export default function Show(props: any) {
    const { user } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Usuário" />

            <div className='flex flex-col gap-4 p-4 max-w-xl'>
                <div className="flex gap-2 justify-between">
                    <p><strong>Nome:</strong> {user.nome}</p>
                    <p><strong>Email:</strong> {user.email}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>CPF:</strong> {user.cpf}</p>
                    <p><strong>CNPJ:</strong> {user.cnpj}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Whatsapp:</strong> {user.whatsapp}</p>
                    <p><strong>Instagram:</strong> {user.instagram}</p>
                </div>
                
                {!!user.alunos.length && (
                    <>
                        <h2 className='text-lg font-bold'>Alunos Vinculados</h2>
                        <ul className='list-disc list-inside'>
                            {user.alunos.map((aluno: any) => (
                                <li key={aluno.id}><Link key={aluno.id} href={`/alunos/${aluno.id}`}>{aluno.nome}</Link></li>
                            ))}
                        </ul>
                    </>
                )}
            </div>
        </AppLayout>
    );
}
