export default function ErrorLabel({ error }: { error?: string; }) {
    if (!error) return null;

    return <p className="text-red-500 text-sm">{error}</p>
}
