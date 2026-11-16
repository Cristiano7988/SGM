import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Endereco, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputNumberContent } from "./input-number-content";
import { useEffect } from "react";

export function FormEnderecoContent({ initialData, endpoint, related }: FormContentProps<Endereco>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Endereco>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const [userInicial] = data.users;
    const addUser = () => setData("users", [...data.users, userInicial]);

    const removeUser = (index: number) => {
        if (data.users?.length) return;
        const updatedUsers = data.users.filter((u: any, i: number) => i !== index);

        setData("users", updatedUsers);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    const updateUser = (index: number, user_id: number) => {
        if (!data.users?.length) return;
    
        const updatedUser = [...data.users];
        const user = related.users.find((u: User) => u.id == user_id);
        updatedUser[index] = user;

        setData("users", updatedUser);
        clearErrors(`users.${index}`);
        clearErrors("users");
    };

    useEffect(() => {
        if (!data.cep) return;

        fetch(`https://viacep.com.br/ws/${data.cep}/json/`)
            .then((r) => r.ok ? r.json() : false)
            .then((resultado) => {
                setData("logradouro", resultado.logradouro);
                setData("bairro", resultado.bairro);
                setData("cidade", resultado.localidade);
                setData("estado", resultado.uf);
                setData("pais", "Brasil");
            });
    }, [data.cep]);

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">

            <hr />
            
            <div className='flex flex-col gap-4'>
                <h2 className="text-lg font-semibold">Endereço</h2>
                
                <InputTextContent
                    column="cep"
                    titulo="CEP"
                    value={data.cep}
                    setData={setData}
                    clearErrors={clearErrors}
                    error={errors.cep}
                />

                <div className='flex gap-4'>
                    <InputTextContent
                        column="pais"
                        titulo="País"
                        value={data.pais}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.pais}
                        required
                    />
                    <InputTextContent
                        column="estado"
                        titulo="Estado"
                        maxLength={2}
                        value={data.estado}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.estado}
                    />
                </div>

                <div className='flex gap-4'>
                    <InputTextContent
                        column="cidade"
                        titulo="Cidade"
                        value={data.cidade}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.cidade}
                    />
                    <InputTextContent
                        column="bairro"
                        titulo="Bairro"
                        value={data.bairro}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.bairro}
                    />
                </div>

                <InputTextContent
                    column="logradouro"
                    titulo="Logradouro"
                    value={data.logradouro}
                    setData={setData}
                    clearErrors={clearErrors}
                    error={errors.logradouro}
                />

                <div className='flex gap-4'>
                    <InputNumberContent
                        column="numero"
                        titulo="Número"
                        value={Number(data.numero)}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.numero}
                        max={999999}
                    />
                    <InputTextContent
                        column="complemento"
                        titulo="Complemento"
                        value={data.complemento}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.complemento}
                    />
                </div>
            </div>

            {!!data.users?.length && <>
                <hr />
                <h2 className="text-lg font-semibold">Usuários vinculados à este endereço</h2>
                {data.users.map((user: User, index: number) => (<div key={index + user?.id} className="flex gap-2">
                    <div className="flex items-center gap-2 w-full">
                        <SelectModelContent
                            column="users"
                            titulo="Usuário"
                            id={user?.id}
                            array={related.users}
                            setData={(_column: string, user_id: number) => updateUser(index, user_id)}
                            error={errors[`users.${index}`]}
                        />

                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeUser(index)}
                        />
                    </div>
                </div>))}
                {errors.users && <ErrorLabel error={errors.users} />}
            </>}

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