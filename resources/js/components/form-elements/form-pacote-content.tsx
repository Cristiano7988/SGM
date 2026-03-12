import { useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { FormContentProps, Pacote } from "@/types/models";
import { FormProps } from "@/types";

export function FormPacoteContent({ initialData, endpoint, related }: FormContentProps<Pacote>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post } = useForm<FormProps<Pacote>>(initialData);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(endpoint);
    };

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={data.nome}
                setData={setData}
                error={errors.nome}
                clearErrors={clearErrors}
            />

            <SelectModelContent
                column="nucleo_id"
                titulo="Núcleos"
                id={data.nucleo_id}
                array={related.nucleos}
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
                clearErrors={clearErrors}
                min={0}
                max={2000}
            />

            {!hasErrors && (
                <ButtonSubmitContent
                    processing={processing}
                    processingText="Salvando..."
                    buttonText="Salvar"
                    classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                />
            )}
        </form>
    );
}
