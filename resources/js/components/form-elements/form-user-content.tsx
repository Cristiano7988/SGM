import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Aluno, FormContentProps, User } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";

export function FormUserContent({ initialData, endpoint, related }: FormContentProps<User>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<User>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const [alunoInicial] = initialData.alunos

    if (alunoInicial) delete alunoInicial.id;

    const addAluno = () => setData("alunos", [...data.alunos, alunoInicial]);

    const removeAluno = (index: number) => {
        const updatedAlunos = data.alunos?.filter((u: any, i: number) => i !== index);

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateAluno = (index: number, aluno_id: number) => {
        const aluno = related.alunos.find((a: Aluno) => a.id == aluno_id);
        if (!aluno) return;

        setData("alunos", data.alunos.map((a, i) =>
            i === index
                ? { ...aluno, pivot: { ...aluno.pivot, vinculo: a?.pivot?.vinculo ?? "" } }
                : a
        ));

        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateVinculo = (index: number, value: string) => {
        setData("alunos", data.alunos.map((aluno, i) =>
            i === index
                ? { ...aluno, pivot: { ...aluno.pivot, vinculo: value } }
                : aluno
        ));
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
            {!!data.alunos?.length && <>
                <hr />
                <h2 className="text-lg font-semibold">Alunos vinculados a este usuário</h2>
            
                {data.alunos.map((aluno: Aluno, index: number) => (<div key={index} className="flex gap-2">
                    <div className="flex items-center gap-2 w-full">
                        <SelectModelContent
                            column="alunos"
                            titulo="Aluno"
                            id={aluno?.id}
                            array={related.alunos}
                            setData={(_column: string, aluno_id: number) => updateAluno(index, aluno_id)}
                            error={errors["alunos"] ?? errors[`alunos.${index}`] ?? errors[`alunos.${index}.id`] ?? errors[`alunos.${index}.pivot.vinculo`]}
                        />
                        <InputTextContent
                            column="vinculo"
                            titulo="Vínculo"
                            title="O que este usuário é deste aluno? Mãe? Pai? ..."
                            value={aluno?.pivot?.vinculo}
                            setData={(_column: string, value: string) => updateVinculo(index, value)}
                            error={errors[`alunos.${index}.pivot.vinculo`]}
                            clearErrors={clearErrors}
                        />
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeAluno(index)}
                        />
                    </div>
                </div>))}
            </>}

            {errors.alunos && <ErrorLabel error={errors.alunos} />}

            <hr />

            <h2 className="text-lg font-semibold">Endereço vinculado a este usuário</h2>

            <SelectModelContent
                column="endereco_id"
                titulo="Endereço"
                id={data.endereco?.id}
                array={related.enderecos}
                setData={setData}
                error={errors[`endereco_id`]}
            />

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

                
                <Link
                    href="/enderecos/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo endereço"
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