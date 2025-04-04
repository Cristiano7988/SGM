import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputNumberContentProps {
    value?: number;
    titulo: string;
    column: string;
    error?: string;
    setData: Function;
    min?: number;
    max?: number;
}

export function InputNumberContent({ titulo, column, value, error, setData, min = 0, max = 100 }: InputNumberContentProps) {
    return (
        <div className="relative w-full flex flex-col gap-4">
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="number"
                name={column}
                min={min}
                max={max}
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                style={{
                    colorScheme: 'light',
                }}
                value={value}
                onChange={(e) => setData(column, Number(e.target.value))}
            />
            <ErrorLabel error={error} />
        </div>
    );
}
