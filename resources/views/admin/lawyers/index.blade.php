@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.lawyers.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-lawyer')
                <div class="input-group" style="width: 40%">
                    <select name="franchisee" class="form-control mr-1">
                        <option></option>
                        @foreach($franchisees as $franchisee)
                            <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                        @endforeach
                    </select>
                    <input type="search" name="search" class="form-control" />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i>
                            Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-lawyer')
                <a href="{{ route('admin.lawyers.create') }}" class="btn bg-info">
                    <i class="fa fa-plus mr-1"></i>Adicionar Registro
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
            <h3 class="card-title">Lista de advogados relacionado aos franqueados:</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Franqueado</th>
                        <th>Advogado</th>
                        <th class="text-center">OAB</th>
                        <th style="width: 160px;">Criado</th>
                        <th style="width: 160px;">Atualizado</th>
                        @can('edit-lawyer')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-lawyer')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lawyers as $lawyer)
                        <tr>
                            <td>
                                @if (isset($lawyer->user->image))
                                    <img src="{{asset('storage/' . $lawyer->user->image) }}" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @endif
                                {{ $lawyer->user->name }}
                            </td>
                            <td>
                                @if (isset($lawyer->image))
                                    <img src="{{asset('storage/' . $lawyer->image) }}" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @endif
                                {{ $lawyer->name }}
                            </td>
                            <td class="text-center">{{ $lawyer->oab }}</td>
                            <td>{{ $lawyer->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $lawyer->updated_at->format('d/m/Y H:m:s') }}</td>
                            @can('edit-lawyer')
                                <td class='px-1'>
                                    <a href="{{ route('admin.lawyers.edit', ['id' => $lawyer->id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-lawyer')
                                <td class='px-1'>
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.lawyers.destroy', ['id' => $lawyer->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if ($lawyers)
                    {{ $lawyers->links() }}
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
