import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, EditPropsNucleo } from '@/types';;
import Session from '@/components/session';
import { FormNucleoContent } from '@/components/form-elements/form-nucleo-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Núcleos', href: '/nucleos' },
    { title: 'Editar Núcleo', href: '#' },
];

export default function Edit(props: EditPropsNucleo) {
    const { nucleo, turmas, pacotes, session } = props;

    const { processing, delete: deleteNucleo } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este núcleo?')) deleteNucleo(route('nucleos.destroy', nucleo.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Núcleo' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Núcleo</h1>

                <FormNucleoContent
                    initialData={nucleo}
                    endpoint={route("nucleos.update", nucleo.id)}
                    related={{ turmas, pacotes }}
                />

                <form onSubmit={submit} >
                    <div className="flex justify-end mt-4">
                        <button
                            type="submit"
                            className="cursor-pointer bg-red-500 text-white px-4 py-2 rounded-md"
                            disabled={processing}
                        >
                            {processing ? "Excluindo..." : "Excluir"}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
