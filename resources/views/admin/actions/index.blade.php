@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.actions.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-action')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Título" required />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-action')
                <a href="{{ route('admin.actions.create') }}" class="btn bg-info">
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
            <h3 class="card-title">Lista de ações previdenciárias</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tipo de ação</th>
                        <th class="text-center">Documentos</th>
                        <th style="width: 160px">Criado</th>
                        <th style="width: 160px">Atualizado</th>
                        @can('edit-action')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-action')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($actions as $action)
                        <tr>
                            <td>{{ $action->name }}</td>
                            <td class="text-center">{{ count($action->modeldocs) }}</td>
                            <td>{{ $action->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $action->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='px-1'>
                                @can('edit-action')
                                    <a href="{{ route('admin.actions.edit', ['id' => $action->id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                            </td>
                            <td class='px-1'>
                                @can('delete-action')
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.actions.destroy', ['id' => $action->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <span>Nenhum registro adicionado</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $actions)
                    {{ $actions->links() }}
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
            var conf = confirm("Deseja mesmo excluir? Os dados serão perdidos e não poderam ser recuperados.");
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
