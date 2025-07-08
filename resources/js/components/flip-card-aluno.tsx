import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight, Link } from "lucide-react";
import { Aluno, RelacionadasAoAluno, User } from "@/types/models";

export default function FlipCardAluno({ aluno }: { aluno: Aluno & RelacionadasAoAluno }) {
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
              <b>{aluno.nome}</b>
              <b>{aluno.idade}</b>
              {/* <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('alunos.edit', { id: aluno.id })} children="Editar" /> */}
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
          <p><b>Responsáveis:</b></p>
          {aluno.users.map((user: User) => <p key={user.id} className="text-sm text-neutral-500">
            <Link href={route('users.show', { id: user.id })} className="text-blue-600 hover:underline">
              {user.nome}
            </Link>
          </p>)}
        </div>
      </motion.div>
    </div>
  );
}
