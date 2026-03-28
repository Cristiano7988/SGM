import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { FormContentProps, Pacote, Data } from "@/types/models";
import { FormProps } from "@/types";
import { Unlink } from "lucide-react";
import ErrorLabel from "../error-label";
import { InputDateContent } from "./input-date-content";
import { useEffect, useState } from "react";

export function FormPacoteContent({ initialData, endpoint, related }: FormContentProps<Pacote>) {
    const { data: dataForm, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Pacote>>(initialData);
    const edit = location.pathname.includes("edit");
    const [dia] = new Date().toISOString().split('T');
    const dataInicial = { id: null, dia, dia_formatado: dia, pacote_id: null };
    const [datas, setDatas] = useState([dataInicial]);

    useEffect(() => {
        setDatas(edit ? dataForm.datas : [dataInicial]);
    }, []);

    useEffect(() => {
        setData("datas", datas);
    }, [datas]);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };
    
    const addData = () => setDatas([...datas, dataInicial]);

    const removeData = (index: number) => {
        delete datas[index];

        setDatas(datas.filter(Boolean));
        clearErrors(`datas.${index}`);
        clearErrors("datas");
    };

    const updateData = (index: number, value: string) => {
        const newDatas = [...datas];
        newDatas[index] = { ...newDatas[index], dia: value };

        setDatas(newDatas);
        clearErrors(`datas.${index}`);
        clearErrors("datas");
    };

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <InputTextContent
                column="nome"
                titulo="Nome"
                value={dataForm.nome}
                setData={setData}
                error={errors.nome}
                clearErrors={clearErrors}
            />

            <SelectModelContent
                column="turma_id"
                titulo="Turmas"
                id={dataForm.turma_id}
                array={related.turmas}
                setData={setData}
                error={errors.turma_id}
            />

            <SwitchContent
                column="ativo"
                titulo="Ativo"
                tituloInativo="Inativo"
                value={dataForm.ativo}
                setData={setData}
                error={errors.ativo}
            />

            <InputNumberContent
                titulo='Valor'
                column='valor'
                value={dataForm.valor}
                setData={setData}
                error={errors.valor}
                clearErrors={clearErrors}
                min={0}
                max={2000}
            />

            <hr />

            <h2 className="text-lg font-semibold">Aulas vinculadas a este pacote</h2>

            {datas.map((data: Data, index: number) => (
                <div key={index} className="flex items-center gap-2">
                    <InputDateContent
                        column="datas"
                        titulo="Data"
                        value={data.dia}
                        setData={(_: any, value: string) => updateData(index, value)}
                        error={errors[`datas.${index}`]}
                        clearErrors={clearErrors}
                    />

                    <Unlink
                        className="cursor-pointer text-red-500 hover:text-red-700"
                        onClick={() => removeData(index)}
                    />
                </div>
            ))}

            {errors.datas && <ErrorLabel error={errors.datas} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/datas/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar nova data"
                />

                <div
                    onClick={addData}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outra data"
                />

                {!hasErrors && (
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Salvando..."
                        buttonText="Salvar"
                        classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                    />
                )}
            </div>
        </form>
    );
}
