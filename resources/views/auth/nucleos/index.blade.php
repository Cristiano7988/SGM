@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Núcleos
                    @if (Auth::user()->is_admin)
                        <a href="{{ route('nucleos.create') }}" class="btn btn-primary">Criar Núcleo</a>
                    @endif
                </div>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Idade Mínima</th>
                            <th scope="col">Idade Máxima</th>
                            <th scope="col">Início da Rematrícula</th>
                            <th scope="col">Fim da Rematrícula</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($nucleos as $nucleo)
                            <tr style="cursor: pointer;" onclick="location.pathname = 'nucleos/{{ $nucleo->id }}'">
                                <th scope="row">{{ $nucleo->id }}</th>
                                <td>{{ $nucleo->nome }}</td>
                                <td>{{ $nucleo->idade_minima->idade }} {{ $nucleo->idade_minima->medida_de_tempo->tipo }}</td>
                                <td>{{ $nucleo->idade_maxima->idade }} {{ $nucleo->idade_maxima->medida_de_tempo->tipo }}</td>
                                <td>{{ data_formatada($nucleo->inicio_rematricula) }}</td>
                                <td>{{ data_formatada($nucleo->fim_rematricula) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex align-self-center">
                    {{ $nucleos->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
