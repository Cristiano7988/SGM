import { usePage } from "@inertiajs/react";
import { SelectModelContent } from "./select-model-content";
import { Endereco, Aula, Turma, Aluno } from "@/types/models";
import { LoaderCircle, Unlink } from "lucide-react";
import ErrorLabel from "../error-label";
import { InputDateContent } from "./input-date-content";
import { InputTimeContent } from "./input-time-content";
import { useEffect, useState } from "react";
import type { Dispatch, SetStateAction } from "react";

type FormAulaProps = {
    aula: Aula;
    index: number;
    setAulas: Dispatch<SetStateAction<Aula[]>>;
    errors: any;
    clearErrors: any;
    related: any;
};

const formatarValor = (valor: number) =>
    valor.toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL",
    });

const formatarDistancia = (distancia: number) =>
    `${distancia.toFixed(2).replace(".", ",")} km`;

export function FormAulaContent({ aula, index, setAulas, errors, clearErrors, related }: FormAulaProps) {
    const [inicializado, setInicializado] = useState(false);
    const [alunos, setAlunos] = useState(aula.alunos ?? related.alunos);
    const [loading, setLoading] = useState(false);
    
    const hoje = new Date();

    const dia = [
        hoje.getFullYear(),
        String(hoje.getMonth() + 1).padStart(2, "0"),
        String(hoje.getDate()).padStart(2, "0"),
    ].join("-");

    const { auth } = usePage<{
        auth?: {
            user?: {
                is_admin: boolean;
            };
        };
    }>().props;

    const taxa_km = Number(import.meta.env.VITE_TAXA_KM ?? 0);
    const taxa_minima = Number(import.meta.env.VITE_TAXA_MINIMA ?? 0);

    const removeAula = (index: number) => {
        setAulas(prev => prev.filter((_, i) => i !== index));
        clearErrors(`aulas.${index}`);
    };

    const updateDia = (index: number, value: string) => {
        setAulas(prev => prev.map((item, i) => i === index ? { ...item, dia: value } : item));

        clearErrors(`aulas.${index}.dia`);
    };

    const updateHorario = (index: number, value: string) => {
        setAulas(prev => prev.map((item, i) => i === index ? { ...item, horario: value } : item));

        clearErrors(`aulas.${index}.horario`);
    };

    const updateEndereco = (index: number, value: number) => {
        const endereco = related.enderecos?.find((e: Endereco) => String(e.id) === String(value));
        setAulas(prev =>
            prev.map((item, i) =>
                i === index
                    ? {
                        ...item,
                        endereco,
                        endereco_id: value
                    }
                    : item
            )
        );

        clearErrors(`aulas.${index}.endereco_id`);
    };

    const updateTurma = (index: number, value: number) => {
        const turma = related.turmas?.find((t: Turma) => String(t.id) === String(value));

        setAulas(prev =>
            prev.map((item, i) =>
                i === index
                    ? {
                        ...item,
                        turma,
                        turma_id: value
                    }
                    : item
            )
        );

        clearErrors(`aulas.${index}.turma_id`);
    };

    const alunoBase = aula?.alunos?.[0] ?? related?.alunos?.[0];

    const addAluno = () => setAlunos([...alunos, alunoBase]);

    const updateAluno = (index: number, aluno_id: number) => {
        const novoAluno = related.alunos.find((a: Aluno) => a.id === Number(aluno_id));

        if (!novoAluno) return;

        setAlunos((prev) => prev.map((item, i) => (i === index ? novoAluno : item)));
    };

    const removeAluno = (index: number) => {
        setAlunos(prev => prev.filter((_, i) => i !== index));
        clearErrors(`alunos.${index}`);
    };

    useEffect(() => {
        setInicializado(true);
    }, []);

    useEffect(() => {
        if (!inicializado) return;
        setLoading(true);

        const origem = aula.turma?.professor?.user?.endereco;
        const destino = aula.endereco;

        const lon1 = origem?.longitude;
        const lat1 = origem?.latitude;
        const lon2 = destino?.longitude;
        const lat2 = destino?.latitude;

        const coordenadas = [lon1, lat1, lon2, lat2];

        const coordenadasValidas = coordenadas.every(
            coordenada =>
                coordenada !== null &&
                coordenada !== undefined &&
                coordenada !== "" &&
                Number.isFinite(Number(coordenada))
        );

        if (!coordenadasValidas) {
            setAulas(prev =>
                prev.map((item, i) =>
                    i === index
                        ? { ...aula }
                        : item
                )
            );
            setLoading(false);
            return;
        }

        const coordenadasSemelhantes = origem?.longitude === destino?.longitude && origem?.latitude === destino?.latitude;
        const valor_da_turma = (aula?.turma?.valor ?? 0) * (alunos?.length ?? 0);

        if (coordenadasSemelhantes) {
            const distancia = 0;
            const distancia_formatada = formatarDistancia(distancia);
            const tempo_de_deslocamento = 0;
            const tempo_de_deslocamento_formatado = "0 min.";
            const taxa_de_deslocamento = 0;
            const taxa_de_deslocamento_formatada = formatarValor(taxa_de_deslocamento);
            const valor = valor_da_turma;
            const valor_formatado = formatarValor(valor);
            setAulas(prev =>
                prev.map((item, i) =>
                    i === index
                        ? {
                            ...aula,
                            distancia,
                            distancia_formatada,

                            tempo_de_deslocamento,
                            tempo_de_deslocamento_formatado,
                            
                            taxa_de_deslocamento,
                            taxa_de_deslocamento_formatada,
                            
                            valor,
                            valor_formatado,
                        }
                        : item
                )
            );
            setLoading(false);
            return;
        }

        try {
            fetch("/api/calcular-rota", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    origem: {
                        longitude: Number(lon1),
                        latitude: Number(lat1),
                    },
                    destino: {
                        longitude: Number(lon2),
                        latitude: Number(lat2),
                    },
                }),
            }).then((response) => {
                if (!response.ok) {
                    setLoading(false);
                    throw new Error("Erro ao calcular rota.");
                }
                return response.json();
            }).then((resultado) => {
                const distancia = resultado.distancia ?? 0;
                const distancia_formatada = formatarDistancia(distancia);
    
                const taxa_de_deslocamento = distancia > 0 ? taxa_minima + taxa_km * distancia : 0;
                const taxa_de_deslocamento_formatada = formatarValor(taxa_de_deslocamento);
    
                const tempo_de_deslocamento = resultado.tempo_de_deslocamento ?? 0;
                const tempo_de_deslocamento_formatado = `${tempo_de_deslocamento} min.`;
                    
                const valor = valor_da_turma + taxa_de_deslocamento;
                const valor_formatado = formatarValor(valor);
    
                if (!Number.isFinite(distancia) || !Number.isFinite(tempo_de_deslocamento)) throw new Error("A API retornou dados de rota inválidos.");
    
                setAulas(prev =>
                    prev.map((item, i) => i === index
                        ? {
                            ...item,

                            distancia,
                            distancia_formatada,

                            tempo_de_deslocamento,
                            tempo_de_deslocamento_formatado,
                            
                            taxa_de_deslocamento,
                            taxa_de_deslocamento_formatada,
                            
                            valor,
                            valor_formatado,
                        }
                        : item
                    )
                );
                setLoading(false);
            })
        } catch (error: any) {
            setLoading(false);
            if (error.name !== "AbortError") console.error("Erro ao calcular deslocamento:", error);
        }
    }, [aula.endereco_id]);

    useEffect(() => {
        setAulas(prev =>
            prev.map((item, i) =>
                i === index
                    ? {
                        ...item,
                        alunos
                    }
                    : item
            )
        );
    }, [alunos]);

    return (
        <div className="flex flex-col gap-2">
            <div className="flex items-end gap-2">
                <InputDateContent
                    column="dia"
                    value={aula.dia}
                    {...(!auth?.user?.is_admin && { min: dia })}
                    setData={(_: any, value: string) =>
                        updateDia(index, value)
                    }
                    error={errors[`aulas.${index}.dia`]}
                    clearErrors={clearErrors}
                />

                <InputTimeContent
                    column="horario"
                    value={aula.horario}
                    setData={(_: any, value: string) =>
                        updateHorario(index, value)
                    }
                    error={errors[`aulas.${index}.horario`]}
                />

                <SelectModelContent
                    column="turma_id"
                    titulo="Turmas"
                    id={aula.turma_id}
                    array={related.turmas}
                    setData={(_: any, value: number) => updateTurma(index, value)}
                    error={errors[`aulas.${index}.turma_id`]}
                />

                <SelectModelContent
                    column="endereco_id"
                    titulo="Endereço"
                    id={aula.endereco_id}
                    array={related.enderecos}
                    setData={(_: any, value: number) => updateEndereco(index, value)}
                    error={errors[`aulas.${index}.endereco_id`]}
                />

                <Unlink
                    className="cursor-pointer text-red-500 hover:text-red-700 w-14"
                    onClick={() => removeAula(index)}
                />
            </div>

            {errors.aulas && <ErrorLabel error={errors.aulas} />}

            <div className="flex items-end gap-2">
                {loading 
                    ? <LoaderCircle className="h-5 w-5 animate-spin text-blue-600" />
                    : <>
                        {!!aula.tempo_de_deslocamento && (
                            <div className="bg-gray-100 dark:bg-gray-900 p-4 gap-2 flex flex-col justify-center">
                                <h3>Taxa de deslocamento:</h3>

                                {!!aula.distancia && (
                                    <p>
                                        <b>Distância:</b>{" "}
                                        {aula.distancia_formatada}
                                    </p>
                                )}

                                {!!aula.tempo_de_deslocamento && (
                                    <p>
                                        <b>Tempo de deslocamento:</b>{" "}
                                        {aula.tempo_de_deslocamento_formatado}
                                    </p>
                                )}

                                {!!aula.taxa_de_deslocamento && (
                                    <p>
                                        <b>Valor:</b>{" "}
                                        {aula.taxa_de_deslocamento_formatada}
                                    </p>
                                )}
                            </div>
                        )}

                        {!!aula.valor && (
                            <div className="bg-gray-100 dark:bg-gray-900 p-4 gap-2 flex flex-col justify-center">
                                <h3>Valor total da aula:</h3>
                                <p>
                                    <b>Valor:</b>{" "}
                                    {aula.valor_formatado}
                                </p>
                            </div>
                        )}
                    </>}
                <div
                    onClick={addAluno}
                    className="cursor-pointer px-4 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 font-medium bg-blue-100 rounded text-blue-600 hover:bg-blue-200"
                    children="Vincular aluno"
                />

                <div className="flex flex-col gap-2">
                    {alunos.map((aluno: Aluno, index: number) => <div className="flex items-end gap-2">
                        <SelectModelContent
                            column="aluno_id"
                            titulo="Aluno"
                            id={aluno?.id}
                            array={related.alunos}
                            setData={(_: any, value: number) => updateAluno(index, value)}
                            error={errors[`aulas.${index}.aluno_id`]}
                        />
                        <Unlink
                            className="cursor-pointer text-red-500 hover:text-red-700"
                            onClick={() => removeAluno(index)}
                        />
                    </div>)}
                </div>
            </div>
        </div>
    );
}
