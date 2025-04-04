import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Nucleo, BreadcrumbItem, Props } from '@/types';
import { InputImageContent } from '@/components/form-elements/input-image-content';
import { CalendarIcon } from 'lucide-react';
import Session from '@/components/session';
import ErrorLabel from '@/components/error-label';
import IdadeInputToggle from '@/components/idade-input-toggle';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Núcleos', href: '/nucleos' },
    { title: 'Editar Núcleo', href: '#' },
];

const formatDate = (date: string | null) => {
    if (!date) return '';

    const [day, month, year] = date.split('/'); // Divide a string
    const parsedDate = new Date(`${year}-${month}-${day}`); // Formato YYYY-MM-DD

    return isNaN(parsedDate.getTime()) ? '' : parsedDate.toISOString().split('T')[0];
};

export default function Edit(props: Props<Nucleo>) {
    const { nucleo, session } = props;
    const { data: formData, setData, post, processing, errors } = useForm({
        nome: nucleo.nome,
        imagem: nucleo.imagem,
        descricao: nucleo.descricao.join('\n\n'),
        inicio_matricula: formatDate(nucleo.inicio_matricula),
        fim_matricula: formatDate(nucleo.fim_matricula),
        idade_minima: Number(nucleo.idade_minima),
        idade_maxima: Number(nucleo.idade_maxima)
    });

    const { processing: processingDeletion, delete: deleteNucleo } = useForm();

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('nucleos.update', nucleo.id)); // Envia a atualização para o backend
    };

    const submitDeletion = (e: React.FormEvent) => {
        e.preventDefault();
        if (confirm('Tem certeza que deseja excluir este núcleo?')) deleteNucleo(route('nucleos.destroy', nucleo.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='Editar Núcleo' />
            <Session session={session}  />

            <div className="w-full mx-auto p-6 rounded-lg shadow">
                <h1 className="text-xl font-bold mb-4">Editar Núcleo</h1>

                <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
                    
                    <InputImageContent value={formData.imagem} setData={setData} errors={errors} />

                    <div>
                        <label className="block font-medium">Nome</label>
                        <input
                            type="text"
                            required
                            value={formData.nome}
                            onChange={(e) => setData('nome', e.target.value)}
                            className="w-full p-2 border rounded-md"
                        />
                        <ErrorLabel error={errors.nome} />
                    </div>

                    <div>
                        <label className="block font-medium">Descrição</label>
                        <textarea
                            rows={5}
                            required
                            value={formData.descricao}
                            onChange={(e) => setData('descricao', e.target.value)}
                            className="w-full p-2 border rounded-md"
                        />
                        <ErrorLabel error={errors.descricao} />
                    </div>

                    <div className='flex flex-col gap-4'>
                        <p><b>Período de matrícula</b></p>
                        <div className="inline-flex gap-4">
                            <div className="relative w-full">
                                <label className="block font-medium text-white mb-2">Início</label>
                                <input
                                    type="date"
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light', // Corrige problema do modo escuro
                                    }}
                                    value={formatDate(formData.inicio_matricula)}
                                    onChange={(e) => setData('inicio_matricula', e.target.value)}
                                />
                                <CalendarIcon className="absolute right-3 top-1/2 transform pointer-events-none" />
                                <ErrorLabel error={errors.inicio_matricula} />
                            </div>

                            <div className="relative w-full">
                                <label className="block font-medium text-white mb-2">Fim</label>
                                <input
                                    type="date"
                                    min={formatDate(formData.inicio_matricula)}
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light', // Corrige problema do modo escuro
                                    }}
                                    value={formatDate(formData.fim_matricula)}
                                    onChange={(e) => setData('fim_matricula', e.target.value)}
                                />
                                <CalendarIcon className="absolute right-3 top-1/2 transform pointer-events-none" />
                                <ErrorLabel error={errors.fim_matricula} />
                            </div>
                        </div>
                    </div>

                    <div className='flex flex-col gap-4'>
                        <p><b>Público alvo</b></p>
                        <div className="inline-flex gap-4">
                            <IdadeInputToggle
                                label='Idade mínima'
                                column="idade_minima"
                                value={formData.idade_minima}
                                limite={0}
                                setData={setData}
                                error={errors.idade_minima}
                            />

                            <IdadeInputToggle
                                label='Idade máxima'
                                column="idade_maxima"
                                value={formData.idade_maxima}
                                limite={formData.idade_minima}
                                setData={setData}
                                error={errors.idade_maxima}
                            />
                        </div>
                    </div>

                    <div className="flex justify-end">
                        <button
                            type="submit"
                            className="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-md"
                            disabled={processing}
                        >
                            {processing ? 'Salvando...' : 'Salvar Alterações'}
                        </button>
                    </div>
                </form>
                <form onSubmit={submitDeletion} >
                    <div className="flex justify-end mt-4">
                        <button
                            type="submit"
                            className="cursor-pointer bg-red-500 text-white px-4 py-2 rounded-md"
                            disabled={processing}
                        >
                            {processingDeletion ? "Excluindo..." : "Excluir"}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
