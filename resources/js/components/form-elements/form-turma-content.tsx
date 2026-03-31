import { FormContentProps, Turma } from "@/types/models";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputImageContent } from "./input-image-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { InputUrlContent } from "./input-url-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { TextAreaContent } from "./text-area-content";
import { Link, useForm } from "@inertiajs/react";
import { FormProps } from "@/types";

export function FormTurmaContent({ initialData, endpoint, related }: FormContentProps<Turma>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Turma>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
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

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/pacotes/create"
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar pacote de aulas"
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
