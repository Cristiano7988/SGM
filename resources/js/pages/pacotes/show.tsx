import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, ShowPacoteProps } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacote',
        href: '/pacotes/{id}',
    },
];

export default function Show(props: ShowPacoteProps) {
    const { pacote } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pacote" />

            <div className="flex flex-wrap gap-4 p-4">
                <div className='flex gap-4 p-4'>
                    <div className="flex flex-col gap-1">
                        <p><strong>Nome:</strong> {pacote.nome}</p>
                        <p><strong>Valor:</strong> {pacote.valor_formatado}</p>
                        <p><strong>Núcleo:</strong> <Link href={"/nucleos/" + pacote.nucleo_id} children={pacote.nucleo.nome} /></p>
                        <p><strong>Disponível:</strong> {pacote.ativo ? "Sim" : "Não"}</p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
