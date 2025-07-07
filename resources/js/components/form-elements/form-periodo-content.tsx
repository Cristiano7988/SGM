import { FormProps } from "@/types";
import { ButtonSubmitContent } from "./button-submit-content";
import { SelectModelContent } from "./select-model-content";
import { Periodo } from "@/types/models";
import { InputDateContent } from "./input-date-content";

export function FormPeriodoContent({ data, processing, submit, setData, errors, props }: FormProps<Periodo>) {
    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputDateContent
                column="inicio"
                titulo="Início"
                value={data.inicio}
                setData={setData}
                error={errors.inicio}
            />

            <InputDateContent
                column="fim"
                titulo="Fim"
                value={data.fim}
                setData={setData}
                error={errors.fim}
            />

            <SelectModelContent
                column="pacote_id"
                titulo="Pacotes"
                id={data.pacote_id}
                array={props.pacotes}
                setData={setData}
                error={errors.pacote_id}
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
