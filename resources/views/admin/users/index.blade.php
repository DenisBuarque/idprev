@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.users.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-user')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Nome" required />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-user')
                <a href="{{ route('admin.users.create') }}" class="btn bg-info">
                    <i class="fa fa-plus"></i> Adicionar Registro
                </a>
            @endcan
        </div>
    </form>
@stop

@section('content')

    @if (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div id="message" class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de usuários do sistema</h3>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        @can('edit-user')
                            <th style="width: 60px; text-align: center">Edit</th>
                        @endcan
                        @can('delete-user')
                            <th style="width: 40px; text-align: center">Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                @if (isset($user->image))
                                    <img src="{{asset('storage/' . $user->image) }}" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @endif
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $user->updated_at->format('d/m/Y H:m:s') }}</td>
                            @can('edit-user')
                                <td class="px-1">
                                    <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" title="Alterar registro"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-user')
                                <td class='px-1'>
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.users.destroy', ['id' => $user->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block btn-xs" title="Excluir registro">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-3">
                                <span>Nenhum registro encontrado.</span>
                            </td>
                        </tr>
                    @endforelse
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

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
