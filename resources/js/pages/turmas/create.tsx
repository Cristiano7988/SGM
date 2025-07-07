import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, CreatePropsTurma } from '@/types';
import Session from '@/components/session';
import { FormTurmaContent } from '@/components/form-elements/form-turma-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Turmas', href: '/turmas' },
    { title: 'Criar turma', href: '#' },
];

export default function Create(props: CreatePropsTurma) {
    const { session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        id: 0,
        nome: '',
        imagem: '',
        descricao: '',
        vagas_fora_do_site: 0,
        vagas_ofertadas: 0,
        horario: '',
        dia_id: 0,
        nucleo_id: 0,
        tipo_de_aula_id: 0,
        disponivel: false,
        zoom: '',
        zoom_id: '',
        zoom_senha: '',
        whatsapp: '',
        spotify: '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('turmas.store'));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Criar Turma' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Criar Turma</h1>

                <FormTurmaContent
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
