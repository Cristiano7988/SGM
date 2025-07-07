import { ButtonSubmitContent } from "./button-submit-content";
import { InputImageContent } from "./input-image-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { InputTimeContent } from "./input-time-content";
import { InputUrlContent } from "./input-url-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { TextAreaContent } from "./text-area-content";

export function FormTurmaContent({ data, processing, submit, setData, errors, props }: any) {
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
                    array={props.nucleos}
                    setData={setData}
                    error={errors.nucleo_id}
                />

                <SelectModelContent
                    column="tipo_de_aula_id"
                    titulo="Tipo de aula"
                    id={data.tipo_de_aula_id}
                    array={props.tipos_de_aula}
                    setData={setData}
                    error={errors.tipo_de_aula_id}
                />
            </div>
            
            <div className="flex gap-4">
                <SelectModelContent
                    column="dia_id"
                    titulo="Dia"
                    id={data.dia_id}
                    array={props.dias}
                    setData={setData}
                    error={errors.dia_id}
                />

                <InputTimeContent
                    column="horario"
                    titulo="Horário"
                    value={data.horario}
                    setData={setData}
                    error={errors.horario}
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
                    />

                    <InputTextContent
                        column="zoom_senha"
                        titulo="Senha"
                        value={data.zoom_senha}
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
                        value={data.vagas_ofertadas}
                        setData={setData}
                        error={errors.vagas_ofertadas}
                        min={data.vagas_fora_do_site}
                    />

                    <InputNumberContent
                        titulo='Fora do site'
                        column='vagas_fora_do_site'
                        value={data.vagas_fora_do_site}
                        setData={setData}
                        error={errors.vagas_fora_do_site}
                        min={0}
                        max={data.vagas_ofertadas}
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
