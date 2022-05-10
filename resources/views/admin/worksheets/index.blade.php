@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.worksheets.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control"
                    placeholder="Título da planilha" required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat"> 
                        <i class="fa fa-search"></i> Buscar
                    </button>
                </span>
            </div>
            <a href="{{ route('admin.worksheets.create') }}" class="btn bg-info">Adicionar Registro</a>
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
            <h3 class="card-title">Lista de Planilhas</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th class='text-center' style="width: 100px;" >Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($worksheets as $worksheet)
                        <tr>
                            <td>{{ $worksheet->title }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{ route('admin.worksheets.download', ['id' => $worksheet->id]) }}"
                                    class="btn btn-default btn-sm mr-1">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('admin.worksheets.edit', ['id' => $worksheet->id]) }}"
                                    class="btn btn-info btn-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.worksheets.destroy', ['id' => $worksheet->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                
                            </td>
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
    </script>
@stop
