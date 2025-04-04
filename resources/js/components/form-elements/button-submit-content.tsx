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
                className={`${classes} cursor-pointer text-white font-semibold py-2 px-4 border border-transparent rounded-md shadow-sm text-sm`}
                disabled={processing}
            >
                {processing ? processingText : buttonText}
            </button>
        </div>
    );
}
