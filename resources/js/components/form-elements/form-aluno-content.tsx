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
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Aluno>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const userInicial = { id: 0, pivot: { vinculo: "" }};
    const users = data.users?.length ? data.users : [userInicial];

    const addResponsavel = () => setData("users", [...users, userInicial]);

    const removeResponsavel = (index: number) => {
        const updatedUsers = [...users];
        updatedUsers.splice(index, 1);
        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateResponsavel = (index: number, user_id: number) => {
        const updatedUsers = [...users];
        const user = related.users.find((u: User) => u.id == user_id);

        updatedUsers[index] = {
            ...user
        };

        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateVinculo = (index: number, value: string) => {
        const updatedUsers = [...users];
        updatedUsers[index].pivot.vinculo = value;

        setData("users", updatedUsers);
        clearErrors(`users.${index}.pivot.vinculo`);
    };

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <div className="flex gap-2">
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
            </div>

            <hr />

            <h2 className="text-lg font-semibold">Usuários vinculados a este aluno</h2>

            {users.map((user: User, index: number) => (<div key={index} className="flex gap-2">
                <div className="flex items-center gap-2 w-full">
                    <SelectModelContent
                        column="users"
                        titulo={`Responsável ${index + 1}`}
                        id={user?.id}
                        array={related.users}
                        setData={(column: string, user_id: number) => updateResponsavel(index, user_id)}
                        error={errors[`users.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeResponsavel(index)}
                        />
                    )}
                </div>
                <InputTextContent
                    column="vinculo"
                    titulo="Vínculo"
                    value={user.pivot.vinculo}
                    setData={(column: string, value: string) => updateVinculo(index, value)}
                    error={errors[`users.${index}.pivot.vinculo`]}
                    clearErrors={clearErrors}
                />
            </div>))}

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