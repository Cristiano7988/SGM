import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { InputTextContent } from "./input-text-content";
import { SelectModelContent } from "./select-model-content";
import { Unlink } from "lucide-react";
import { Aluno, Endereco, FormContentProps } from "@/types/models";
import { FormProps } from "@/types/index";
import ErrorLabel from "../error-label";
import { InputNumberContent } from "./input-number-content";

export function FormEnderecoContent({ initialData, endpoint, related }: FormContentProps<Endereco>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Endereco>>(initialData);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const alunoInicial = {
        id: 0,
        nome: "",
        data_de_nascimento: "",
        data_de_nascimento_formatada: "",
        idade: "",
        matriculas: [],
        pivot: { 
            id: 0,
            aluno_id: 0,
            user_id: 0,
            vinculo: ""
        }
    };
    const alunos = edit ? data.alunos : [alunoInicial];

    const addAluno = () => alunos && setData("alunos", [...alunos, alunoInicial]);

    const removeAluno = (index: number) => {
        const updatedAlunos = alunos?.filter((u: any, i: number) => i !== index);

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

    const updateAluno = (index: number, aluno_id: number) => {
        if (!alunos) return;
    
        const updatedAlunos = [...alunos];
        const aluno = related.alunos.find((a: Aluno) => a.id == aluno_id);
        updatedAlunos[index] = aluno;

        setData("alunos", updatedAlunos);
        clearErrors(`alunos.${index}`);
        clearErrors("alunos");
    };

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

            {!!alunos?.length && <hr />}

            {!!alunos?.length && <h2 className="text-lg font-semibold">Alunos vinculados a este usuário</h2>}

            {alunos?.map((aluno: Aluno, index: number) => (<div key={index + aluno.id} className="flex gap-2">
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