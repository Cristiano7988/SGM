import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { User, Endereco } from '@/types/models';
import Session from '@/components/session';
import { FormEnderecoContent } from '@/components/form-elements/form-endereco-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Endereços', href: '/enderecos' },
    { title: 'Criar Endereço', href: '#' },
];

export default function Create(props: { session: any, endereco: Endereco, users: User[] }) {
    const { session, endereco, users } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Endereço' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Endereço</h1>

                <FormEnderecoContent
                    initialData={endereco}
                    endpoint={route("enderecos.store")}
                    related={{ users }}
                />
            </div>
        </AppLayout>
    );
}
