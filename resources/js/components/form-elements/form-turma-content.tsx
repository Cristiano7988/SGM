import { Aula, FormContentProps, Turma } from "@/types/models";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputImageContent } from "./input-image-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { InputUrlContent } from "./input-url-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { TextAreaContent } from "./text-area-content";
import { useForm } from "@inertiajs/react";
import { FormProps } from "@/types";
import { Unlink } from "lucide-react";
import ErrorLabel from "../error-label";
import { InputTimeContent } from "./input-time-content";
import { useEffect, useState } from "react";

export function FormTurmaContent({ initialData, endpoint, related }: FormContentProps<Turma>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Turma>>(initialData);
    const edit = location.pathname.includes("edit");
    const aulaInicial = { horario: "00:00", dia_id: null};
    const [aulas, setAulas] = useState(edit ? data.aulas : [aulaInicial]);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    useEffect(() => {
        setData("aulas", aulas);
    }, [aulas]);

    const addAula = () => setAulas([...aulas, aulaInicial]);

    const removeAula = (index: number) => {
        delete aulas[index];

        setAulas(aulas.filter(Boolean));
        clearErrors(`aulas.${index}`);
        clearErrors("aulas");
    };
    
    const updateHorario = (index: number, value: string) => {
        aulas[index].horario = value;

        setAulas(aulas);
        clearErrors(`aulas.${index}`);
        clearErrors("aulas");
    };

    const updateDia = (index: number, value: number) => {
        aulas[index].dia_id = value;

        setData("aulas", aulas);
        clearErrors(`aulas.${index}`);
        clearErrors("aulas");
    };

    return (
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
                error={errors.nome}
                clearErrors={clearErrors}
            />

            <TextAreaContent
                column="descricao"
                titulo="Descrição"
                value={data.descricao}
                setData={setData}
                error={errors.descricao}
            />

            <div className="flex gap-4">
                <SelectModelContent
                    column="nucleo_id"
                    titulo="Núcleos"
                    id={data.nucleo_id}
                    array={related.nucleos}
                    setData={setData}
                    error={errors.nucleo_id}
                />
            </div>

            <SwitchContent
                column="disponivel"
                titulo="Disponível"
                tituloInativo="Indisponível"
                value={data.disponivel}
                setData={setData}
                error={errors.disponivel}
            />

            <div className="inline-flex gap-4">
                <InputUrlContent
                    column="whatsapp"
                    titulo="WhatsApp"
                    value={data.whatsapp}
                    setData={setData}
                    error={errors.whatsapp}
                />

                <InputUrlContent
                    column="spotify"
                    titulo="Spotify"
                    value={data.spotify}
                    setData={setData}
                    error={errors.spotify}
                />
            </div>

            <div className='flex flex-col gap-4'>
                <p><b>Zoom</b></p>
                <InputUrlContent
                    column="zoom"
                    titulo="Link"
                    value={data.zoom}
                    setData={setData}
                    error={errors.zoom}
                />

                <div className="inline-flex gap-4">

                    <InputTextContent
                        column="zoom_id"
                        titulo="ID"
                        value={data.zoom_id}
                        setData={setData}
                        error={errors.zoom_id}
                        clearErrors={clearErrors}
                    />

                    <InputTextContent
                        column="zoom_senha"
                        titulo="Senha"
                        value={data.zoom_senha}
                        setData={setData}
                        error={errors.zoom_senha}
                        clearErrors={clearErrors}
                    />
                </div>
            </div>

            <div className='flex flex-col gap-4'>
                <p><b>Vagas</b></p>
                <div className="inline-flex gap-4">
                    <InputNumberContent
                        titulo='Ofertadas'
                        column='vagas_ofertadas'
                        value={data.vagas_ofertadas}
                        setData={setData}
                        error={errors.vagas_ofertadas}
                        clearErrors={clearErrors}
                    />
                </div>
            </div>

            <hr />

            <h2 className="text-lg font-semibold">Aulas agendadas para esta turma</h2>

            {aulas.map((aula: Aula, index: number) => (<div key={index} className="flex gap-2">
                <SelectModelContent
                    column="dia_id"
                    titulo="Dia"
                    id={aula.dia_id}
                    array={related.dias}
                    setData={(column: string, dia_id: number) => updateDia(index, dia_id)}
                    error={errors[`aulas.${index}.dia_id`]}
                />
                <InputTimeContent
                    column="horario"
                    titulo="Horário"
                    value={aula.horario}
                    setData={(column: string, value: string) => updateHorario(index, value)}
                    error={errors[`aulas.${index}.horario`]}
                />
                <div className="flex items-center gap-2 w-full">
                    <Unlink
                        className="cursor-pointer text-red-500 hover:text-red-700"
                        onClick={() => removeAula(index)}
                    />
                </div>
            </div>))}

            {errors.aulas && <ErrorLabel error={errors.aulas} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <div
                    onClick={addAula}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Adicionar aula"
                />

                {!hasErrors && <ButtonSubmitContent
                    processing={processing}
                    processingText="Salvando..."
                    buttonText="Salvar"
                    classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                />}
            </div>
        </form>
    );
}
