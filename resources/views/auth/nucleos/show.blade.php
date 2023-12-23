@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Núcleo {{ $nucleo->nome }}
                    @if (Auth::user()->is_admin)
                        <div>
                            <a href="{{ route('nucleos.edit', $nucleo) }}" class="btn btn-primary">Editar</a>
                            <form method="POST" onsubmit="return confirm('Deletar núcleo {{ $nucleo->nome }}?')" class="d-inline" action="{{ route('nucleos.destroy', $nucleo) }}">
                                @csrf
                                @method('DELETE')
                                <input type="submit"  class="btn btn-danger" value="Deletar" />
                            </form>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start gap-3">
                        @if ($nucleo->imagem)
                            <img class="mr-3" width="150" src="/storage/{{$nucleo->imagem}}" />
                        @endif
                        <div class="media-body">
                            <pre style="font-family: 'Nunito', sans-serif; white-space: break-spaces; font-size: .9rem;">{{ $nucleo->descricao }}</pre>
                        </div>
                    </div>

                    <hr />
                    
                    <h5 class="card-title">Período de matrículas</h5>

                    <div class="mb-3 d-flex gap-3">
                        <div>
                            <b>Disponível de:</b>
                            <span>
                                {{ data_formatada($nucleo->inicio_rematricula) }}
                            </span>
                        </div>

                        <div>
                            <b>Até:</b>
                            <span>
                                {{ data_formatada($nucleo->fim_rematricula) }}
                            </span>
                        </div>
                    </div>

                    <hr />
                    
                    <h5 class="card-title">Público alvo</h5>
                    
                    <div class="mb-3 d-flex gap-3">
                        <div>
                            <b>De:</b>
                            <span>
                                {{ $nucleo->idade_minima->idade }} {{ $nucleo->idade_minima->medida_de_tempo->tipo }}
                            </span>
                        </div>

                        <div>
                            <b>Até:</b>
                            <span>
                                {{ $nucleo->idade_maxima->idade }} {{ $nucleo->idade_maxima->medida_de_tempo->tipo }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
