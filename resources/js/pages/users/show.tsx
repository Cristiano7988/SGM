import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

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

            <div className='flex flex-col gap-4 p-4'>
                <div className="flex gap-2">
                    <p><strong>Nome:</strong>{user.nome}</p>
                    <p><strong>Email</strong>{user.email}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>CPF:</strong>{user.cpf}</p>
                    <p><strong>CNPJ:</strong>{user.cnpj}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Whatsapp:</strong>{user.whatsapp}</p>
                    <p><strong>Instagram:</strong>{user.instagram}</p>
                </div>

                <p><strong>CEP:</strong>{user.cep}</p>

                <div className="flex gap-2">
                    <p><strong>País:</strong>{user.pais}</p>
                    <p><strong>Estado:</strong>{user.estado}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Cidade:</strong>{user.cidade}</p>
                    <p><strong>Bairro:</strong>{user.bairro}</p>
                </div>
                <div className="flex gap-2">
                    <p><strong>Logradouro:</strong>{user.logradouro}</p>
                    <p><strong>Número:</strong>{user.numero}</p>
                    <p><strong>Complemento:</strong>{user.complemento}</p>
                </div>
            </div>
        </AppLayout>
    );
}
