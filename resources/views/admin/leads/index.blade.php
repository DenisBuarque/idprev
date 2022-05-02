@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.leads.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisa."
                    required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Buscar</button>
                </span>
            </div>
            <a href="{{ route('admin.leads.create') }}" class="btn bg-info">Adicionar Registro</a>
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
            <h3 class="card-title">Lista de Leads</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class='text-center'>Telefone</th>
                        <th>Etiqueta</th>
                        <th>Situação</th>
                        <th class='text-center'>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td class='text-center'>{{ $lead->phone }}</td>
                            <td>
                                @php
                                    $array_tags = [1 => 'Novo', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                    foreach ($array_tags as $key => $value) {
                                        if ($key == $lead->tag) {
                                            echo $value;
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                    foreach ($array_situations as $key => $value) {
                                        if ($key == $lead->situation) {
                                            echo $value;
                                        }
                                    }
                                @endphp
                            </td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{ route('admin.leads.edit', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.leads.destroy', ['id' => $lead->id]) }}">
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
                @if (!$search && $leads)
                    {{ $leads->links() }}
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