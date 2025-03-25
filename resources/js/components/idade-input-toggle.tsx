import React, { useState } from 'react';
import ErrorLabel from './error-label';
import { Switch } from '@headlessui/react';

interface IdadeInputToggleProps {
  label: string;
  column: string;
  value: number;
  limite: number;
  setData: (column: string, value: number) => void;
  error: string | undefined;
}

const IdadeInputToggle: React.FC<IdadeInputToggleProps> = ({
  label,
  column,
  value,
  limite,
  setData,
  error,
}) => {
  const [isYears, setIsYears] = useState(value > 12);

  const toggleInputMode = () => setIsYears(!isYears);

  const handleIdadeChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const value = isYears ? (Number(e.target.value) * 12).toFixed(1) : e.target.value;

    setData(column, Number(value));
  };

  return (
    <div className="flex flex-col gap-4 w-full">
      <div className="w-full">
        <label className="block font-medium">{label}</label>
        <input
          type="number"
          step={isYears ? 0.1 : 1}
          min={isYears ? (limite / 12).toFixed(1) : limite}
          required
          value={isYears ? (value / 12).toFixed(1) : value}
          onChange={handleIdadeChange}
          className="w-full p-2 border rounded-md"
        />
      </div>
      <ErrorLabel error={error} />
      <div className="flex items-center space-x-4">
        <span>{isYears ? 'Anos' : 'Meses'}</span>
        <Switch
          checked={isYears}
          onChange={toggleInputMode}
          className={`${isYears ? 'bg-gray-300' : 'bg-blue-500'} cursor-pointer relative inline-flex items-center h-6 rounded-full w-11`}
        >
          <span
            className={`${isYears ? 'translate-x-1' : 'translate-x-6'} inline-block w-4 h-4 transform bg-white rounded-full transition`}
          />
        </Switch>
      </div>
    </div>
  );
};

export default IdadeInputToggle;
