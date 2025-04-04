import React, { useState, useEffect, useRef } from 'react';
import ErrorLabel from '../error-label';

interface InputImageContentProps {
  value: string | File | null;
  setData: (key: string, value: any) => void;
  errors: any
}

export const InputImageContent: React.FC<InputImageContentProps> = ({ value, setData, errors }) => {
  const [isUrl, setIsUrl] = useState(true);
  const [preview, setPreview] = useState<string | null>(null);
  const inputRef = useRef<HTMLInputElement | null>(null);
  const labelRef = useRef<HTMLInputElement | null>(null);

  const toggleInputMode = () => {
    setIsUrl(!isUrl);
    setData('imagem', null);
    setPreview(null);
  };

  const handleUrlChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setData('imagem', e.target.value);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      const [file] = e.target.files;

      setData('imagem', file);
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
              type="url"
              required
              value={typeof value === 'string' ? value : ''}
              onChange={handleUrlChange}
              placeholder="Insira a URL da imagem"
              className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
        ) : (
          <div>
            <label className="block font-medium">Imagem (Upload)</label>
            <div className='flex'>
              <button 
                type="button" 
                className="cursor-pointer bg-gray-100 dark:bg-gray-900 file-upload-button p-3 rounded-md"
                onClick={() => inputRef.current?.click()}
              >
                Escolher Arquivo
              </button>
              <span ref={labelRef} className="border file-name p-3 rounded-md block text-center">Nenhum arquivo selecionado</span>
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
          className="p-3 bg-blue-500 text-white rounded-md cursor-pointer"
        >
          Alternar para {isUrl ? 'Upload de Imagem' : 'URL'}
        </button>
      </div>
    </div>
  );
};
