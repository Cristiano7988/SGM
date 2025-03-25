import CarouselText from '@/components/carousel-text';
import AppLayout from '@/layouts/app-layout';
import { Nucleo, type BreadcrumbItem, Props } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Núcleo',
        href: '/nucleos/{id}',
    },
];

export default function Show(props: Props<Nucleo>) {
    const { nucleo } = props;
    const convertToYear = (idade: number) => {
        const meses = idade / 12;
        
        return meses % 1 === 0
            ? Math.floor(meses)
            : (meses).toFixed(1);
    }
    
    const idade_minima = nucleo.idade_minima > 12 ? convertToYear(nucleo.idade_minima) : nucleo.idade_minima;
    const idade_maxima = nucleo.idade_maxima > 12 ? convertToYear(nucleo.idade_maxima) : nucleo.idade_maxima

      
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Núcleo" />

            <div className="flex gap-4 p-4">
                <div className="flex flex-col items-center gap-4 p-4 text-center">
                    <figure className="w-24 h-24 rounded-full overflow-hidden border border-gray-300">
                        <img
                        src={nucleo.imagem}
                        alt={nucleo.nome}
                        className="w-full h-full object-cover"
                        />
                    </figure>
                    <b>{nucleo.nome}</b>
                </div>
                <div className="flex flex-col gap-6 p-4">
                    <div className="flex flex-col gap-1">
                        <p><strong>Público alvo:</strong></p>
                        <div>
                        <p><strong>De:</strong> {idade_minima} {nucleo.unidade_de_tempo_minima}</p>
                        <p><strong>Até:</strong> {idade_maxima} {nucleo.unidade_de_tempo_maxima}</p>
                        </div>
                    </div>
                    <div className="flex flex-col gap-1 bg-gray-100 dark:bg-gray-900 p-4 rounded-md">
                        <p><strong>Matrículas:</strong></p>
                        <div>
                        <p><strong>De:</strong> {nucleo.inicio_matricula}</p>
                        <p><strong>Até:</strong> {nucleo.fim_matricula}</p>
                        </div>
                    </div>
                    <div className="overflow-hidden bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-4 text-center">
                        <CarouselText paragraphs={nucleo.descricao} />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
