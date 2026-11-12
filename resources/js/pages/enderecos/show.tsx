import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Endereços',
        href: '/enderecos/{id}',
    },
];

export default function Show(props: any) {
    const { endereco } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Endereço" />

            <div className='flex flex-col gap-4 p-4 max-w-xl'>
                <p><strong>CEP:</strong> {endereco.cep}</p>

                <div className="flex gap-2">
                    <p><strong>País:</strong> {endereco.pais}</p>
                    <p><strong>Estado:</strong> {endereco.estado}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Cidade:</strong> {endereco.cidade}</p>
                    <p><strong>Bairro:</strong> {endereco.bairro}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Logradouro:</strong> {endereco.logradouro}</p>
                    <p><strong>Número:</strong> {endereco.numero}</p>
                    <p><strong>Complemento:</strong> {endereco.complemento}</p>
                </div>

                <hr />
                
                {!!endereco.alunos?.length && (
                    <>
                        <h2 className='text-lg font-bold'>Alunos Vinculados</h2>
                        {endereco.alunos ? <ul className='list-disc list-inside'>
                            {endereco.alunos.map((aluno: any) => (
                                <li key={aluno.id}><Link key={aluno.id} href={`/alunos/${aluno.id}`}>{aluno.nome}</Link></li>
                            ))}
                        </ul> : <p>Nenhum aluno vinculado a este endereço.</p>}
                    </>
                )}
            </div>
        </AppLayout>
    );
}
