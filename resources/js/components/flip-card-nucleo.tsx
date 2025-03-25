import { Nucleo } from "@/types";
import { useState } from "react";
import { motion } from "framer-motion";
import CarouselText from "./carousel-text";
import { Link } from "@inertiajs/react";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function FlipCardNucleo({ nucleo }: { nucleo: Nucleo }) {
  const [flipped, setFlipped] = useState(false);

  return (
    <div
      className="relative w-95 h-60" // Tamanho do cartão
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
                  src={nucleo.imagem}
                  alt={nucleo.nome}
                  className="w-full h-full object-cover"
                />
              </figure>
              <b>{nucleo.nome}</b>
              <Link className="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" href={route('nucleos.edit', { id: nucleo.id })} children="Editar" />
            </div>
            <div className="flex flex-col m-auto gap-6">
              <div className="flex flex-col gap-1">
                <p><strong>Público alvo:</strong></p>
                <div>
                  <p><strong>De:</strong> {nucleo.idade_minima > 12 ? (nucleo.idade_minima / 12).toFixed(1) : nucleo.idade_minima} {nucleo.unidade_de_tempo_minima}</p>
                  <p><strong>Até:</strong> {nucleo.idade_maxima > 12 ? (nucleo.idade_maxima / 12).toFixed(1) : nucleo.idade_maxima} {nucleo.unidade_de_tempo_maxima}</p>
                </div>
              </div>
              <div className="flex flex-col gap-1 bg-gray-100 dark:bg-gray-900 p-4 rounded-md">
                <p><strong>Matrículas:</strong></p>
                <div>
                  <p><strong>De:</strong> {nucleo.inicio_rematricula}</p>
                  <p><strong>Até:</strong> {nucleo.fim_rematricula}</p>
                </div>
              </div>
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
          <CarouselText paragraphs={nucleo.descricao} />
        </div>
      </motion.div>
    </div>
  );
}
