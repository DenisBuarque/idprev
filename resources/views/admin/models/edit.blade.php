@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Modelo de Dcumento</h4>
        <a href="{{ route('admin.models.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.models.update', ['id' => $model->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição modelo de documento:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Ação:</small>
                            <select name="action_id" class="form-control">
                                @foreach($actions as $action)
                                    @if ($action->id == $model->action_id)
                                        <option value="{{$action->id}}" selected>{{$action->name}}</option>
                                    @else
                                        <option value="{{$action->id}}">{{$action->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('action_id')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Título:</small>
                            <input type="text" name="title" value="{{ $model->title ?? old('title') }}" placeholder="Digite o título do documento"
                                class="form-control @error('title') is-invalid @enderror" maxlength="100" />
                            @error('title')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <small>Anexo modelo de documento: .pdf, .doc, .docx</small>
                            <br/>
                            <input type="file" name="document" class="@error('document') is-invalid @enderror"/>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.models.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    </script>
@stop
