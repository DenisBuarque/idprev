@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Prazo</h4>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 900px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 900px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.terms.update', ['id' => $term->id]) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="lead_id" value="{{$term->lead_id}}" />
        <div class="card card-info" style="max-width: 900px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de prazo:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Prazo: *</small>
                            <input type="date" name="term" value="{{ $term->term ?? old('term') }}"
                                class="form-control @error('term') is-invalid @enderror" />
                            @error('term')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Data Audiência:</small>
                            <input type="date" name="audiencia" value="{{ $term->audiencia ?? old('audiencia') }}" class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Horários:</small>
                            <input type="time" name="hour" value="{{ $term->hour ?? old('hour') }}" class="form-control" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Etiqueta:</small>
                            <select name="tag" class="form-control">
                                <option value="1" @if ($term->tag == 1) selected @endif>Aguardando</option>
                                <option value="2" @if ($term->tag == 2) selected @endif>Cumprido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Endereço:</small>
                            <input type="text" name="address" value="{{ $term->address ?? old('address') }}" class="form-control" maxlength="250" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Comentários: *</small>
                            <textarea name="comments" class="form-control @error('comments') is-invalid @enderror" class="w-full h-28">{{$term->comments ?? old('comments')}}</textarea>
                            @error('comments')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-info" style="max-width: 900px; margin: auto">

            <div class="card-footer">
                <a href="{{ route('admin.clients.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button id="button" type="submit" onClick="ocultarExibir()" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save mr-2"></i>
                    Salvar dados
                </button>
                <a id="spinner" class="btn btn-md btn-info float-right text-center">
                    <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </a>
            </div>
        </div>

    </form>

    <br />

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function ocultarExibir() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }
    </script>
@stop
