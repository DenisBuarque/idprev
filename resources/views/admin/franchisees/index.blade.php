@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.franchisees.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-franchisee')
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisa."
                    required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-1"></i>
                        Buscar
                    </button>
                </span>
            </div>
            @endcan
            @can('create-franchisee')
            <a href="{{ route('admin.franchisees.create') }}" class="btn bg-info">
                <i class="fa fa-plus mr-1"></i> Adicionar Registro
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
            <h3 class="card-title">Lista de Franqueados Conveniados</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th class='text-center'>Lead(s)</th>
                        <th style="width: 160px;">Criado</th>
                        <th style="width: 160px;">Atualizado</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td class='text-center'>{{ count($user->leads) }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $user->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                @can('edit-franchisee')
                                <a href="{{ route('admin.franchisees.edit', ['id' => $user->id]) }}"
                                    class="btn btn-info btn-xs px-2 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete-franchisee')
                                <form method="POST" action="{{ route('admin.franchisees.destroy', ['id' => $user->id]) }}"
                                    onsubmit="return(confirmaExcluir())">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs px-2">
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
                @if (!$search && $users)
                    {{ $users->links() }}
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
