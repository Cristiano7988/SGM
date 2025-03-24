import React, { useState, useEffect, useRef } from 'react';
import ErrorLabel from './error-label';

interface ImageInputToggleProps {
  value: string | File | null;
  setImage: (value: any) => void;
  errors: any
}

const ImageInputToggle: React.FC<ImageInputToggleProps> = ({ value, setImage, errors }) => {
  const [isUrl, setIsUrl] = useState(true);
  const [preview, setPreview] = useState<string | null>(null);
  const inputRef = useRef<HTMLInputElement | null>(null);
  const labelRef = useRef<HTMLInputElement | null>(null);

  const toggleInputMode = () => {
    setIsUrl(!isUrl);
    setImage(null);
    setPreview(null);
  };

  const handleUrlChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setImage(e.target.value);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const [file] = e.target.files;

      setImage(file);
      if (labelRef.current) labelRef.current.textContent = file.name;
    }
  };

  useEffect(() => {
    if (value instanceof File) {
      const objectUrl = URL.createObjectURL(value);
      setPreview(objectUrl);
      return () => {
        URL.revokeObjectURL(objectUrl);
      };
    } else {
      setPreview(value);
    }
  }, [value]);

  return (
    <div className="flex flex-col sm:flex-row sm:items-end gap-4 w-fit">
      {preview && (
        <div className="mt-4">
          <p className="text-sm">Pré-visualização:</p>
          <img src={preview} alt="Imagem" className="h-32 sm:h-64 object-cover mt-2" />
        </div>
      )}
      <div className="flex flex-col gap-4 items-start justify-end w-fit">
        {isUrl ? (
          <div className='w-full'>
            <label className="block font-medium">Imagem (URL)</label>
            <input
              type="text"
              required
              value={typeof value === 'string' ? value : ''}
              onChange={handleUrlChange}
              placeholder="Insira a URL da imagem"
              className="w-full p-2 border rounded-md"
            />
          </div>
        ) : (
          <div>
            <label className="block font-medium">Imagem (Upload)</label>
            <div className='flex'>
              <button 
                type="button" 
                className="cursor-pointer bg-gray-100 dark:bg-gray-900 file-upload-button p-2 rounded-md"
                onClick={() => inputRef.current?.click()}
              >
                Escolher Arquivo
              </button>
              <span ref={labelRef} className="border file-name p-2 rounded-md block text-center">Nenhum arquivo selecionado</span>
              <input
                ref={inputRef}
                type="file"
                required
                onChange={handleFileChange}
                accept="image/*"
                className="hidden"
              />
            </div>
          </div>
        )}
        <ErrorLabel error={errors.imagem} />
        <button
          type="button"
          onClick={toggleInputMode}
          className="p-2 bg-blue-500 text-white rounded-md"
        >
          Alternar para {isUrl ? 'Upload de Imagem' : 'URL'}
        </button>
      </div>
    </div>
  );
};

export default ImageInputToggle;