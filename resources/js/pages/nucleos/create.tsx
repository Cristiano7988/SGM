import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsNucleo } from '@/types';
import Session from '@/components/session';
import { FormNucleoContent } from '@/components/form-elements/form-nucleo-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Núcleos', href: '/nucleos' },
    { title: 'Editar Núcleo', href: '#' },
];

export default function Create(props: CreatePropsNucleo) {
    const { session, turmas, pacotes } = props;
    const initialData = {
        id: null,
        nome: '',
        imagem: '',
        descricao: '',
        idade_minima: 0,
        unidade_de_tempo_minima: '',
        idade_maxima: 0,
        unidade_de_tempo_maxima: '',
        inicio_matricula: '',
        fim_matricula: '',
        turmas,
        pacotes,
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Núcleo' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Núcleo</h1>

                <FormNucleoContent
                    initialData={initialData}
                    endpoint={route("nucleos.store")}
                    related={{ turmas, pacotes }}
                />
            </div>
        </AppLayout>
    );
}
