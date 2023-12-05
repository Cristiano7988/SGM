<div style="font-family:Arial, Helvetica, sans-serif; font-size: 14px;">
    <h1 style="font-size: 16px;">{{$tipo}} de nº {{$item->id}} excluído(a)</h1>
    
    <br />

    <p>Esta é uma cópia de segurança para que você não perca informações relevantes caso haja algum item relacionado à este.</p>
    <p>Detalhes do(a) {{$tipo}}:</p>
    @foreach(explode(',"', $item) as $key)
    <p style="background: lightblue; padding: 5px; line-height: 30px;">{{$key}}</p>
    @endforeach
    <br />
    <br />
    
    Atenciosamente,<br />
    <b>Cristiano Morales<br />
    <i>Desenvolvedor Fullstack</i></b>
</div>