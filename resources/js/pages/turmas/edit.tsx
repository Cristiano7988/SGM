import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Turma, BreadcrumbItem, Props } from '@/types';
import Session from '@/components/session';
import { ButtonSubmitContent } from '@/components/form-elements/button-submit-content';
import { FormTurmaContent } from '@/components/form-elements/form-turma-content';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Turmas', href: '/turmas' },
    { title: 'Editar Turma', href: '#' },
];

export default function Edit(props: Props<Turma>) {
    const { turma, session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        nome: turma.nome,
        imagem: turma.imagem,
        descricao: turma.descricao.join('\n\n'),
        vagas_fora_do_site: turma.vagas_fora_do_site,
        vagas_ofertadas: turma.vagas_ofertadas,
        horario: turma.horario,
        dia_id: turma.dia_id,
        nucleo_id: turma.nucleo_id,
        tipo_de_aula_id: turma.tipo_de_aula_id,
        disponivel: turma.disponivel,
        zoom: turma.zoom,
        zoom_id: turma.zoom_id,
        zoom_senha: turma.zoom_senha,
        whatsapp: turma.whatsapp,
        spotify: turma.spotify,
    });

    const { processing: processingDeletion, delete: deleteTurma } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('turmas.update', turma.id));
    };

    const submitDeletion = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir esta turma?')) deleteTurma(route('turmas.destroy', turma.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Turma' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Turma</h1>

                <FormTurmaContent
                    formData={formData}
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
