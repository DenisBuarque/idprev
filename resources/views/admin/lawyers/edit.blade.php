@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Advogado</h4>
        <a href="{{ route('admin.lawyers.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.lawyers.update', ['id' => $lawyer->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de Advogado:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Nome:</small>
                            <input type="text" name="name" value="{{ $lawyer->name ?? old('name') }}"
                                placeholder="Digite o título do evento"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>OAB:</small>
                            <input type="text" name="oab" value="{{ $lawyer->oab ?? old('oab') }}"
                                class="form-control @error('oab') is-invalid @enderror" />
                            @error('oab')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Franqueado:</small>
                            <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                @foreach ($users as $user)
                                    @if ($lawyer->user_id == $user->id)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('advisor_id')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <small>Foto do advogado:</small>
                        <div class="form-group">
                            <input type="file" name="image" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.lawyers.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
