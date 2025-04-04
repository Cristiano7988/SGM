import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputTimeContentProps {
    titulo: string;
    column: string;
    value: string;
    error?: string;
    setData: Function;
}

export function InputTimeContent({ titulo, column, value, error, setData }: InputTimeContentProps) {
    return (
        <div className="relative w-full flex flex-col gap-4">
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="time"
                name={column}
                pattern="^([01]\d|2[0-3]):([0-5]\d)$"
                title="Formato válido: HH:MM"
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                style={{
                    colorScheme: 'light',
                }}
                value={value}
                onChange={(e) => setData(column, e.target.value)}
            />
            <ErrorLabel error={error} />
        </div>
    );
}
