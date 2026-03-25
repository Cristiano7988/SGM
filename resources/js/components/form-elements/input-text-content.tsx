import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface InputTextContentProps {
    value?: string;
    titulo: string;
    title?: string;
    column: string;
    error?: string;
    clearErrors: Function;
    setData: Function;
    required?: boolean;
}


export function InputTextContent({ titulo, column, value, error, clearErrors, setData, required = false, title }: InputTextContentProps) {
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        clearErrors(column);
        setData(column, e.target.value);
    }

    return (
        <div className='flex flex-col gap-4 w-full'>
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <input
                type="text"
                name={column}
                required={required}
                value={value}
                title={title}
                onChange={handleChange}
                className="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
            <ErrorLabel error={error} />
        </div>
    );
}
