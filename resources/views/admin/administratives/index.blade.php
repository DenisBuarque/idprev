@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<form method="GET" action="{{ route('admin.administratives.index') }}">
    <div style="display: flex; justify-content: space-between;">
        @can('search-administrative')
            <div class="input-group" style="width: 40%">
                <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Dados do cliente"
                    required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-"></i>
                        Buscar
                    </button>
                </span>
            </div>
        @endcan
        @can('create-administrative')
            <a href="{{ route('admin.administratives.create') }}" class="btn bg-info">
                <i class="fas fa-plus"></i> Adicionar Registro
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
            <h3 class="card-title">Pedidos Administrativos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Franqueado</th>
                        <th>Cliente</th>
                        <th>Resultado</th>
                        <th>Data inicio</th>
                        <th>Data Concessão</th>
                        <th>Honorários</th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        @can('edit-lead')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-lead')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($administratives as $administrative)
                            <tr>
                                <td>{{ $administrative->user->name }}</td>
                                <td>{{ $administrative->name }}</td>
                                <td>
                                    @if ($administrative->results == 1)
                                        Deferido
                                    @else
                                        Indeferido
                                    @endif
                                </td>
                                <td>{{ $administrative->initial_date->format('d/m/Y') }}</td>
                                <td>{{ $administrative->concessao_date->format('d/m/Y') }}</td>
                                <td>{{ $administrative->fees }}</td>
                                <td>{{ $administrative->created_at->format('d/m/Y H:m:s') }}</td>
                                <td>{{ $administrative->updated_at->format('d/m/Y H:m:s') }}</td>
                                @can('edit-administrative')
                                    <td class='px-1'>
                                        <a href="{{route('admin.administratives.edit',['id' => $administrative->id])}}"
                                            class="btn btn-info btn-xs btn-block">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                @endcan
                                @can('delete-administrative')
                                    <td class='px-1'>
                                        <form method="POST" action="{{route('admin.administratives.destroy',['id' => $administrative->id])}}"
                                            onsubmit="return(confirmaExcluir())">
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
                        <td class="text-center" colspan="10">
                            Nenhum registro encontrado
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $administratives)
                    {{ $administratives->links() }}
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
            var conf = confirm("Deseja mesmo excluir? Todos os dados serão perdidos e não pederão ser recuperados.");
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
