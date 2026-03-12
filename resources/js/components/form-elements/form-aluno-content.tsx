import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { InputDateContent } from "./input-date-content";
import { Unlink } from "lucide-react";
import { Aluno, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";

export function FormAlunoContent({ initialData, endpoint, related }: FormContentProps<Aluno>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post } = useForm<FormProps<Aluno>>(initialData);

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(endpoint);
    };

    const users = data.users.length ? data.users : [{ id: 0 }];

    const addResponsavel = () => {
        setData("users", [...users, { id: 0 }]);
    };

    const removeResponsavel = (index: number) => {
        const updatedUsers = [...users];
        updatedUsers.splice(index, 1);
        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateResponsavel = (index: number, id: number) => {
        const user = related.users.find((u: User) => u.id === id);
        const updatedUsers = [...users];
        updatedUsers[index] = user;
        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <InputTextContent
                column="nome"
                titulo="Nome"
                value={data.nome}
                setData={setData}
                error={errors.nome}
                clearErrors={clearErrors}
            />

            <InputDateContent
                column="data_de_nascimento"
                titulo="Data de Nascimento"
                value={data.data_de_nascimento}
                setData={setData}
                error={errors.data_de_nascimento}
                clearErrors={clearErrors}
            />

            {users.map((user: User, index: number) => (
                <div key={index} className="flex items-center gap-2">

                    <SelectModelContent
                        column="users"
                        titulo={`Responsável ${index + 1}`}
                        id={user?.id}
                        array={related.users}
                        setData={(_: any, id: number) => updateResponsavel(index, id)}
                        error={errors[`users.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeResponsavel(index)}
                        />
                    )}
                </div>
            ))}

            {errors.users && <ErrorLabel error={errors.users} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">

                <Link
                    href="/users/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo usuário"
                />

                <div
                    onClick={addResponsavel}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outro usuário"
                />

                {!hasErrors && (
                    <ButtonSubmitContent
                        processing={processing}
                        processingText="Salvando..."
                        buttonText="Salvar"
                        classes="cursor-pointer bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 focus:ring-offset-blue-200"
                    />
                )}
            </div>

        </form>
    </>);
}