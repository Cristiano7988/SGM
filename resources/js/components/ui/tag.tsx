export function Tag({ background, children, title }: { background: string, children: string | undefined, title: string }) {
  if (!children) return;

  return (
    <span
      style={{ background }}
      className="text-sm p-1 rounded-[4px]"
      children={children}
      title={title}
    />
  )
}
