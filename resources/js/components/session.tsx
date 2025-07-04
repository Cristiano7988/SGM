import { SessionType } from "@/types";
import { X } from "lucide-react";
import { useEffect, useState } from "react";

export default function Session({ session }: { session: SessionType }) {
    const [show, setShow] = useState(!!(session?.error || session?.success));

    useEffect(() => {
        setShow(!!(session?.error || session?.success));
    }, [session]);

    useEffect(() => {
        if (!show) return;

        const timer = setTimeout(() => setShow(false), 10000);
        return () => clearTimeout(timer); // Limpa o timeout ao desmontar
    }, [show]);

    if (!show) return null;
    
    const [color] = [session.error && 'red', session.success && 'green'].filter(Boolean);

    return <div style={{ backgroundColor: color }} className={`absolute w-full text-white p-4 rounded-md mb-4 flex justify-between`}>
        {<span>{session.error || session.success}</span>}
        <X className="cursor-pointer" onClick={() => setShow(false)} />
    </div>
}
