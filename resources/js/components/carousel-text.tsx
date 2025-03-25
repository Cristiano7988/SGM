import { useState } from "react";
import { motion } from "framer-motion";

export default function CarouselText({ paragraphs }: { paragraphs: Array<string> }) {
  const [currentIndex, setCurrentIndex] = useState(0);

  const goToIndex = (index: number) => {
    setCurrentIndex(index);
  };

  return (
    <div className="flex flex-col justify-between gap-4 p-4 h-full items-center">
      <motion.p
        className="text-sm text-gray-700 dark:text-gray-300"
        key={currentIndex}
        initial={{ opacity: 0, x: 100 }}
        animate={{ opacity: 1, x: 0 }}
        exit={{ opacity: 0, x: -100 }}
        transition={{ duration: 0.5 }}
      >
        {paragraphs[currentIndex]}
      </motion.p>

      {paragraphs.length > 1 && (
        <div className="flex gap-2">
          {paragraphs.map((_, index) => (
            <button
              key={index}
              onClick={() => goToIndex(index)}
              className={`cursor-pointer w-3 h-3 rounded-full ${currentIndex === index ? "bg-blue-500" : "bg-gray-400"}`}
            />
          ))}
        </div>
      )}
    </div>
  );
}
