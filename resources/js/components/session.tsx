import { X } from "lucide-react";
import { useState } from "react";

export default function Session(props: any ) {
    const [show, setShow] = useState(true);
    const { session } = props;

    if (!session || !show) return null;

    setTimeout(() => setShow(false), 10000);

    const [color] = [session.error && 'red', session.success && 'green'].filter(Boolean);

    return <div style={{ backgroundColor: color }} className={`absolute w-full text-white p-4 rounded-md mb-4 flex justify-between`}>
        <span>{session.error || session.success}</span>
        <X className="cursor-pointer" onClick={() => setShow(false)} />
    </div>
}
