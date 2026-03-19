import { FormProps } from "@/types";
import { ButtonSubmitContent } from "./button-submit-content";
import { SelectModelContent } from "./select-model-content";
import { FormContentProps, Periodo } from "@/types/models";
import { InputDateContent } from "./input-date-content";
import { useForm } from "@inertiajs/react";

export function FormPeriodoContent({ initialData, endpoint, related }: FormContentProps<Periodo>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Periodo>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputDateContent
                column="inicio"
                titulo="Início"
                value={data.inicio}
                setData={setData}
                error={errors.inicio}
                clearErrors={clearErrors}
            />

            <InputDateContent
                column="fim"
                titulo="Fim"
                value={data.fim}
                setData={setData}
                error={errors.fim}
                clearErrors={clearErrors}
            />

            <SelectModelContent
                column="pacote_id"
                titulo="Pacotes"
                id={data.pacote_id}
                array={related.pacotes}
                setData={setData}
                error={errors.pacote_id}
            />

            {!hasErrors && <ButtonSubmitContent
                processing={processing}
                processingText="Salvando..."
                buttonText="Salvar"
                classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
            />}
        </form>
    );
}
