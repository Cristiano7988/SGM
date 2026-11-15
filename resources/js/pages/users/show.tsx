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

                <h2 className='text-lg font-bold'>Endereço:</h2>

                <p><strong>CEP:</strong> {user.endereco?.cep}</p>

                <div className="flex gap-2">
                    <p><strong>País:</strong> {user.endereco?.pais}</p>
                    <p><strong>Estado:</strong> {user.endereco?.estado}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Cidade:</strong> {user.endereco?.cidade}</p>
                    <p><strong>Bairro:</strong> {user.endereco?.bairro}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Logradouro:</strong> {user.endereco?.logradouro}</p>
                    <p><strong>Número:</strong> {user.endereco?.numero}</p>
                    <p><strong>Complemento:</strong> {user.endereco?.complemento}</p>
                </div>

                <hr />
                
                {!!user.alunos?.length && (
                    <>
                        <h2 className='text-lg font-bold'>Alunos Vinculados</h2>
                        <ul className='list-disc list-inside'>
                            {user.alunos.map((aluno: any) => (
                                <li key={aluno.id}><Link key={aluno.id} href={`/alunos/${aluno.id}`}>{aluno.nome}</Link></li>
                            ))}
                        </ul>
                    </>
                )}

                <hr />

                {!!user.professores?.length && (
                    <>
                        <h2 className='text-lg font-bold'>Professores Vinculados</h2>
                        <ul className='list-disc list-inside'>
                            {user.professores.map((professor: any) => (
                                <li key={professor.id}><Link key={professor.id} href={`/professores/${professor.id}`}>{professor.nome}</Link></li>
                            ))}
                        </ul>
                    </>
                )}
            </div>
        </AppLayout>
    );
}
