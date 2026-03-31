import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Turma, FormContentProps, Nucleo } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputImageContent } from "./input-image-content";
import { TextAreaContent } from "./text-area-content";
import { InputDateContent } from "./input-date-content";
import IdadeInputToggle from "../idade-input-toggle";

export function FormNucleoContent({ initialData, endpoint, related }: FormContentProps<Nucleo>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Nucleo>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const turmas = edit ? data.turmas : [{ id: null }];

    const addTurma = () => {
        setData("turmas", [...turmas, { id: null }]);
    };

    const removeTurma = (index: number) => {
        const updatedTurmas = turmas.filter((u: any, i: number) => i !== index);

        setData("turmas", updatedTurmas);
        clearErrors(`turmas.${index}`);
        clearErrors("turmas");
    };

    const updateTurma = (index: number, id: number) => {
        const updatedTurmas = [...turmas];
        const turma = related.turmas.find((u: Turma) => u.id === id);
        updatedTurmas[index] = turma;

        setData("turmas", updatedTurmas);
        clearErrors(`turmas.${index}`);
        clearErrors("turmas");
    };

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <InputImageContent
                value={data.imagem}
                setData={setData}
                errors={errors}
            />

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={data.nome}
                setData={setData}
                clearErrors={clearErrors}
                error={errors.nome}
                required
            />

            <TextAreaContent
                column="descricao"
                titulo="Descrição"
                value={data.descricao}
                setData={setData}
                error={errors.descricao}
            />

            <hr />

            <div className='flex flex-col gap-4'>
                <h2 className="text-lg font-semibold">Período de Matrícula</h2>
                <div className='flex gap-4'>
                    <InputDateContent
                        column="inicio_matricula"
                        titulo="Início"
                        value={data.inicio_matricula}
                        setData={setData}
                        error={errors.inicio_matricula}
                        clearErrors={clearErrors}
                    />

                    <InputDateContent
                        column="fim_matricula"
                        titulo="Fim"
                        value={data.fim_matricula}
                        setData={setData}
                        error={errors.fim_matricula}
                        clearErrors={clearErrors}
                    />
                </div>
            </div>

            <hr />

            <div className='flex flex-col gap-4'>
                <h2 className="text-lg font-semibold">Público alvo</h2>
                <div className='flex gap-4'>
                    <IdadeInputToggle
                        label='Idade mínima'
                        column="idade_minima"
                        value={data.idade_minima}
                        limite={0}
                        setData={setData}
                        error={errors.idade_minima}
                    />

                    <IdadeInputToggle
                        label='Idade máxima'
                        column="idade_maxima"
                        value={data.idade_maxima}
                        limite={data.idade_minima}
                        setData={setData}
                        error={errors.idade_maxima}
                    />
                </div>
            </div>

            <hr />

            <h2 className="text-lg font-semibold">Turmas vinculados a este núcleo</h2>

            {turmas.map((turma: Turma, index: number) => (
                <div key={index + turma.id} className="flex items-center gap-2">

                    <SelectModelContent
                        column="turma"
                        id={turma.id}
                        array={related.turmas}
                        setData={(_: any, id: number) => updateTurma(index, id)}
                        error={errors[`turmas.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeTurma(index)}
                        />
                    )}
                </div>
            ))}

            {errors.turmas && <ErrorLabel error={errors.turmas} />}

            <hr />

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/turmas/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar nova turma"
                />

                <div
                    onClick={addTurma}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outra turma"
                />

                {!hasErrors && (
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Salvando..."
                        buttonText="Salvar"
                        classes="cursor-pointer bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                    />
                )}
            </div>
        </form>
    </>);
}