import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Aluno, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputNumberContent } from "./input-number-content";

export function FormUserContent({ inicialData, endpoint, related }: FormContentProps<User>) {

    const { data, setData, errors, clearErrors, hasErrors, processing, post } = useForm<FormProps<User>>(inicialData);
    // const { processing: processingDeletion, delete: deleteUser } = useForm();
    const editing = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const param = editing
            ? route(endpoint, data.id)
            : route(endpoint);

        post(param);
    };

    // const submitDeletion = (e: React.FormEvent) => {
    //     e.preventDefault();
    //     if (confirm('Tem certeza que deseja excluir este usuário?')) deleteUser(route('users.destroy', data.id));
    // };

    const alunos = data.alunos.length ? data.alunos : [{ id: 0 }];

    const addAluno = () => {
        setData("alunos", [...alunos, { id: 0 }]);
    };

    const removeAluno = (index: number) => {
        const updatedAlunos = [...alunos];
        updatedAlunos.splice(index, 1);
        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateAluno = (index: number, id: number) => {
        const aluno = related.alunos.find((u: Aluno) => u.id === id);
        const updatedAlunos = [...alunos];
        updatedAlunos[index] = aluno;
        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <div className='flex gap-4'>
                <InputTextContent
                    column="nome"
                    titulo="Nome"
                    value={data.nome}
                    setData={setData}
                    clearErrors={clearErrors}
                    error={errors.nome}
                    required
                />

                <InputTextContent
                    column="email_nf"
                    titulo="Email para Nota Fiscal"
                    value={data.email_nf}
                    setData={setData}
                    clearErrors={clearErrors}
                    error={errors.email_nf}
                />
            </div>

            {!editing && <InputTextContent
                column="email"
                titulo="Email de acesso"
                value={data.email}
                setData={setData}
                clearErrors={clearErrors}
                error={errors.email}
            />}
            
            <hr />

            <div className='flex flex-col gap-4'>
                <h2 className="text-lg font-semibold">Documentos</h2>
                <div className='flex gap-4'>
                    <InputTextContent
                        column="cpf"
                        titulo="CPF"
                        value={data.cpf}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.cpf}
                    />

                    <InputTextContent
                        column="cnpj"
                        titulo="CNPJ"
                        value={data.cnpj}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.cnpj}
                    />
                </div>
            </div>
            {/* <InputTextContent
                column="vinculo"
                titulo="Vínculo"
                value={data.vinculo}
                setData={setData}
                error={errors.vinculo}
                /> */}

            <hr />

            <div className='flex flex-col gap-4'>
                <h2 className="text-lg font-semibold">Contato</h2>
                <div className='flex gap-4'>
                    <InputTextContent
                        column="whatsapp"
                        titulo="WhatsApp"
                        value={data.whatsapp}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.whatsapp}
                    />
                    <InputTextContent
                        column="instagram"
                        titulo="Instagram"
                        value={data.instagram}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.instagram}
                    />
                </div>
            </div>

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
                        value={data.numero}
                        setData={setData}
                        clearErrors={clearErrors}
                        error={errors.numero}
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

            <hr />

            <h2 className="text-lg font-semibold">Alunos vinculados a este usuário</h2>

            {alunos.map((aluno: Aluno, index: number) => (
                <div key={index} className="flex items-center gap-2">

                    <SelectModelContent
                        column="alunos"
                        titulo={`Aluno ${index + 1}`}
                        id={aluno?.id}
                        array={related.alunos}
                        setData={(_: any, id: number) => updateAluno(index, id)}
                        error={errors[`alunos.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeAluno(index)}
                        />
                    )}
                </div>
            ))}

            {errors.alunos && <ErrorLabel error={errors.alunos} />}

            <div className="bg-background bottom-4 fixed flex gap-4 items-center p-4 right-4">

                <Link
                    href="/alunos/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo aluno"
                />

                <div
                    onClick={addAluno}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular outro aluno"
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
        {/* {editing && <form onSubmit={submitDeletion} >
            <div className="flex justify-end mt-4">
                <button
                    type="submit"
                    className="cursor-pointer bg-red-500 text-white px-4 py-2 rounded-md"
                    disabled={processing}
                >
                    {processingDeletion ? "Excluindo..." : "Excluir"}
                </button>
            </div>
        </form>} */}
    </>);
}