import { ChevronLeft, ChevronRight } from "lucide-react";
import { useState } from "react";
import { motion } from "framer-motion";

export default function CarouselText({ paragraphs }: { paragraphs: Array<string> }) {
  const [currentIndex, setCurrentIndex] = useState(0);

  const goToPrevious = () => {
    if (currentIndex > 0) {
      setCurrentIndex(currentIndex - 1);
    }
  };

  const goToNext = () => {
    if (currentIndex < paragraphs.length - 1) {
      setCurrentIndex(currentIndex + 1);
    }
  };

  const goToIndex = (index: number) => {
    setCurrentIndex(index);
  };

  return (
    <div className="flex flex-col justify-between p-4 h-full items-center">
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

      <div className="mt-4 flex justify-between w-full max-w-[200px]">
        <ChevronLeft
          onClick={goToPrevious}
          className={`${currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}`}
          style={{ visibility: currentIndex === 0 ? 'hidden' : 'visible' }}
        />

        {paragraphs.length > 1 && (
          <div className="flex gap-2">
            {paragraphs.map((_, index) => (
              <button
                key={index}
                onClick={() => goToIndex(index)}
                className={`m-auto w-3 h-3 rounded-full ${currentIndex === index ? "bg-blue-500" : "bg-gray-400"}`}
              />
            ))}
          </div>
        )}

        <ChevronRight
          onClick={goToNext}
          className={`${currentIndex === paragraphs.length - 1 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}`}
          style={{ visibility: currentIndex === paragraphs.length - 1 ? 'hidden' : 'visible' }}
        />
      </div>
    </div>
  );
}
