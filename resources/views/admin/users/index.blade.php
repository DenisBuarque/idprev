@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Nome do usuário" required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </span>
            </div>

            @can('create-data')
                <a href="{{ route('admin.users.create') }}" class="btn bg-info">
                    <i class="fa fa-plus"></i> Adicionar Registro
                </a>
            @endcan

        </div>
    </form>
@stop

@section('content')

    @if (session('alert'))
        <div class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('success'))
        <div class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de administradores do sistema</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th style='width: 160px'>Criado</th>
                        <th style='width: 160px'>Atualizado</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $user->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>

                                    <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}"
                                        class="btn btn-info btn-xs px-2 mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.users.destroy', ['id' => $user->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-2 btn-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm("Deseja mesmo excluir? Os dados serão perdidos e não poderam ser recupeados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@stop
