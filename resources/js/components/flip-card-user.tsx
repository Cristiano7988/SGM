import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { User, RelacionadasAoUser, Aluno } from "@/types/models";
import { Link } from "@inertiajs/react";

export default function FlipCardUser({ user }: { user: User & RelacionadasAoUser }) {
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
              <b>{user.nome}</b>
              <b>{user.email}</b>
              <b>{user.whatsapp}</b>
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('users.edit', { id: user.id })} children="Editar" />
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
          <p><b>Alunos:</b></p>
          {user.alunos.map((aluno: Aluno) => <p key={aluno.id} className="text-sm text-neutral-500">
            <Link href={route('alunos.show', { id: aluno.id })} className="text-blue-600 hover:underline">
              {aluno.nome} ({aluno.pivot.vinculo})
            </Link>
          </p>)}
        </div>
      </motion.div>
    </div>
  );
}
