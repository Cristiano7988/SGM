@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    Adicionar Público Alvo
                </div>
                <div class="card-body">

                    <form method="POST" action="{{ route('idades_minimas.store') }}">
                        @csrf
                        <div id="publico_alvo" class="row mb-3">
                            <label for="idade" class="col-md-2 col-form-label text-md-end">De</label>

                            <div class="col-md-4">
                                <input type="number" min="1" class="form-control" name="idade">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="medida_de_tempo_id">
                                    @foreach ($medidasDeTempo as $medidaDeTempo)
                                        <option value="{{ $medidaDeTempo->id }}">{{ $medidaDeTempo->tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" value="Salvar" />
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('idades_maximas.store') }}">
                        @csrf
                        <div class="row mb-3">
                            <label for="idade" class="col-md-2 col-form-label text-md-end">Até</label>
                            
                            <div class="col-md-4">
                                <input type="number" min="2" class="form-control" name="idade">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" name="medida_de_tempo_id">
                                    @foreach ($medidasDeTempo as $medidaDeTempo)
                                        <option value="{{ $medidaDeTempo->id }}">{{ $medidaDeTempo->tipo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" value="Salvar" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Editar Núcleo
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('nucleos.update', $nucleo) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row mb-3">
                            <label for="nome" class="col-md-2 col-form-label text-md-end">Nome</label>

                            <div class="col-md-8">
                                <input id="nome" type="text" class="form-control @error('nome') is-invalid @enderror" name="nome" required autocomplete="nome" value="{{ $nucleo->nome }}">

                                @error('nome')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="imagem" class="col-md-2 col-form-label text-md-end">Imagem</label>

                            <div class="col-md-8">
                                @if ($nucleo->imagem)
                                    <img width="150" class="border rounded mb-3" src="/storage/{{ $nucleo->imagem }}" />
                                @endif
                                <input id="imagem" type="file" class="form-control @error('imagem') is-invalid @enderror" name="imagem" autocomplete="imagem" >

                                @error('imagem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="descricao" class="col-md-2 col-form-label text-md-end">Descrição</label>

                            <div class="col-md-8">
                                <textarea id="descricao" min="20" rows="6" class="form-control @error('descricao') is-invalid @enderror" name="descricao" autocomplete="descricao">{{ $nucleo->descricao }}</textarea>

                                @error('descricao')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <br />
                        
                        <h5 class="card-title text-center">Período de matrículas</h5>

                        <div class="row mb-3">
                            <label for="inicio_rematricula" class="col-md-2 col-form-label text-md-end">Início</label>

                            <div class="col-md-8">
                                <input id="inicio_rematricula" type="date" min="{{ date('Y-m-d', strtotime('-365 days')) }}" max="{{ date('Y-m-d', strtotime('+365 days')) }}" class="form-control @error('inicio_rematricula') is-invalid @enderror" name="inicio_rematricula" required autocomplete="inicio_rematricula" value="{{ $nucleo->inicio_rematricula }}">

                                @error('inicio_rematricula')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="fim_rematricula" class="col-md-2 col-form-label text-md-end">Fim</label>
                            
                            <div class="col-md-8">
                                <input id="fim_rematricula" type="date" min="{{ date('Y-m-d', strtotime('-365 days')) }}" max="{{ date('Y-m-d', strtotime('+365 days')) }}" class="form-control @error('fim_rematricula') is-invalid @enderror" name="fim_rematricula" required autocomplete="fim_rematricula" value="{{ $nucleo->fim_rematricula }}">
                                
                                @error('fim_rematricula')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <br />
                        
                        <h5 class="card-title text-center">Público alvo</h5>

                        <div class="row mb-3">
                            <label for="idade_minima_id" class="col-md-2 col-form-label text-md-end">De</label>
                            
                            <div class="col-md-8">
                                <select class="form-select" name="idade_minima_id" required>
                                    <option value="">Selecione uma idade mínima</option>
                                    @foreach ($idadesMinimas as $idade_minima)
                                        <option @if ($nucleo->idade_minima->id == $idade_minima->id) selected @endif value="{{ $idade_minima->id }}">{{ $idade_minima->idade }} {{ $idade_minima->medida_de_tempo->tipo }}</option>
                                    @endforeach
                                </select>
                                    
                                @error('idade_minima_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            
                        <div class="row mb-3">
                            <label for="idade_maxima_id" class="col-md-2 col-form-label text-md-end">Até</label>
                            
                            <div class="col-md-8">
                                <select class="form-select" name="idade_maxima_id" required>
                                    <option value="">Selecione uma idade mínima</option>
                                    @foreach ($idadesMaximas as $idade_maxima)
                                        <option @if ($nucleo->idade_maxima->id == $idade_maxima->id) selected @endif value="{{ $idade_maxima->id }}">{{ $idade_maxima->idade }} {{ $idade_maxima->medida_de_tempo->tipo }}</option>
                                    @endforeach
                                </select>

                                @error('idade_maxima_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <input type="submit" class="btn btn-primary" value="Salvar" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
