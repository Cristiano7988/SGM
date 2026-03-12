import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { Matricula, RelacionadasAMatricula } from "@/types/models";
import { Link } from "@inertiajs/react";

export default function FlipCardMatricula({ matricula }: { matricula: Matricula & RelacionadasAMatricula }) {
  const [flipped, setFlipped] = useState(false);

  return (
    <div
      className="relative w-95 h-60"
    >
      <motion.div
        className="relative w-full h-full"
        animate={{ rotateY: flipped ? 180 : 0 }}
        transition={{ duration: 0.6 }}
        style={{ transformStyle: "preserve-3d" }}
      >
        {/* Frente do cartão */}
        <div className="absolute inset-0 border-sidebar-border/70 dark:border-sidebar-border rounded-xl border overflow-hidden backface-hidden">
          <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center gap-4">
            <div className="flex flex-col items-center m-auto gap-4">
              <figure className="m-auto w-24 h-24 rounded-full overflow-hidden border border-gray-300">
                <img
                  src={matricula.turma.imagem}
                  alt={matricula.turma.nome}
                  className="w-full h-full object-cover"
                />
              </figure>
              <p><b>Turma:</b> {matricula.turma.nome}</p>
              <p><b>Aluno:</b> {matricula.aluno.nome}</p>
              
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('matriculas.edit', { id: matricula.id })} children="Editar" />
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
          <b>{matricula.turma.dia.nome} às {matricula.turma.horario}</b>
          
          <p><b>Pacote:</b></p>
          <b>{matricula.pacote.nome}</b>
          <b>{matricula.pacote.valor_formatado}</b>
          <b>Pacote {matricula.pacote.ativo ? "Ativo" : "Inativo"}</b>
        </div>
      </motion.div>
    </div>
  );
}
