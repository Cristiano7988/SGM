import CarouselText from '@/components/carousel-text';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, ShowPropsTurma } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Turma',
        href: '/turmas/{id}',
    },
];

export default function Show({ turma }: ShowPropsTurma) {

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Turma" />

            <div className="flex flex-wrap gap-4 p-4">
                <div className="flex flex-col items-center gap-4 p-4 text-center">
                    <figure className="w-24 h-24 rounded-full overflow-hidden border border-gray-300">
                        <img
                        src={turma.imagem}
                        alt={turma.nome}
                        className="w-full h-full object-cover"
                        />
                    </figure>
                    <b>{turma.nome}</b>
                </div>
                <div className='flex flex-col gap-4 p-4'>
                    <div className="flex flex-col gap-1">
                        <p><strong>Quando?</strong></p>
                        <div>
                            <p><strong>Dia:</strong> {turma.dia.nome}</p>
                            <p><strong>Horário:</strong> {turma.horario}</p>
                        </div>
                    </div>

                    <div className="overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-4 text-center">
                        <CarouselText paragraphs={turma.descricao} />
                    </div>

                    <div className="flex flex-col gap-1">
                        <div>
                            <p><strong>Núcleo:</strong> <Link href={"/nucleos/" + turma.nucleo_id} children={turma.nucleo.nome} /></p>
                            <p><strong>Tipo de aula:</strong> {turma.tipo_de_aula.tipo}</p>
                            <p><strong>Disponível:</strong> {turma.disponivel ? "Sim" : "Não"}</p>
                        </div>
                    </div>

                    <div className="flex flex-col gap-1 bg-gray-100 dark:bg-gray-900 p-4 rounded-md">
                        <p><strong>Zoom:</strong></p>
                        <div>
                            <p><strong>Link:</strong> {turma.zoom}</p>
                            <p><strong>ID:</strong> {turma.zoom_id}</p>
                            <p><strong>Senha:</strong> {turma.zoom_senha}</p>
                        </div>

                        <hr className="my-2" />
                        
                        <p><strong>Whatsapp:</strong> {turma.whatsapp}</p>
                        <p><strong>Spotify:</strong> {turma.spotify}</p>
                    </div>

                    <div className="flex flex-col gap-1">
                        <p><strong>Vagas:</strong></p>
                        <div>
                            <p><strong>Ofertadas:</strong> {turma.vagas_ofertadas}</p>
                            <p><strong>Fora do Site:</strong> {turma.vagas_fora_do_site}</p>
                            <p><strong>Preenchidas:</strong> {turma.vagas_preenchidas}</p>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
