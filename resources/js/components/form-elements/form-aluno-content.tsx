import { FormProps } from "@/types";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { InputDateContent } from "./input-date-content";
import { useState } from "react";
import { CirclePlus, Trash2 } from "lucide-react";
import { Aluno, RelacionadasAoAluno } from "@/types/models";

export function FormAlunoContent({ data, processing, submit, setData, errors, hasErrors, props }: FormProps<Aluno & RelacionadasAoAluno>) {
    const [count, setCount] = useState(0);
    const incrementCount = () => setCount(count + 1);

    return (
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={data.nome}
                setData={setData}
                error={errors.nome}
            />

            <InputDateContent
                column="data_de_nascimento"
                titulo="Data de Nascimento"
                value={data.data_de_nascimento}
                setData={setData}
                error={errors.data_de_nascimento}
            />

            {[...Array(count + 1)].map((_, index) => (
                <div key={index} className="flex items-center gap-2">
                    <SelectModelContent
                        column="users"
                        titulo={`Responsável ${index + 1}`}
                        id={data["users"][index]?.id || 0}
                        array={props.users}
                        setData={(column: string, value: any) => {
                            const updatedUsers = [...data["users"]];
                            updatedUsers[index] = value;
                            setData(column, updatedUsers);
                        }}
                        error={errors["users"]}
                    />
                    {index > 0 && (
                        <Trash2
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => {
                                const updatedUsers = [...data["users"]];
                                updatedUsers.splice(index, 1);
                                setData("users", updatedUsers);
                                setCount(count - 1);
                            }}
                        />
                    )}
                </div>
            ))}

            <div>
                <label className="block font-medium text-white mb-2">Adicionar outro responsável</label>
                <CirclePlus className="cursor-pointer text-blue-500 hover:text-blue-700" onClick={incrementCount} />
            </div>

            {!hasErrors && <ButtonSubmitContent
                processing={processing}
                processingText="Salvando..."
                buttonText="Salvar"
                classes="bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
            />}
        </form>
    );
}
