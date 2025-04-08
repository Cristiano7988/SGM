import CardPacote from '@/components/card-pacote';
import Session from '@/components/session';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, FiltrosPacote, Pacote, PacoteProps } from '@/types';
import { Switch } from '@headlessui/react';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pacotes',
        href: '/pacotes',
    },
];

export default function Index(props: PacoteProps) {
    const { pagination, session } = props;
    const searchParams = new URLSearchParams(location.search);
    const filtrosInicial: FiltrosPacote = {};

    if (searchParams.get('ativo')) filtrosInicial.ativo = Boolean(Number(searchParams.get('ativo')));
    if (searchParams.get('nucleoId')) filtrosInicial.nucleoId = Number(searchParams.get('nucleoId')) ?? undefined;

    const [filtros, setFiltros] = useState<FiltrosPacote>(filtrosInicial);
    const filtroEstaAtivo = ['ativo', 'nucleoId'].filter(prop => prop in filtrosInicial).length;
    const [mostrarFiltros, setMostrarFiltros] = useState(!!filtroEstaAtivo);
    const [filtrosHabilitados, setFiltrosHabilitados] = useState<FiltrosPacote>({
        ativo: Boolean(searchParams.get('ativo')),
        nucleoId: Boolean(searchParams.get('nucleoId')),
    });

    const handleFiltrosChange = (id: string, value:string | undefined = undefined ) => {
        const filtrosAtualizados = {
            ...filtros,
            [id]: value ?? !filtros[id] 
        }

        setFiltros(filtrosAtualizados);
    }

    const handleFiltro = (id: string, isBoolean = false) => {
        if (filtros.hasOwnProperty(id)) delete filtros[id];
        else if (!isBoolean) filtros[id] = undefined;
        else filtros[id] = Number(searchParams.get(id));
        
        setFiltros(filtros)
        setFiltrosHabilitados({
            ...filtrosHabilitados,
            [id]: !filtrosHabilitados[id]
        })
    }
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pacotes" />

            <Session session={session}  />

            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className='flex flex-wrap justify-between'>
                    <div className='flex gap-2 items-center'>
                        <Label>Filtros</Label>
                        <Switch
                            checked={mostrarFiltros}
                            onChange={(value) => setMostrarFiltros(value)}
                            className={` group relative flex h-7 w-14 cursor-pointer rounded-full ${mostrarFiltros ? "bg-blue-500" : "bg-white/10"} p-1 transition-colors duration-200 ease-in-out focus:outline-none data-[focus]:outline-1 data-[focus]:outline-white`}
                        >
                            <span
                                aria-hidden="true"
                                className="pointer-events-none inline-block size-5 translate-x-0 rounded-full bg-white ring-0 shadow-lg transition duration-200 ease-in-out group-data-[checked]:translate-x-7"
                            />
                        </Switch>

                    </div>
                    <div className="flex gap-2">
                        <Link href={route('pacotes.index', filtros)} className='bg-blue-500 px-4 py-2 rounded-md w-fit'>Filtrar</Link>
                        <Link href="#" className='bg-blue-500 border px-4 py-2 rounded-xl' children="Criar" />
                    </div>
                </div>

                {mostrarFiltros && <div className='flex flex-wrap gap-8 justify-between'>
                    <div className='flex flex-col gap-4 w-fit justify-between border rounded-lg p-4'>
                        <div className='flex gap-2 items-center py-2'>
                            <Checkbox
                                disabled={!filtrosHabilitados.ativo}
                                id="ativo"
                                name="ativo"
                                className='cursor-pointer'
                                checked={Boolean(filtros.ativo)}
                                onClick={(e) => handleFiltrosChange('ativo')}
                            />
                            <Label htmlFor="ativo" className='cursor-pointer'>Ativo</Label>
                        </div>
                        <Switch
                            checked={Boolean(filtrosHabilitados.ativo)}
                            onChange={(value) => handleFiltro('ativo', true)}
                            className={` group relative flex h-7 w-14 cursor-pointer rounded-full ${filtrosHabilitados.ativo ? "bg-blue-500" : "bg-white/10"} p-1 transition-colors duration-200 ease-in-out focus:outline-none data-[focus]:outline-1 data-[focus]:outline-white`}
                        >
                            <span
                                aria-hidden="true"
                                className="pointer-events-none inline-block size-5 translate-x-0 rounded-full bg-white ring-0 shadow-lg transition duration-200 ease-in-out group-data-[checked]:translate-x-7"
                            />
                        </Switch>
                    </div>
                    
                    <div className='flex flex-col gap-4 w-fit justify-between border rounded-lg p-4'>
                        <div className='flex gap-2 items-center'>
                            <Label htmlFor="nucleoId" className={filtrosHabilitados.nucleoId ? "" : "opacity-50"}>Núcleo:</Label>
                            <Select
                                name="nucleoId"
                                disabled={!filtrosHabilitados.nucleoId}
                                onValueChange={(value) => handleFiltrosChange('nucleoId', value)}
                                defaultValue={String(filtros.nucleoId)}
                            >
                                <SelectTrigger className="w-auto gap-2 min-w-56 cursor-pointer">
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
                        <Switch
                            id="nucleoId"
                            checked={Boolean(filtrosHabilitados.nucleoId)}
                            onChange={(value) => handleFiltro('nucleoId')}
                            className={`group relative flex h-7 w-14 cursor-pointer rounded-full ${filtrosHabilitados.nucleoId ? "bg-blue-500" : "bg-white/10"} p-1 transition-colors duration-200 ease-in-out focus:outline-none data-[focus]:outline-1 data-[focus]:outline-white`}
                        >
                            <span
                                aria-hidden="true"
                                className="pointer-events-none inline-block size-5 translate-x-0 rounded-full bg-white ring-0 shadow-lg transition duration-200 ease-in-out group-data-[checked]:translate-x-7"
                            />
                        </Switch>
                    </div>
                </div>}

                {pagination.data.length
                        ? <div className="flex flex-wrap justify-between gap-4">
                            {pagination.data.map((pacote: Pacote) => <CardPacote key={pacote.id} pacote={pacote} />)}
                        </div>
                    : <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex flex-col justify-center items-center overflow-hidden rounded-xl border md:min-h-min">
                        <div className="m-auto">Sem resultados</div>
                    </div>
                  }
            </div>
        </AppLayout>
    );
}
