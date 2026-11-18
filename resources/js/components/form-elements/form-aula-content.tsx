import { usePage } from "@inertiajs/react";
import { SelectModelContent } from "./select-model-content";
import { Endereco, Aula, Turma } from "@/types/models";
import { LoaderCircle, Unlink } from "lucide-react";
import ErrorLabel from "../error-label";
import { InputDateContent } from "./input-date-content";
import { InputTimeContent } from "./input-time-content";
import { useEffect, useState } from "react";
import type { Dispatch, SetStateAction } from "react";

type FormAulaProps = {
    aula: Aula;
    index: number;
    turma: Turma;
    setAulas: Dispatch<SetStateAction<Aula[]>>;
    dataForm: any;
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

export function FormAulaContent({ aula, index, turma, setAulas, dataForm, errors, clearErrors, related }: FormAulaProps) {
    const [inicializado, setInicializado] = useState(false);
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
                endereco?: Endereco;
                is_admin: boolean;
            };
        };
    }>().props;

    const taxa_km = Number(import.meta.env.VITE_TAXA_KM ?? 0);
    const taxa_minima = Number(import.meta.env.VITE_TAXA_MINIMA ?? 0);

    // Remover aula
    const removeAula = (index: number) => {
        setAulas(prev => prev.filter((_, i) => i !== index));
        clearErrors(`aulas.${index}`);
    };

    // Atualizar dia
    const updateDia = (index: number, value: string) => {
        setAulas(prev => prev.map((item, i) => i === index ? { ...item, dia: value } : item));

        clearErrors(`aulas.${index}.dia`);
    };

    // Atualizar horário
    const updateHorario = (index: number, value: string) => {
        setAulas(prev => prev.map((item, i) => i === index ? { ...item, horario: value } : item));

        clearErrors(`aulas.${index}.horario`);
    };

    // Atualizar endereço
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
    
    useEffect(() => {
        setInicializado(true);
    }, []);

    useEffect(() => {
        if (!inicializado) return;
        setLoading(true);

        const origem = turma?.professor?.user?.endereco;
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

        const coordenadasSemelhantes = origem.longitude === destino?.longitude && origem.latitude === destino?.latitude;

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

        if (coordenadasSemelhantes) {
            const distancia = 0;
            const distancia_formatada = formatarDistancia(distancia);
            const tempo_de_deslocamento = 0;
            const tempo_de_deslocamento_formatado = "0 min.";
            const taxa_de_deslocamento = 0;
            const taxa_de_deslocamento_formatada = formatarValor(taxa_de_deslocamento);
            const valor = turma.valor;
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
                if (!response.ok) throw new Error("Erro ao calcular rota.");
                return response.json();
            }).then((resultado) => {
                const distancia = resultado.distancia ?? 0;
                const distancia_formatada = formatarDistancia(distancia);
    
                const taxa_de_deslocamento = distancia > 0 ? taxa_minima + taxa_km * distancia : 0;
                const taxa_de_deslocamento_formatada = formatarValor(taxa_de_deslocamento);
    
                const tempo_de_deslocamento = resultado.tempo_de_deslocamento ?? 0;
                const tempo_de_deslocamento_formatado = `${tempo_de_deslocamento} min.`;
                    
                const valor = turma.valor + taxa_de_deslocamento;
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
                    column="endereco_id"
                    titulo="Endereço"
                    id={aula.endereco_id}
                    array={related.enderecos}
                    setData={(_: any, value: number) => updateEndereco(index, value)}
                    error={errors[`aulas.${index}.endereco_id`]}
                />

                <Unlink
                    className="cursor-pointer text-red-500 hover:text-red-700"
                    onClick={() => removeAula(index)}
                />
            </div>

            {errors.aulas && <ErrorLabel error={errors.aulas} />}

            {loading 
                ? <LoaderCircle className="h-5 w-5 animate-spin text-blue-600" />
                : <div className="flex gap-2">
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
                </div>}
        </div>
    );
}
