import { Switch } from '@headlessui/react';
import ErrorLabel from '../error-label';
import { Label } from '../ui/label';

interface SwitchContentProps {
    titulo: string;
    tituloInativo: string;
    column: string;
    value: boolean;
    error?: string;
    setData: Function;
}

export function SwitchContent({ titulo, tituloInativo, column, value, error, setData }: SwitchContentProps) {
    const toggleInputMode = () => setData(column, !value);

    return (
        <div className="flex items-center space-x-4">
            <Switch
                name={column}
                checked={value}
                onChange={toggleInputMode}
                className={`${value ? 'bg-blue-500' : 'bg-gray-300'} cursor-pointer relative inline-flex items-center h-6 rounded-full w-11`}
            >
                <span
                    className={`${value ? 'translate-x-6' : 'translate-x-1'} inline-block w-4 h-4 transform bg-white rounded-full transition`}
                />
            </Switch>
            <Label htmlFor={column} className='block font-medium text-white'>{value ? titulo : tituloInativo}</Label>
            <ErrorLabel error={error} />
        </div>
    );
}
