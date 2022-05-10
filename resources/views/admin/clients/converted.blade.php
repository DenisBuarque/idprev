@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.converted') }}">
        <div style="display: flex; justify-content: start;">
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
        </div>
    </form>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Clientes convertidos</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Nome</th>
                        <th>Franqueado</th>
                        <th>Local</th>
                        <th>Etiqueta</th>
                        <th>Situação</th>
                        <th class='text-center'>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->user->name }}</td>
                            <td>{{ $lead->city.'/'.$lead->state }}</td>
                            <td>
                                @php
                                    $array_tags = [1 => 'Novo', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                    foreach ($array_tags as $key => $value) {
                                        if ($key == $lead->tag) {
                                            if($key == 2){
                                                echo '<small class="badge badge-warning">'.$value.'</small>';
                                            } elseif($key == 3){
                                                echo '<small class="badge badge-success">'.$value.'</small>';
                                            } elseif($key == 4){
                                                echo '<small class="badge badge-danger">'.$value.'</small>';
                                            }
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
                                <a href="{{ route('admin.clients.edit', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
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

    </script>
@stop
