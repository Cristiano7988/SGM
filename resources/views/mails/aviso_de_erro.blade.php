<div style="font-family:Arial, Helvetica, sans-serif; font-size: 14px;">
    <h1 style="font-size: 16px;">Foi registrado um erro na aplicação</h1>
    
    <br />

    <h2 style="font-size: 14px;">Erro nº {{$erro->id}}</h2>
    <p>Ocorreu um erro na Linha <b>{{$erro->linha}}</b> do arquivo <b>{{$erro->arquivo}}</b>
    <p>A mensagem de erro obtida foi: <b>{{$erro->mensagem}}</b></p>
    <p>Usuário: @if ($user)<b>{{$user->nome}}</b> (ID: <b>#{{$user->id}}</b>) @else Ainda não cadastrado @endif</p>
    <p>enquanto acessava a rota <b>{{$erro->rota}}</b> utilizando o método <b>{{$erro->metodo}}</b></p>
    <p>O usuário parecia estar acessando através do <b>{{$erro->acessado_via}}</b></p>
    @if ($erro->corpo_da_requisicao)
        <p>Foi enviado no corpo da requisição o seguinte:</p>
        <pre style="background: lightblue;">{{$erro->corpo_da_requisicao}}</pre>
    @endif
    
    <br />
    <br />
    
    Atenciosamente,<br />
    <b>Cristiano Morales<br />
    <i>Desenvolvedor Fullstack</i></b>
</div>