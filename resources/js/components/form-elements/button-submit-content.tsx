interface ButtonSubmitContentProps {
    processing: boolean;
    processingText: string;
    buttonText: string;
    classes?: string;
}

export function ButtonSubmitContent({ classes, processing, processingText, buttonText }: ButtonSubmitContentProps) {
    return (
        <div className="flex justify-end">
            <button
                type="submit"
                className={`px-4 py-2 rounded text-white focus:outline-none focus:ring-2 focus:ring-offset-2 ${classes}`}
                disabled={processing}
            >
                {processing ? processingText : buttonText}
            </button>
        </div>
    );
}
