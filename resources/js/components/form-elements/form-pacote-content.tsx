import { FormPacoteContentProps } from "@/types";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";

export function FormPacoteContent({ data, processing, submit, setData, errors, props }: FormPacoteContentProps) {
    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={data.nome}
                setData={setData}
                error={errors.nome}
            />

            <SelectModelContent
                column="nucleo_id"
                titulo="Núcleos"
                id={data.nucleo_id}
                array={props.nucleos}
                setData={setData}
                error={errors.nucleo_id}
            />

            <SwitchContent
                column="ativo"
                titulo="Ativo"
                tituloInativo="Inativo"
                value={data.ativo}
                setData={setData}
                error={errors.ativo}
            />

            <InputNumberContent
                titulo='Valor'
                column='valor'
                value={data.valor}
                setData={setData}
                error={errors.valor}
                min={0}
                max={2000}
            />

            <ButtonSubmitContent
                processing={processing}
                processingText="Salvando..."
                buttonText="Salvar"
                classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
            />
        </form>
    );
}
