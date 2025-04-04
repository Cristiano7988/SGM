import { ButtonSubmitContent } from "./button-submit-content";
import { InputImageContent } from "./input-image-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { InputTimeContent } from "./input-time-content";
import { InputUrlContent } from "./input-url-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { TextAreaContent } from "./text-area-content";

interface FormTurmaContentProps {
    formData: any;
    processing: boolean;
    submit: (e: React.FormEvent) => void;
    setData: (key: string, value: any) => void;
    errors: any;
    props: any;
}

export function FormTurmaContent({ formData, processing, submit, setData, errors, props }: FormTurmaContentProps) {
    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <InputImageContent
                value={formData.imagem}
                setData={setData}
                errors={errors}
            />

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={formData.nome}
                setData={setData}
                error={errors.nome}
            />

            <TextAreaContent
                column="descricao"
                titulo="Descrição"
                value={formData.descricao}
                setData={setData}
                error={errors.descricao}
            />

            <div className="flex gap-4">
                <SelectModelContent
                    column="nucleo_id"
                    titulo="Núcleos"
                    id={formData.nucleo_id}
                    array={props.nucleos}
                    setData={setData}
                    error={errors.nucleo_id}
                />

                <SelectModelContent
                    column="tipo_de_aula_id"
                    titulo="Tipo de aula"
                    id={formData.tipo_de_aula_id}
                    array={props.tipos_de_aula}
                    setData={setData}
                    error={errors.tipo_de_aula_id}
                />
            </div>
            
            <div className="flex gap-4">
                <SelectModelContent
                    column="dia_id"
                    titulo="Dia"
                    id={formData.dia_id}
                    array={props.dias}
                    setData={setData}
                    error={errors.dia_id}
                />

                <InputTimeContent
                    column="horario"
                    titulo="Horário"
                    value={formData.horario}
                    setData={setData}
                    error={errors.horario}
                />
            </div>

            <SwitchContent
                column="disponivel"
                titulo="Disponível"
                tituloInativo="Indisponível"
                value={formData.disponivel}
                setData={setData}
                error={errors.disponivel}
            />

            <div className="inline-flex gap-4">
                <InputUrlContent
                    column="whatsapp"
                    titulo="WhatsApp"
                    value={formData.whatsapp}
                    setData={setData}
                    error={errors.whatsapp}
                />

                <InputUrlContent
                    column="spotify"
                    titulo="Spotify"
                    value={formData.spotify}
                    setData={setData}
                    error={errors.spotify}
                />
            </div>

            <div className='flex flex-col gap-4'>
                <p><b>Zoom</b></p>
                <InputUrlContent
                    column="zoom"
                    titulo="Link"
                    value={formData.zoom}
                    setData={setData}
                    error={errors.zoom}
                />

                <div className="inline-flex gap-4">

                    <InputTextContent
                        column="zoom_id"
                        titulo="ID"
                        value={formData.zoom_id}
                        setData={setData}
                        error={errors.zoom_id}
                    />

                    <InputTextContent
                        column="zoom_senha"
                        titulo="Senha"
                        value={formData.zoom_senha}
                        setData={setData}
                        error={errors.zoom_senha}
                    />
                </div>
            </div>

            <div className='flex flex-col gap-4'>
                <p><b>Vagas</b></p>
                <div className="inline-flex gap-4">
                    <InputNumberContent
                        titulo='Ofertadas'
                        column='vagas_ofertadas'
                        value={formData.vagas_ofertadas}
                        setData={setData}
                        error={errors.vagas_ofertadas}
                        min={formData.vagas_fora_do_site}
                    />

                    <InputNumberContent
                        titulo='Fora do site'
                        column='vagas_fora_do_site'
                        value={formData.vagas_fora_do_site}
                        setData={setData}
                        error={errors.vagas_fora_do_site}
                        min={0}
                        max={formData.vagas_ofertadas}
                    />
                </div>
            </div>

            <ButtonSubmitContent
                processing={processing}
                processingText="Salvando..."
                buttonText="Salvar"
                classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
            />
        </form>
    );
}
