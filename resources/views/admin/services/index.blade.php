@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.services.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-service')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" class="form-control"
                        placeholder="Título" required />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search"></i> Buscar
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
                        @can('edit-service')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-service')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td>{{ $service->title }}</td>
                            <td>
                                @if ($service->active == 'yes')
                                    Ativo
                                @else
                                    Inativo
                                @endif
                            </td>
                            @can('edit-service')
                                <td class='px-1'>
                                    <a href="{{ route('admin.services.edit', ['id' => $service->id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-service')
                                <td class='px-1'>
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.services.destroy', ['id' => $service->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <span>Nenhum registro adicionado</span>
                            </td>
                        </tr>
                    @endforelse
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
