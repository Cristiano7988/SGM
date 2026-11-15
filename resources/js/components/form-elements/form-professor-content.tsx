import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Professor, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { SwitchContent } from "./switch-content";
import { InputImageContent } from "./input-image-content";

export function FormProfessorContent({ initialData, endpoint, related }: FormContentProps<Professor>) {
    const userInicial: User = {
        id: 0,
        nome: '',
        email: '',
        email_nf: '',
        cpf: '',
        cnpj: '',
        whatsapp: '',
        instagram: '',
        pivot: {
            id: 0,
            user_id: 0,
            professor_id: 0,
            aluno_id: 0,
            vinculo: "",
            mesma_pessoa: false
        },
        matriculas: [],
        alunos: []
    };

    const formInicial = {
        ...initialData,
        users: initialData.users ?? [
            {
                ...userInicial,
                pivot: { ...userInicial.pivot }
            }
        ]
    };

    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Professor>>(formInicial);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const addUser = () => {
        setData("users", [
            ...(data.users ?? []),
            {
                ...userInicial,
                pivot: { ...userInicial.pivot }
            }
        ]);
    };

    const removeUser = (index: number) => {
        const updatedUsers = (data.users ?? []).filter((_: any, i: number) => i !== index);

        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateUser = (index: number, user_id: number) => {
        const user = related.users.find((u: User) => u.id === user_id);

        if (!user) return;

        const updatedUsers = [...(data.users ?? [])];
        updatedUsers[index] = {
            ...user,
            pivot: {
                id: 0,
                user_id: user.id,
                professor_id: data.id ?? 0,
                mesma_pessoa: false
            }
        };

        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateMesmaPessoa = (index: number, value: boolean) => {
        const updatedUsers = [...(data.users ?? [])];
        const user = updatedUsers[index];

        if (!user) return;

        updatedUsers[index] = {
            ...user,
            pivot: {
                ...user.pivot,
                mesma_pessoa: value
            }
        };

        setData("users", updatedUsers);
        clearErrors(`users.${index}.pivot.mesma_pessoa`);
    };

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <div className="flex flex-col gap-2">
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
                    clearErrors={clearErrors}
                />
            </div>

            <hr />

            <h2 className="text-lg font-semibold">Usuários vinculados a este professor</h2>

            {data.users?.map((user: User, index: number) => (<div key={index + user.id} className="flex gap-2">
                <div className="flex items-center gap-2">
                    <SelectModelContent
                        column="users"
                        titulo="Responsável"
                        id={user.id}
                        array={related.users}
                        setData={(_column: string, user_id: number) => updateUser(index, user_id)}
                        error={errors[`users.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeUser(index)}
                        />
                    )}
                    <SwitchContent
                        column="mesma_pessoa"
                        titulo="Mesma pessoa"
                        tituloInativo="Outra pessoa"
                        value={user.pivot.mesma_pessoa}
                        setData={() => updateMesmaPessoa(index, !user.pivot.mesma_pessoa)}
                        error={errors[`users.${index}.pivot.mesma_pessoa`]}
                    />
                </div>
            </div>))}

            {errors.users && (<ErrorLabel error={errors.users} />)}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">

                <Link
                    href="/users/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo usuário"
                />

                <div
                    onClick={addUser}
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
