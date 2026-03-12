import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, EditPropsPeriodo } from '@/types';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormPeriodoContent } from '@/components/form-elements/form-periodo-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Períodos', href: '/periodos' },
    { title: 'Editar Período', href: '#' },
];

export default function Edit(props: EditPropsPeriodo) {
    const { periodo, session, pacotes } = props;

    const { processing, delete: deletePeriodo } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este período?')) deletePeriodo(route('periodos.destroy', periodo.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Período' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Período</h1>

                <FormPeriodoContent
                    inicialData={periodo}
                    endpoint={route("periodos.update", periodo.id)}
                    related={{ pacotes }}
                />

                <form onSubmit={submit} className='mt-4'>
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Excluindo..."
                        buttonText="Excluir"
                        classes="bg-red-500 hover:bg-red-600 focus:ring-red-500 focus:ring-offset-red-200"
                    />
                </form>
            </div>
        </AppLayout>
    );
}
