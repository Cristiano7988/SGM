import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Professor',
        href: '/professores/{id}',
    },
];

export default function Show(props: any) {
    const { professor } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Professor" />

            <div className='flex flex-col gap-4 p-4'>
                <figure className="w-32 h-32 rounded-full overflow-hidden border border-gray-300">
                    <img
                        src={professor.imagem}
                        alt={professor.nome}
                        className="w-full h-full object-cover"
                    />
                </figure>

                <div className='flex flex-col gap-4 p-4 max-w-xl'>
                    <div className="flex gap-2 justify-between">
                        <p><strong>Nome:</strong> {professor.user.nome}</p>
                        <p><strong>Email:</strong> {professor.user.email}</p>
                    </div>
                    <div className="flex gap-2">
                        <p><strong>CPF:</strong> {professor.user.cpf}</p>
                        <p><strong>CNPJ:</strong> {professor.user.cnpj}</p>
                    </div>
                    <div className="flex gap-2">
                        <p><strong>Whatsapp:</strong> {professor.user.whatsapp}</p>
                        <p><strong>Instagram:</strong> {professor.user.instagram}</p>
                    </div>
                </div>

                <h2 className='text-lg font-bold'>Endereço:</h2>

                <p><strong>CEP:</strong> {professor.user.endereco?.cep}</p>

                <div className="flex gap-2">
                    <p><strong>País:</strong> {professor.user.endereco?.pais}</p>
                    <p><strong>Estado:</strong> {professor.user.endereco?.estado}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Cidade:</strong> {professor.user.endereco?.cidade}</p>
                    <p><strong>Bairro:</strong> {professor.user.endereco?.bairro}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Logradouro:</strong> {professor.user.endereco?.logradouro}</p>
                    <p><strong>Número:</strong> {professor.user.endereco?.numero}</p>
                    <p><strong>Complemento:</strong> {professor.user.endereco?.complemento}</p>
                </div>

                <hr />

                <h2 className='text-lg font-bold'>Usuários Vinculados</h2>
                {!!professor.users?.length ? (
                    <ul className='list-disc list-inside'>
                        {professor.users.map((user: any) => (
                            <li key={user.id}><Link href={`/users/${user.id}`}>{user.nome}</Link></li>
                        ))}
                    </ul>
                ) : (
                    <p>Nenhum usuário vinculado.</p>
                )}

                <hr />

                <h2 className='text-lg font-bold'>Endereços Vinculados</h2>
                {!!professor.enderecos?.length ? (
                    <ul className='list-disc list-inside'>
                        {professor.enderecos.map((endereco: any) => (
                            <li key={endereco.id}>{endereco.logradouro}, {endereco.numero} - {endereco.bairro}, {endereco.cidade}/{endereco.estado}, {endereco.cep} - {endereco.pais}</li>
                        ))}
                    </ul>
                ) : (
                    <p>Nenhum endereço vinculado.</p>
                )}

            </div>
        </AppLayout>
    );
}
