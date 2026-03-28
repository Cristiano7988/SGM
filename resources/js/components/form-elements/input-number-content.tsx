import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputNumberContentProps {
    value?: number;
    titulo: string;
    column: string;
    error?: string;
    clearErrors: Function;
    setData: Function;
    min?: number;
    max?: number;
}

export function InputNumberContent({ titulo, column, value, error, clearErrors, setData, min = 0, max = 100 }: InputNumberContentProps) {
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        clearErrors(column);
        setData(column, e.target.value);
    }

    return (
        <div className="relative w-full flex flex-col gap-4">
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="number"
                name={column}
                min={min}
                max={max}
                step="0.01"
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                style={{
                    colorScheme: 'light',
                }}
                value={value}
                onChange={handleChange}
            />
            <ErrorLabel error={error} />
        </div>
    );
}
