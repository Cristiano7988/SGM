import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputTimeContentProps {
    titulo: string;
    column: string;
    value: string;
    error?: string;
    clearErrors: Function;
    setData: Function;
}

export function InputDateContent({ titulo, column, value, error, clearErrors, setData }: InputTimeContentProps) {
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setData(column, e.target.value);
        clearErrors(column);
    };

    return (
        <div className="relative w-full flex flex-col gap-4">
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="date"
                name={column}
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
