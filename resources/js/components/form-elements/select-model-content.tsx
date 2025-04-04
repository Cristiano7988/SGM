import ErrorLabel from '../error-label';
import { Label } from '../ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '../ui/select';

interface SelectContentProps {
    titulo: string;
    column: string;
    id: number;
    array: any[];
    error?: string;
    setData: Function;
}

export function SelectModelContent({ titulo, column, id, array, error, setData }: SelectContentProps) {
    return (
        <div className='flex flex-col w-full gap-4'>
            <Label htmlFor={column} className='block font-medium text-white'>{titulo}</Label>
            <Select
                onValueChange={(value) => setData(column, Number(value))}
                defaultValue={String(id)}
            >
                <SelectTrigger className="gap-2 min-w-56 cursor-pointer h-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <SelectValue placeholder="Filtrar" />
                </SelectTrigger>
                <SelectContent>
                    {array.map(item => <SelectItem
                        key={item.id}
                        value={String(item.id)}
                        children={item.nome ?? item.tipo}
                    /> )}
                </SelectContent>
            </Select>
            <ErrorLabel error={error} />
        </div>
    );
}
