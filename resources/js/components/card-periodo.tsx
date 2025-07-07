import { Periodo } from "@/types/models";
import { Link } from "@inertiajs/react";

export default function CardPeriodo({ periodo }: { periodo: Periodo }) {
  return (
    <div
      className="w-95 h-60"
    >
        <div className="border-sidebar-border/70 dark:border-sidebar-border rounded-xl border overflow-hidden">
          <div className="flex-col size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center gap-4">
            <div className="flex gap-4">
              <b>Início: </b>
              <span>
                {periodo.inicio_formatado}
              </span>
            </div>
            <div className="flex gap-4">
              <b>Fim: </b>
              <span>
                {periodo.fim_formatado}
              </span>
            </div>
            <div className="flex gap-4">
              <b>Pacote: </b>
              <Link href={"/pacotes/" + periodo.pacote_id} children={periodo.pacote.nome} />
            </div>
            <Link className="m-auto rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('periodos.edit', { id: periodo.id })} children="Editar" />
          </div>
        </div>
    </div>
  );
}
