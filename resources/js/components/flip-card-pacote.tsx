import { Aula, Pacote, Turma } from "@/types/models";
import { Link } from "@inertiajs/react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { motion } from "framer-motion";
import { useState } from "react";

export default function FlipCardPacote({ pacote }: { pacote: Pacote }) {
  const [flipped, setFlipped] = useState(false);
  return (
    <div
      style={{ height: 'calc(.25rem * 80)' }}
      className="relative w-95" // Tamanho do cartão
    >
      <motion.div
        className="relative w-full h-full"
        animate={{ rotateY: flipped ? 180 : 0 }}
        transition={{ duration: 0.6 }}
        style={{ transformStyle: "preserve-3d" }}
      >
        {/* Frente do cartão */}
        <div className="absolute inset-0 border-sidebar-border/70 dark:border-sidebar-border rounded-xl border overflow-hidden backface-hidden">
          <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex flex-col items-center justify-center gap-4">
            <div className="flex flex-col items-center">
              <b>Dias:</b>
              {pacote.aulas.map((aula: Aula) => <p key={aula.id} className="text-sm">
                <span className="capitalize">{aula.dia_formatado}</span> às {aula.horario}
              </p>)}
            </div>
            <p>
              <b>Turma(s):</b>
              <span className="text-sm"> {!!pacote.turmas.length && pacote.turmas.map((turma: Turma) => turma.nome).join(" - ")}</span>
            </p>
            <div className="flex gap-2">
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('pacotes.show', { id: pacote.id })} children="Ver" />
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('pacotes.edit', { id: pacote.id })} children="Editar" />
            </div>
            <ChevronRight
              onClick={() => setFlipped(true)}
              className="cursor-pointer m-auto absolute right-0 top-1/2"
            />
          </div>
        </div>

        
        {/* Verso do cartão */}
        <div
          className="overflow-hidden absolute inset-0 bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-4 text-center backface-hidden"
          style={{ transform: "rotateY(180deg)" }}
        >
          <ChevronLeft
            onClick={() => setFlipped(false)}
            className="cursor-pointer m-auto absolute left-0 top-1/2"
          />
            <div className="grid gap-4">
              <div className="grid gap-2">
                <b>Vigência:</b>
                <p className="text-sm">{pacote.vigencia}</p>
              </div>
              <div className="grid gap-2">
                <b>Valor:</b>
                <p className="text-sm">{pacote.valor_formatado}</p>
              </div>
            </div>
        </div>
      </motion.div>
    </div>
  );
}
