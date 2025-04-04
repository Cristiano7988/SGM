import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface TextAreaContentProps {
    titulo: string;
    column: string;
    value?: string;
    error?: string;
    setData: Function;
}

export function TextAreaContent({ titulo, column, value, error, setData }: TextAreaContentProps) {
    return (
        <div className='flex flex-col gap-4'>
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <textarea
                name={column}
                rows={5}
                required
                value={value}
                onChange={(e) => setData(column, e.target.value)}
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <ErrorLabel error={error} />
        </div>
    );
}
