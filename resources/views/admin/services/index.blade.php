@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.services.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-service')
                <div class="input-group" style="width: 30%">
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Título do serviço" required />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search"></i>  Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-service')
                <a href="{{ route('admin.services.create') }}" class="btn bg-info">
                    <i class="fa fa-plus"></i> Adicionar Registro
                </a>
            @endcan
        </div>
    </form>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Serviços</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Status</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->title }}</td>
                            <td>
                                @if ($service->active == 'yes')
                                    Ativo
                                @else
                                    Inativo
                                @endif
                            </td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                @can('edit-service')
                                    <a href="{{ route('admin.services.edit', ['id' => $service->id]) }}"
                                        class="btn btn-info btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                                @can('delete-service')
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.services.destroy', ['id' => $service->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $services)
                    {{ $services->links() }}
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm("Deseja mesmo excluir? Os dados seram pedidos e não poderam ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
