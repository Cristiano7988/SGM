import { Aula, Pacote, RelacionadasAoPacote } from "@/types/models";
import { Link } from "@inertiajs/react";

export default function CardPacote({ pacote }: { pacote: Pacote & RelacionadasAoPacote }) {
  return (
    <div
      className="relative w-95 h-60"
    >
        <div className="absolute inset-0 border-sidebar-border/70 dark:border-sidebar-border rounded-xl border overflow-hidden backface-hidden">
          <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center gap-4">
            <div className="flex flex-col m-auto gap-4">
              <b>{pacote.nome}</b>
              <p>{pacote.valor_formatado}</p>
              <div className="grid gap-2">
                <b>Turma:</b>
                <p className="text-sm">{pacote.turma.nome}</p>
              </div>
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('pacotes.edit', { id: pacote.id })} children="Editar" />
            </div>
            <div className="flex flex-col justify-between">
              <div className="grid gap-2">
                <b>Dias:</b>
                {pacote.aulas_na_semana.map((aula: Aula) => <p key={aula.id} className="text-sm">
                  <span className="capitalize">{aula.dia_da_semana}</span> às {aula.horario}
                </p>)}
              </div>
              <div className="grid gap-2">
                <b>Vigência:</b>
                <p className="text-sm">{pacote.vigencia}</p>
              </div>
            </div>
          </div>
        </div>
    </div>
  );
}
