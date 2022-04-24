@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Serviço</h4>
        <a href="{{ route('admin.services.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.services.update', ['id' => $service->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de serviço:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group m-0">
                            <small>Título do serviço:</small>
                            <input type="text" name="title" value="{{ $service->title ?? old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" maxlength="50" />
                            @error('title')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Ativo:</small>
                            <select name="active" class="form-control">
                                <option value="yes" @if($service->active == 'yes') selected @endif>Ativo</option>
                                <option value="no"  @if($service->active == 'no') selected @endif>Inativo</option>
                            </select>
                            @error('active')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Descrição do serviço:</small>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ $service->description ?? old('description') }}</textarea>
                            @error('description')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.services.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar dados
                </button>
            </div>
        </div>
    </form>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        
    </script>
@stop
