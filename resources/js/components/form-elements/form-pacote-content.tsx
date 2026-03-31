import { useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { FormContentProps, Pacote, Aula } from "@/types/models";
import { FormProps } from "@/types";
import { Unlink } from "lucide-react";
import ErrorLabel from "../error-label";
import { InputDateContent } from "./input-date-content";
import { useEffect, useState } from "react";
import { InputTimeContent } from "./input-time-content";

export function FormPacoteContent({ initialData, endpoint, related }: FormContentProps<Pacote>) {
    const { data: dataForm, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Pacote>>(initialData);
    const edit = location.pathname.includes("edit");
    const [dia] = new Date().toISOString().split('T');
    const aulaInicial = { id: null, dia, dia_formatado: dia, dia_da_semana: "", horario: "00:00", pacote_id: null };
    const [aulas, setAulas] = useState([aulaInicial]);

    useEffect(() => {
        setAulas(edit ? dataForm.aulas : [aulaInicial]);
    }, []);

    useEffect(() => {
        setData("aulas", aulas);
        clearErrors("aulas");
    }, [aulas]);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };
    
    const addAula = () => setAulas([...aulas, aulas.length ? aulas[aulas.length - 1] : aulaInicial]);

    const removeAula = (index: number) => {
        delete aulas[index];

        setAulas(aulas.filter(Boolean));
        clearErrors(`aulas.${index}`);
    };

    const updateDia = (index: number, value: string) => {
        const newAulas = [...aulas];
        newAulas[index] = { ...newAulas[index], dia: value };

        setAulas(newAulas);
        clearErrors(`aulas.${index}.dia`);
    };

    const updateHorario = (index: number, value: string) => {
        const newAulas = [...aulas];
        newAulas[index] = { ...newAulas[index], horario: value };

        setAulas(newAulas);
        clearErrors(`aulas.${index}.horario`);
    };

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <div className="flex gap-2">
                <InputTextContent
                    column="nome"
                    titulo="Nome"
                    value={dataForm.nome}
                    setData={setData}
                    error={errors.nome}
                    clearErrors={clearErrors}
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
            </div>

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

            <hr />

            <h2 className="text-lg font-semibold">Aulas deste pacote</h2>

            {aulas.map((aula: Aula, index: number) => (
                <div key={index} className="flex items-center gap-2">
                    <InputDateContent
                        column="dia"
                        value={aula.dia}
                        min={dia}
                        setData={(_: any, value: string) => updateDia(index, value)}
                        error={errors[`aulas.${index}.dia`]}
                        clearErrors={clearErrors}
                    />
                    <InputTimeContent
                        column="horario"
                        value={aula.horario}
                        setData={(_: any, value: string) => updateHorario(index, value)}
                        error={errors[`aulas.${index}.horario`]}
                    />
                    <Unlink
                        className="cursor-pointer text-red-500 hover:text-red-700"
                        onClick={() => removeAula(index)}
                    />
                </div>
            ))}

            {errors.aulas && <ErrorLabel error={errors.aulas} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <div
                    onClick={addAula}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outra aula"
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
