import { Head, useForm } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { Turma, BreadcrumbItem, Props } from '@/types';
import ImageInputToggle from '@/components/image-input-toggle';
import Session from '@/components/session';
import ErrorLabel from '@/components/error-label';
import { Switch } from '@headlessui/react';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

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

    const toggleInputMode = (value: boolean) => setData('disponivel', value)

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

                <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
                    
                    <ImageInputToggle value={formData.imagem} setData={setData} errors={errors} />

                    <div className='flex flex-col gap-4'>
                        <Label htmlFor="nome" className='block font-medium text-white'>Nome</Label>
                        <input
                            type="text"
                            name="nome"
                            required
                            value={formData.nome}
                            onChange={(e) => setData('nome', e.target.value)}
                            className="w-full p-2 border rounded-md"
                        />
                        <ErrorLabel error={errors.nome} />
                    </div>

                    <div className='flex flex-col gap-4'>
                        <Label htmlFor="descricao" className='block font-medium text-white'>Descrição</Label>
                        <textarea
                            name="descricao"
                            rows={5}
                            required
                            value={formData.descricao}
                            onChange={(e) => setData('descricao', e.target.value)}
                            className="w-full p-2 border rounded-md"
                        />
                        <ErrorLabel error={errors.descricao} />
                    </div>

                    <div className="flex gap-4">
                        <div className='flex flex-col w-full gap-4'>
                            <Label htmlFor="nucleo_id" className='block font-medium text-white'>Núcleo</Label>
                            <Select
                                onValueChange={(value) => setData('nucleo_id', Number(value))}
                                defaultValue={formData.nucleo_id.toString()}
                            >
                                <SelectTrigger className="gap-2 min-w-56 cursor-pointer h-12">
                                    <SelectValue placeholder="Filtrar" />
                                </SelectTrigger>
                                <SelectContent>
                                    {props.nucleos.map(nucleo => <SelectItem
                                        key={nucleo.id}
                                        value={nucleo.id.toString()}
                                        children={nucleo.nome}
                                    /> )}
                                </SelectContent>
                            </Select>
                        </div>
                        <div className='flex flex-col w-full gap-4'>
                            <Label htmlFor="tipo_de_aula_id" className='block font-medium text-white'>Tipo de Aula</Label>
                            <Select
                                onValueChange={(value) => setData('tipo_de_aula_id', Number(value))}
                                defaultValue={formData.tipo_de_aula_id.toString()}
                            >
                                <SelectTrigger className="gap-2 min-w-56 cursor-pointer h-12">
                                    <SelectValue placeholder="Filtrar" />
                                </SelectTrigger>
                                <SelectContent>
                                    {props.tipos_de_aula.map(tipo_de_aula => <SelectItem
                                        key={tipo_de_aula.id}
                                        value={tipo_de_aula.id.toString()}
                                        children={tipo_de_aula.tipo}
                                    /> )}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>
                    
                    <div className="flex gap-4">
                        <div className='flex flex-col w-full gap-4'>
                            <Label htmlFor="dia_id" className='block font-medium text-white'>Dia</Label>
                            <Select
                                onValueChange={(value) => setData('dia_id', Number(value))}
                                defaultValue={formData.dia_id.toString()}
                            >
                                <SelectTrigger name='dia_id' className="gap-2 min-w-56 cursor-pointer h-12">
                                    <SelectValue placeholder="Filtrar" />
                                </SelectTrigger>
                                <SelectContent>
                                    {props.dias.map(dia => <SelectItem
                                        key={dia.id}
                                        value={dia.id.toString()}
                                        children={dia.nome}
                                    /> )}
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="relative w-full flex flex-col gap-4">
                            <Label htmlFor="horario" className='block font-medium text-white'>Horário</Label>
                            <input
                                type="time"
                                name='horario'
                                pattern="^([01]\d|2[0-3]):([0-5]\d)$"
                                title="Formato válido: HH:MM"
                                className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                style={{
                                    colorScheme: 'light',
                                }}
                                value={formData.horario}
                                onChange={(e) => setData('horario', e.target.value)}
                            />
                            <ErrorLabel error={errors.horario} />
                        </div>
                    </div>

                    <div className="flex items-center space-x-4">
                        <Label htmlFor="disponivel" className='block font-medium text-white'>{formData.disponivel ? 'Disponível' : 'Indisponível'}</Label>
                        <Switch
                            name='disponivel'
                            checked={formData.disponivel}
                            onChange={toggleInputMode}
                            className={`${formData.disponivel ? 'bg-blue-500' : 'bg-gray-300'} cursor-pointer relative inline-flex items-center h-6 rounded-full w-11`}
                        >
                            <span
                                className={`${formData.disponivel ? 'translate-x-6' : 'translate-x-1'} inline-block w-4 h-4 transform bg-white rounded-full transition`}
                            />
                        </Switch>
                    </div>

                    <div className="inline-flex gap-4">
                        <div className="relative w-full flex flex-col gap-4">
                            <Label htmlFor="whatsapp" className='block font-medium text-white'>Whatsapp</Label>
                            <input
                                type="url"
                                name='whatsapp'
                                className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                style={{
                                    colorScheme: 'light',
                                }}
                                value={formData.whatsapp}
                                onChange={(e) => setData('whatsapp', e.target.value)}
                            />
                            <ErrorLabel error={errors.whatsapp} />
                        </div>

                        <div className="relative w-full flex flex-col gap-4">
                            <Label htmlFor="spotify" className='block font-medium text-white'>Spotify</Label>
                            <input
                                type="url"
                                name="spotify"
                                className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                style={{
                                    colorScheme: 'light',
                                }}
                                value={formData.spotify}
                                onChange={(e) => setData('spotify', e.target.value)}
                            />
                            <ErrorLabel error={errors.spotify} />
                        </div>
                    </div>

                    <div className='flex flex-col gap-4'>
                        <p><b>Zoom</b></p>
                        <div className="relative w-full flex flex-col gap-4">
                            <Label htmlFor="zoom" className='block font-medium text-white'>Link</Label>
                            <input
                                type="url"
                                name='zoom'
                                className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                style={{
                                    colorScheme: 'light',
                                }}
                                value={formData.zoom}
                                onChange={(e) => setData('zoom', e.target.value)}
                            />
                            <ErrorLabel error={errors.zoom} />
                        </div>
                        <div className="inline-flex gap-4">
                            <div className="relative w-full flex flex-col gap-4">
                                <Label htmlFor="zoom_id" className='block font-medium text-white'>ID</Label>
                                <input
                                    type="text"
                                    name='zoom_id'
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light',
                                    }}
                                    value={formData.zoom_id}
                                    onChange={(e) => setData('zoom_id', e.target.value)}
                                />
                                <ErrorLabel error={errors.zoom_id} />
                            </div>

                            <div className="relative w-full flex flex-col gap-4">
                                <Label htmlFor="zoom_senha" className='block font-medium text-white'>Senha</Label>
                                <input
                                    type="text"
                                    name="zoom_senha"
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light',
                                    }}
                                    value={formData.zoom_senha}
                                    onChange={(e) => setData('zoom_senha', e.target.value)}
                                />
                                <ErrorLabel error={errors.zoom_senha} />
                            </div>
                        </div>
                    </div>

                    <div className='flex flex-col gap-4'>
                        <p><b>Vagas</b></p>
                        <div className="inline-flex gap-4">
                            <div className="relative w-full flex flex-col gap-4">
                                <Label htmlFor="vagas_ofertadas" className='block font-medium text-white'>Ofertadas</Label>
                                <input
                                    type="number"
                                    name="vagas_ofertadas"
                                    min={formData.vagas_fora_do_site}
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light',
                                    }}
                                    value={formData.vagas_ofertadas}
                                    onChange={(e) => setData('vagas_ofertadas', Number(e.target.value))}
                                />
                                <ErrorLabel error={errors.vagas_ofertadas} />
                            </div>

                            <div className="relative w-full flex flex-col gap-4">
                                <Label htmlFor="vagas_fora_do_site" className='block font-medium text-white'>Fora do site</Label>
                                <input
                                    type="number"
                                    name="vagas_fora_do_site"
                                    max={formData.vagas_ofertadas}
                                    min="0"
                                    className="w-full p-3 border rounded-lg bg-wheat focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    style={{
                                        colorScheme: 'light',
                                    }}
                                    value={formData.vagas_fora_do_site}
                                    onChange={(e) => setData('vagas_fora_do_site', Number(e.target.value))}
                                />
                                <ErrorLabel error={errors.vagas_fora_do_site} />
                            </div>
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
