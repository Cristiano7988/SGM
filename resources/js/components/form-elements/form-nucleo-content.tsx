import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Turma, FormContentProps, Nucleo, Pacote } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputImageContent } from "./input-image-content";
import { TextAreaContent } from "./text-area-content";
import { InputDateContent } from "./input-date-content";
import IdadeInputToggle from "../idade-input-toggle";

export function FormNucleoContent({ initialData, endpoint, related }: FormContentProps<Nucleo>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post } = useForm<FormProps<Nucleo>>(initialData);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(endpoint);
    };

    const turmas = data.turmas.length ? data.turmas : [{ id: 0 }];

    const addTurma = () => {
        setData("turma", [...turmas, { id: 0 }]);
    };

    const removeTurma = (index: number) => {
        const updatedTurmas = [...turmas];
        updatedTurmas.splice(index, 1);
        setData("turma", updatedTurmas);
        clearErrors(`turma.${index}`);
        clearErrors("turma");
    };

    const updateTurma = (index: number, id: number) => {
        const turma = related.turmas.find((u: Turma) => u.id === id);
        const updatedTurmas = [...turmas];
        updatedTurmas[index] = turma;
        setData("turma", updatedTurmas);
        clearErrors(`turma.${index}`);
        clearErrors("turma");
    };

    const pacotes = data.pacotes.length ? data.pacotes : [{ id: 0 }];

    const addPacote = () => {
        setData("pacote", [...pacotes, { id: 0 }]);
    };

    const removePacote = (index: number) => {
        const updatedPacotes = [...pacotes];
        updatedPacotes.splice(index, 1);
        setData("pacote", updatedPacotes);
        clearErrors(`pacote.${index}`);
        clearErrors("pacote");
    };

    const updatePacote = (index: number, id: number) => {
        const pacote = related.pacotes.find((u: Pacote) => u.id === id);
        const updatedPacotes = [...pacotes];
        updatedPacotes[index] = pacote;
        setData("pacote", updatedPacotes);
        clearErrors(`pacote.${index}`);
        clearErrors("pacote");
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
                <div key={index} className="flex items-center gap-2">

                    <SelectModelContent
                        column="turma"
                        titulo={`Turma ${index + 1}`}
                        id={turma?.id}
                        array={related.turmas}
                        setData={(_: any, id: number) => updateTurma(index, id)}
                        error={errors[`turma.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeTurma(index)}
                        />
                    )}
                </div>
            ))}

            {errors.turma && <ErrorLabel error={errors.turma} />}

            <hr />

            <h2 className="text-lg font-semibold">Pacotes vinculados a este núcleo</h2>

            {pacotes.map((pacote: Pacote, index: number) => (
                <div key={index} className="flex items-center gap-2">

                    <SelectModelContent
                        column="pacote"
                        titulo={`Pacote ${index + 1}`}
                        id={pacote?.id}
                        array={related.pacotes}
                        setData={(_: any, id: number) => updatePacote(index, id)}
                        error={errors[`pacote.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removePacote(index)}
                        />
                    )}
                </div>
            ))}

            {errors.pacote && <ErrorLabel error={errors.pacote} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/turma/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo turma"
                />

                <div
                    onClick={addTurma}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outro turma"
                />

                <Link
                    href="/pacote/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo pacote"
                />

                <div
                    onClick={addPacote}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outro pacote"
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