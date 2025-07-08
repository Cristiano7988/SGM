import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link } from '@inertiajs/react';
import { useState } from 'react';
import { Switch } from '@headlessui/react';
import { FiltrosType } from '@/types';

export default function Filtros({ dados, tabela }: { dados: FiltrosType[], tabela: String}) {
    const [filtros, setFiltros] = useState<FiltrosType[]>(dados);
    const filtroEstaAtivo = filtros.filter((filtro: FiltrosType) => filtro.ativo).length;
    const [mostrarFiltros, setMostrarFiltros] = useState(!!filtroEstaAtivo);
    
    const handleFiltrosChange = (nome: string, valor:string | boolean | undefined = undefined ) => {
        
        const novosFiltros = filtros.map((filtro: FiltrosType) => {
            if (filtro.nome == nome) filtro.valor = valor ?? undefined;
            return filtro;
        })

        setFiltros(novosFiltros);
    }

    const handleSwitch = (nome: string) => {
        const novosFiltros = filtros.map((filtro: FiltrosType) => {
            const ativo = filtro.nome == nome
                ? !filtro.ativo
                : filtro.ativo;
            return {
                ...filtro,
                ativo
            };
        });  

        setFiltros(novosFiltros);
    }

    return <>
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
                <Link href={route(tabela + '.index', filtros.map((filtro: FiltrosType) => ({ [filtro.nome]: filtro.ativo && mostrarFiltros ? filtro.valor : undefined })))} className='bg-blue-500 px-4 py-2 rounded-md w-fit'>Filtrar</Link>
                <Link href={route(tabela + '.create')} className='bg-blue-500 border px-4 py-2 rounded-xl' children="Criar" />
            </div>
        </div>

        {mostrarFiltros && <div className='flex flex-wrap gap-8 justify-between'>
            {filtros.map((filtro: FiltrosType) => <div key={filtro.nome}>

            {filtro.tipo == 'select' &&
                <div className='flex flex-col gap-4 w-fit justify-between border rounded-lg p-4'>
                    <div className='flex gap-2 items-center'>
                        <Label htmlFor={filtro.nome} className={filtro.ativo ? "" : "opacity-50"}>{filtro.label}:</Label>
                        <Select
                            name={filtro.nome}
                            disabled={!filtro.ativo}
                            onValueChange={(value) => handleFiltrosChange(filtro.nome, value)}
                            defaultValue={String(filtro.valor)}
                        >
                            <SelectTrigger className="w-auto gap-2 min-w-56 cursor-pointer">
                                <SelectValue placeholder="Filtrar" />
                            </SelectTrigger>
                            <SelectContent>
                                {filtro.opcoes?.map((opcao: any) => <SelectItem
                                    key={opcao.id}
                                    value={opcao.id.toString()}
                                    children={opcao.nome ?? opcao.tipo}
                                /> )}
                            </SelectContent>
                        </Select>
                    </div>
                    <Switch
                        id={filtro.nome}
                        checked={Boolean(filtro.ativo)}
                        onChange={(value) => handleSwitch(filtro.nome)}
                        className={`group relative flex h-7 w-14 cursor-pointer rounded-full ${filtro.ativo ? "bg-blue-500" : "bg-white/10"} p-1 transition-colors duration-200 ease-in-out focus:outline-none data-[focus]:outline-1 data-[focus]:outline-white`}
                    >
                        <span
                            aria-hidden="true"
                            className="pointer-events-none inline-block size-5 translate-x-0 rounded-full bg-white ring-0 shadow-lg transition duration-200 ease-in-out group-data-[checked]:translate-x-7"
                        />
                    </Switch>
                </div>}

            {filtro.tipo == 'boolean' &&
                <div className='flex flex-wrap gap-8 justify-between'>
                    <div className='flex flex-col gap-4 w-fit justify-between border rounded-lg p-4'>
                        <div className='flex gap-2 items-center py-2'>
                            <Checkbox
                                disabled={!filtro.ativo}
                                id={filtro.nome}
                                name={filtro.nome}
                                className='cursor-pointer'
                                checked={Boolean(Number(filtro.valor))}
                                onClick={(e) => handleFiltrosChange(filtro.nome, !filtro.valor)}
                            />
                            <Label htmlFor={filtro.nome} className='cursor-pointer'>{filtro.label}</Label>
                        </div>
                        <Switch
                            checked={Boolean(filtro.ativo)}
                            onChange={(value) => handleSwitch(filtro.nome)}
                            className={` group relative flex h-7 w-14 cursor-pointer rounded-full ${filtro.ativo ? "bg-blue-500" : "bg-white/10"} p-1 transition-colors duration-200 ease-in-out focus:outline-none data-[focus]:outline-1 data-[focus]:outline-white`}
                        >
                            <span
                                aria-hidden="true"
                                className="pointer-events-none inline-block size-5 translate-x-0 rounded-full bg-white ring-0 shadow-lg transition duration-200 ease-in-out group-data-[checked]:translate-x-7"
                            />
                        </Switch>
                    </div>
                </div>}

            </div>)}
        </div>}
    </>
}
