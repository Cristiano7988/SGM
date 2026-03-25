import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Aluno, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputNumberContent } from "./input-number-content";

export function FormUserContent({ initialData, endpoint, related }: FormContentProps<User>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<User>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const alunoInicial = { id: null, pivot: { vinculo: "" }};
    const alunos = edit ? data.alunos : [alunoInicial];

    const addAluno = () => setData("alunos", [...alunos, alunoInicial]);

    const removeAluno = (index: number) => {
        const updatedAlunos = alunos.filter((u: any, i: number) => i !== index);

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateAluno = (index: number, aluno_id: number) => {
        const updatedAlunos = [...alunos];
        const aluno = related.alunos.find((a: Aluno) => a.id == aluno_id);
        updatedAlunos[index] = aluno;

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateVinculo = (index: number, value: string) => {
        const updatedAlunos = [...data.alunos];
        updatedAlunos[index].pivot.vinculo = value;

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}.pivot.vinculo`);
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

            {!edit && <InputTextContent
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

            <hr />

            <h2 className="text-lg font-semibold">Alunos vinculados a este usuário</h2>

            {alunos.map((aluno: Aluno, index: number) => (<div key={index + aluno.id} className="flex gap-2">
                <div className="flex items-center gap-2 w-full">
                    <SelectModelContent
                        column="alunos"
                        titulo="Aluno"
                        id={aluno?.id}
                        array={related.alunos}
                        setData={(_column: string, aluno_id: number) => updateAluno(index, aluno_id)}
                        error={errors[`alunos.${index}`]}
                    />

                    {index > 0 && (
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeAluno(index)}
                        />
                    )}
                </div>
                {!!data.alunos?.length && <InputTextContent
                    column="vinculo"
                    titulo="Vínculo"
                    title="O que este usuário é deste aluno? Mãe? Pai? ..."
                    value={data.alunos[index].pivot.vinculo}
                    setData={(_column: string, value: string) => updateVinculo(index, value)}
                    error={errors[`alunos.${index}.pivot.vinculo`]}
                    clearErrors={clearErrors}
                />}
            </div>))}

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
    </>);
}