import { Link, useForm } from "@inertiajs/react";
import { ButtonSubmitContent } from "./button-submit-content";
import { SelectModelContent } from "./select-model-content"
import { FormContentProps, Matricula, Aluno, Turma, Nucleo, Pacote, User } from "@/types/models";
import { FormProps } from "@/types/index";
import { useEffect, useState } from "react";
import { Unlink } from "lucide-react";
import ErrorLabel from "../error-label";

export function FormMatriculaContent({ initialData, endpoint, related }: FormContentProps<Matricula>) {
    const { data, setData, errors, clearErrors, hasErrors, processing, post, put } = useForm<FormProps<Matricula>>(initialData);
    const [turmas, setTurmas] = useState(related.turmas);
    const [pacotes, setPacotes] = useState(related.pacotes);
    const edit = location.pathname.includes("edit");

    const submit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        edit
            ? put(endpoint)
            : post(endpoint);
    };

    const handleUpdate = (id: number, array: any, column: string) => {
        const item = array.find((u: any) => u.id === id);

        setData(column, item.id);
        clearErrors(column);
    };

    function checarDisponibilidadeNucleo({ nucleo }: { nucleo: Nucleo }) {
        const now = new Date();
        const inicioMatricula = new Date(nucleo.inicio_matricula);
        const fimMatricula = new Date(nucleo.fim_matricula);
        const aluno = related.alunos.find((aluno: Aluno) => aluno.id == data.aluno_id);

        if (!aluno) return false;

        const noPeriodoDeMatricula = fimMatricula >= now && inicioMatricula <= now;
        const escopoDaIdade = nucleo.idade_minima <= aluno.meses && aluno.meses <= nucleo.idade_maxima;

        if (!escopoDaIdade) return false;
        if (!noPeriodoDeMatricula) return false;

        return true;
    }

    useEffect(() => {
        if (!data.aluno_id) return;

        const turmasFiltradas = related.turmas.filter((turma: Turma) => checarDisponibilidadeNucleo({ nucleo: turma.nucleo }));
        setTurmas(turmasFiltradas);

        if (!turmasFiltradas.length) {
            setPacotes([]);
            return;
        }

        const [turma] = turmasFiltradas; 
        const pacotesFiltrados = related.pacotes.filter((pacote: Pacote) => pacote.nucleo_id == turma.nucleo_id);
        setPacotes(pacotesFiltrados);
    }, [data.aluno_id]);

        const userInicial = { id: 0, pivot: { vinculo: "" }};
        const users = edit ? data.users : [userInicial];
    
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

    return (<>
        <form onSubmit={submit} className="flex flex-col gap-6 space-y-4">
            <SelectModelContent
                column="aluno_id"
                titulo={"Aluno"}
                id={data.aluno_id}
                array={related.alunos}
                setData={(_: any, id: number) => handleUpdate(id, related.alunos, 'aluno_id')}
                error={errors["aluno_id"]}
            />

            <SelectModelContent
                column="turma_id"
                titulo={"turma"}
                id={data.turma_id}
                array={turmas}
                setData={(_: any, id: number) => handleUpdate(id, turmas, 'turma_id')}
                error={errors["turma_id"]}
            />

            <SelectModelContent
                column="pacote_id"
                titulo={"Pacote"}
                id={data.pacote_id}
                array={pacotes}
                setData={(_: any, id: number) => handleUpdate(id, related.pacotes, 'pacote_id')}
                error={errors["pacote_id"]}
            />

            <SelectModelContent
                column="marcacao_id"
                titulo={"Marcação"}
                id={data.marcacao_id}
                array={related.marcacoes}
                setData={(_: any, id: number) => handleUpdate(id, related.marcacoes, 'marcacao_id')}
                error={errors["marcacao_id"]}
            />

            <SelectModelContent
                column="situacao_id"
                titulo={"Situação"}
                id={data.situacao_id}
                array={related.situacoes}
                setData={(_: any, id: number) => handleUpdate(id, related.situacoes, 'situacao_id')}
                error={errors["situacao_id"]}
            />
            
            <hr />

            <h2 className="text-lg font-semibold">Usuários que acompanharão o aluno</h2>

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

                    <Unlink
                        className="cursor-pointer text-red-500 hover:text-red-700"
                        onClick={() => removeResponsavel(index)}
                    />
                </div>
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

                <Link
                    href="/alunos/create"
                    className="px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Criar novo aluno"
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
