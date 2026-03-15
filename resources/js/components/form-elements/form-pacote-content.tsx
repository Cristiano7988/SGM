import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputNumberContent } from "./input-number-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { SwitchContent } from "./switch-content";
import { FormContentProps, Pacote, Periodo } from "@/types/models";
import { FormProps } from "@/types";
import { Unlink } from "lucide-react";

export function FormPacoteContent({ initialData, endpoint, related }: FormContentProps<Pacote>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Pacote>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const periodos = data.periodos.length ? data.periodos : [{ id: 0 }];
    
    const addPeriodo = () => {
        setData("periodos", [...periodos, { id: 0 }]);
    };

    const removePeriodo = (index: number) => {
        const updatedPeriodos = [...periodos];
        updatedPeriodos.splice(index, 1);
        setData("periodos", updatedPeriodos);
        clearErrors(`periodos.${index}`);
        clearErrors("periodos");
    };

    const updatePeriodo = (index: number, id: number) => {
        const user = related.periodos.find((u: Periodo) => u.id === id);
        const updatedPeriodos = [...periodos];
        updatedPeriodos[index] = user;
        setData("periodos", updatedPeriodos);
        clearErrors(`periodos.${index}`);
        clearErrors("periodos");
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

            <hr />

            {periodos.map((periodo: Periodo, index: number) => (
                <div key={index} className="flex items-center gap-2">

                    <SelectModelContent
                        column="periodos"
                        titulo={`Período ${index + 1}`}
                        id={periodo?.id}
                        array={related.periodos}
                        setData={(_: any, id: number) => updatePeriodo(index, id)}
                        error={errors[`periodos.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removePeriodo(index)}
                        />
                    )}
                </div>
            ))}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">
                <Link
                    href="/periodos/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo período"
                />

                <div
                    onClick={addPeriodo}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outro período"
                />

                {!hasErrors && (
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Salvando..."
                        buttonText="Salvar"
                        classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                    />
                )}
            </div>
        </form>
    );
}
