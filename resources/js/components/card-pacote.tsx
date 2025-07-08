import { Pacote, Periodo, RelacionadasAoPacote } from "@/types/models";
import { Link } from "@inertiajs/react";

export default function CardPacote({ pacote }: { pacote: Pacote & RelacionadasAoPacote }) {
  return (
    <div
      className="relative w-95 h-60"
    >
        <div className="absolute inset-0 border-sidebar-border/70 dark:border-sidebar-border rounded-xl border overflow-hidden backface-hidden">
          <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center gap-4">
            <div className="flex flex-col items-center m-auto gap-4">
              <b>{pacote.nome}</b>
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('pacotes.edit', { id: pacote.id })} children="Editar" />
            </div>
            <div className="flex flex-col m-auto gap-2">
              <p>{pacote.valor_formatado}</p>
              {pacote.periodos.map((periodo: Periodo) => (
                <p key={periodo.id} className="text-sm text-neutral-500">
                  De {periodo.inicio_formatado} até {periodo.fim_formatado}
                </p>
              ))}
            </div>
          </div>
        </div>
    </div>
  );
}
