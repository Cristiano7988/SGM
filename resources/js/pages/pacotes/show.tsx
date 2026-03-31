import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, ShowPropsPacote } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacote',
        href: '/pacotes/{id}',
    },
];

export default function Show(props: ShowPropsPacote) {
    const { pacote } = props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pacote" />

            <div className="flex flex-wrap gap-4 p-4">
                <div className='flex gap-4 p-4'>
                    <div className="flex flex-col gap-2">
                        <p><strong>Nome:</strong> {pacote.nome}</p>
                        <p><strong>Valor:</strong> {pacote.valor_formatado}</p>
                        <p><strong>Disponível:</strong> {pacote.ativo ? "Sim" : "Não"}</p>
                        <hr />
                        <p><strong>Turma:</strong> <Link href={"/turmas/" + pacote.turma_id} children={pacote.turma.nome} /></p>
                        <hr />
                        <p><strong>Aulas:</strong></p>
                        {pacote.aulas.map((aula) => <p key={aula.id}>{aula.dia_formatado}</p>)}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
