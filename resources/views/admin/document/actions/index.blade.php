@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.document.actions.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Título da ação" required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </span>
            </div>
            <a href="{{ route('admin.document.actions.create') }}" class="btn bg-info">
                <i class="fa fa-plus"></i> Adicionar Registro
            </a>
        </div>
    </form>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de tipos de ações</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tipo de ação</th>
                        <th style="width: 160px">Criado</th>
                        <th style="width: 160px">Atualizado</th>
                        <th class='text-center' style="width: 100px;" >Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $action)
                        <tr>
                            <td>{{ $action->name }}</td>
                            <td>{{ $action->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $action->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{ route('admin.document.actions.edit', ['id' => $action->id]) }}"
                                    class="btn btn-info btn-xs py-2 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.document.actions.destroy', ['id' => $action->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs py-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

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
    </script>
@stop
