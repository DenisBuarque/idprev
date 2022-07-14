@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Evento</h4>
        <a href="{{ route('admin.events.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário cadastro de evento:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group">
                            <small>Título:</small>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Digite o título do evento"
                                class="form-control @error('title') is-invalid @enderror" maxlength="100" />
                            @error('title')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Data do evento:</small>
                            <input type="date" name="date_event" value="{{ old('date_event') }}" 
                                class="form-control @error('date_event') is-invalid @enderror" />
                            @error('date_event')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <small>Descrição sobre o evento:</small>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Imagem formato: .jpg, .jpeg, .gif, .png</small><br/>
                            <input type="file" name="image" />
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.events.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    <br/>
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
    </script>
@stop
