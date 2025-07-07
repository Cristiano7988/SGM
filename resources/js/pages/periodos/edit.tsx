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
    const { periodo, session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        id: periodo.id,
        inicio: periodo.inicio,
        fim: periodo.fim,
        pacote_id: periodo.pacote_id,
    });

    const { processing: processingDeletion, delete: deletePeriodo } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('periodos.update', periodo.id));
    };

    const submitDeletion = (e: React.FormEvent) => {
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
                    data={formData}
                    processing={processing}
                    submit={submit}
                    setData={setData}
                    errors={errors}
                    props={props}
                />

                <form onSubmit={submitDeletion} className='mt-4'>
                    <ButtonSubmitContent
                        processing={processingDeletion}
                        processingText="Excluindo..."
                        buttonText="Excluir"
                        classes="bg-red-500 hover:bg-red-600 focus:ring-red-500 focus:ring-offset-red-200"
                    />
                </form>
            </div>
        </AppLayout>
    );
}
