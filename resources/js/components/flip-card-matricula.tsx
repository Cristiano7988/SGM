import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { Aluno, Aula, Matricula, RelacionadasAMatricula, User } from "@/types/models";
import { Link } from "@inertiajs/react";
import { Tag } from "./ui/tag";

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
          <div className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20 p-4 flex justify-center items-center gap-4">
            <div className="flex flex-col gap-4">
              <figure className="m-auto w-24 h-24 rounded-full overflow-hidden border border-gray-300">
                <img
                  src={matricula.turma?.imagem}
                  alt={matricula.turma?.nome}
                  className="w-full h-full object-cover"
                />
              </figure>
              
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 m-auto" href={route('matriculas.edit', { id: matricula.id })} children="Editar" />
            </div>
            <div className="flex flex-col m-auto gap-2">
              <p><b>Aluno:</b> <Link className="tex-sm" href={`/alunos/${matricula.aluno_id}`}>{matricula.aluno?.nome}</Link></p>
              <p><b>Turma:</b> <Link className="tex-sm" href={`/turmas/${matricula.turma_id}`}>{matricula.turma?.nome}</Link></p>
              <p><b>Dias:</b> {matricula.pacote?.aulas_na_semana.map((aula: Aula) => <p key={aula.id} className="text-sm">
                <span className="capitalize">{aula.dia_da_semana}</span> às {aula.horario}
              </p>)}</p>
            </div>
            <ChevronRight
              onClick={() => setFlipped(true)}
              className="cursor-pointer m-auto absolute right-0 top-1/2"
            />
          </div>
        </div>

        {/* Verso do cartão */}
        <div
          className="overflow-hidden absolute inset-0 bg-gray-100 dark:bg-gray-900 rounded-xl shadow-lg flex flex-col items-center justify-center p-4 text-center backface-hidden gap-2"
          style={{ transform: "rotateY(180deg)" }}
        >
          <ChevronLeft
            onClick={() => setFlipped(false)}
            className="cursor-pointer m-auto absolute left-0 top-1/2"
          />

          <p>
            <b>Pacote: </b>
            <Link href={`/pacotes/${matricula.pacote_id}`}>{matricula.pacote?.nome} ({matricula.pacote?.ativo ? "ativo" : "inativo"})</Link>
          </p>
          <p>{matricula.pacote?.valor_formatado}</p>
          <div className="flex items-center gap-2">
            <b>Vigência:</b>
            <div>
              <p className="text-sm">
                {matricula.pacote.vigencia}
              </p>
            </div>
          </div>
          <hr className="border-gray-300 w-3/4" />
          {!!matricula.users?.length &&<div className="flex flex-col gap-2">
            <b>Acompanhantes:</b>
            <div className="flex flex-col gap-1">
              {matricula.users.map((user: User) => {
                const [alunoVinculado] = user.alunos?.filter((a: Aluno) => a.id == matricula.aluno_id);
                return <div key={user.id} className="flex items-center gap-1"><Link className="text-sm" href={route('users.show', { id: user.id })}>
                  {user.nome}
                </Link>
                <Tag background="darkcyan" children={alunoVinculado?.pivot?.vinculo} title="Vínculo" />
              </div>})}
            </div>
          </div>}
        </div>
      </motion.div>
    </div>
  );
}
