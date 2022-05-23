@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.leads.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisa:"
                    required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-"></i>
                        Buscar
                    </button>
                </span>
            </div>
            <a href="{{ route('admin.leads.create') }}" class="btn bg-info">
                <i class="fas fa-plus"></i> Adicionar Registro
            </a>
        </div>
    </form>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $leads_total }}</h3>
                    <p>Leads</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('admin.leads.index') }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $waiting }}</h3>
                    <p>Leads Aguardando</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="{{route('admin.leads.tag',['tag' => 2])}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $converted_lead }}</h3>
                    <p>Leads Convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <a class="small-box-footer">
                    Sem permissão <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $unconverted_lead }}</h3>
                    <p>Leads não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                <a class="small-box-footer">
                    Sem permissão <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

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
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>Franqueado</th>
                        <th>Etiqueta</th>
                        <th style='width: 160px'>Criado</th>
                        <th style='width: 160px'>Atualizado</th>
                        <th style='width: 160px' class='text-center'>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->phone }}</td>
                            <td>{{ $lead->user->name }}</td>
                            <td>
                                @php
                                    $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                    foreach ($array_tags as $key => $value) {
                                        if ($key == $lead->tag) {
                                            if ($key == 1) {
                                                echo '<small class="badge badge-info">' . $value . '</small>';
                                            } elseif ($key == 2) {
                                                echo '<small class="badge badge-warning">' . $value . '</small>';
                                            } elseif ($key == 3) {
                                                echo '<small class="badge badge-success">' . $value . '</small>';
                                            } else {
                                                echo '<small class="badge badge-danger">' . $value . '</small>';
                                            }
                                        }
                                    }
                                @endphp
                            </td>
                            <td>{{ $lead->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $lead->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{route('admin.leads.show',['id' => $lead->id])}}" class="btn btn-xs px-2 btn-light border mr-1">
                                    <i class="fa fa-comments"></i> {{ count($lead->feedbackLeads) }}
                                </a>
                                <a href="{{ route('admin.leads.edit', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-xs px-2 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.leads.destroy', ['id' => $lead->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs px-2">
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
            var conf = confirm("Deseja mesmo excluir? Os dados serão perdidos e não poderam ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@stop
