@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.worksheets.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-worksheet')
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
            @can('create-worksheet')
                <a href="{{ route('admin.worksheets.create') }}" class="btn bg-info">
                    <i class="fa fa-plus mr-2"></i> Adicionar Registro
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
            <h3 class="card-title">Lista de Planilhas</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th style="width: 160px;">Criado</th>
                        <th style="width: 160px;">Atualizado</th>
                        @can('edit-worksheet')
                            <th style='width: 60px' class='text-center'>Down</th>
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-worksheet')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($worksheets as $worksheet)
                        <tr>
                            <td>{{ $worksheet->title }}</td>
                            <td>{{ $worksheet->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $worksheet->updated_at->format('d/m/Y H:m:s') }}</td>
                            @can('edit-worksheet')
                                <td class='px-1'>
                                    <a href="{{ Storage::url($worksheet->arquivo) }}"
                                        class="btn btn-default btn-xs btn-block">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('edit-worksheet')
                                <td class="px-1">
                                    <a href="{{ route('admin.worksheets.edit', ['id' => $worksheet->id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-worksheet')
                                <td class="px-1">
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.worksheets.destroy', ['id' => $worksheet->id]) }}">
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
                @if (!$search && $worksheets)
                    {{ $worksheets->links() }}
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
