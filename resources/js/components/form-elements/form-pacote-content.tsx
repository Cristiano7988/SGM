import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { FormContentProps, Pacote, Aula, Turma, } from "@/types/models";
import { FormProps } from "@/types";
import ErrorLabel from "../error-label";
import { useEffect, useState } from "react";
import { FormAulaContent } from "./form-aula-content";

export function FormPacoteContent({ initialData, endpoint, related }: FormContentProps<Pacote>) {
    const { data: dataForm, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Pacote>>(initialData);
    const edit = location.pathname.includes("edit");
    const [aulaInicial] = initialData.aulas
    const [aulas, setAulas] = useState([aulaInicial]);

    useEffect(() => {
        setAulas(edit ? dataForm.aulas : []);
    }, []);

    useEffect(() => {
        setData("aulas", aulas);
        clearErrors("aulas");

        const total = aulas.reduce((soma, aula) => soma + aula.valor, 0);
        setData("valor", total);
        setData("valor_formatado", total.toFixed(2).replace(".", ","));
    }, [aulas]);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };
    
    const addAula = () => setAulas([...aulas, aulas.length ? aulas[aulas.length - 1] : aulaInicial]);

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
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

            {!!aulas.length && <>
                <hr />

                <h2 style={{ margin: "unset" }} className="text-lg font-semibold">Aulas deste pacote</h2>

                {aulas.map((aula: Aula, index: number) => (
                    <FormAulaContent
                        key={index}
                        aula={aula}
                        index={index}
                        turma={related.turmas.find((t: Turma) => t.id === dataForm.turma_id)}
                        setAulas={setAulas}
                        dataForm={dataForm}
                        errors={errors}
                        clearErrors={clearErrors}
                        related={related}
                    />
                ))}
                
                {!!dataForm.valor && <div className="bg-gray-100 dark:bg-gray-900 p-4 gap-2 flex flex-col">
                    <h3>Total:</h3>
                    <p><b>Valor:</b> {dataForm.valor_formatado}</p>
                </div>}
            </>}

            {errors.aulas && <ErrorLabel error={errors.aulas} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/turmas/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar turma"
                />
                {dataForm.turma_id && <div
                    onClick={addAula}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular aula"
                />}

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
