@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Ação de Documento</h4>
        <a href="{{ route('admin.document.actions.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.document.actions.store') }}">
        @csrf
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário cadastro de ação:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Ação:</small>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Digite o tipo da ação"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" autofocus />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.document.actions.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        document.getElementById("button").style.display = "block"; 
        document.getElementById("spinner").style.display = "none"; 

        function ocultarExibir() {
            document.getElementById("button").style.display = "none"; 
            document.getElementById("spinner").style.display = "block"; 
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
