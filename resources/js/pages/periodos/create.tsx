import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsPeriodo } from '@/types';
import Session from '@/components/session';
import { FormPeriodoContent } from '@/components/form-elements/form-periodo-content';
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Períodos', href: '/periodos' },
    { title: 'Editar Período', href: '#' },
];

export default function Create(props: CreatePropsPeriodo) {
    const { session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        id: 0,
        inicio: '',
        fim: '',
        pacote_id: 0,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('periodos.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Período' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Período</h1>

                <FormPeriodoContent
                    data={formData}
                    processing={processing}
                    submit={submit}
                    setData={setData}
                    errors={errors}
                    props={props}
                />
            </div>
        </AppLayout>
    );
}
