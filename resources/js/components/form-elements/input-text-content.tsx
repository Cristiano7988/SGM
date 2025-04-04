import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputTextContentProps {
    value?: string;
    titulo: string;
    column: string;
    error?: string;
    setData: Function;
}

export function InputTextContent({ titulo, column, value, error, setData }: InputTextContentProps) {
    return (
        <div className='flex flex-col gap-4 w-full'>
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="text"
                name={column}
                required
                value={value}
                onChange={(e) => setData(column, e.target.value)}
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <ErrorLabel error={error} />
        </div>
    );
}
