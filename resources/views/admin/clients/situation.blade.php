@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.index') }}">
        <div style="display: flex; justify-content: space-between;">
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
            <a href="{{ route('admin.clients.create') }}" class="btn bg-info">
                <i class="fa fa-plus mr-1"></i> Adicionar Registro
            </a>
        </div>
    </form>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ count($leads) }}</h3>
                    <p>Clientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('admin.clients.index') }}" class="small-box-footer">
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
                <a href="{{route('admin.clients.tag',['tag' => 2])}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $converted_lead }}</h3>
                    <p>Clientes Convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <a href="{{route('admin.clients.tag',['tag' => 3])}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $unconverted_lead }}</h3>
                    <p>Clientes não convetidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                <a href="{{route('admin.clients.tag',['tag' => 4])}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
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

    <div class="row">

        <div class="col-lg-9 col-md-9 col-6">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de clientes convertidos</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Franqueado</th>
                                <th>Situação</th>
                                <th style="width: 130px;">Anexos</th>
                                <th class='text-center' style="width: 140px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leads as $lead)
                                <tr>
                                    <td>{{ $lead->name }}</td>
                                    <td>{{ $lead->user->name }}</td>
                                    <td>
                                        @php
                                            $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                            foreach ($array_situations as $key => $value) {
                                                if ($key == $lead->situation) {
                                                    echo $value;
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            $docs = count($lead->photos);
                                            $anexos = 0;
                                            foreach($models as $model){
                                                if($model->action_id == $lead->action){
                                                    $anexos += 1;
                                                }
                                            }

                                            if($anexos > $docs){
                                                $falta = $anexos - $docs;
                                                echo $docs .' <i class="fas fa-paperclip"></i> falta ' . $falta . ' doc.';
                                            } else {
                                                echo '<i class="fas fa-thumbs-up"></i> '.$docs.' anexo(s)';
                                            }
                                        @endphp
                                    </td>
                                    <td class='d-flex flex-row align-content-center justify-content-center'>
                                        <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                            class="btn btn-xs border mr-1"><i class="fa fa-comments"></i>
                                            {{ count($lead->feedbackLeads) }}</a>
                                        <a href="{{ route('admin.clients.edit', ['id' => $lead->id]) }}"
                                            class="btn btn-info btn-xs px-2 mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" onsubmit="return(confirmaExcluir())"
                                            action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
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

        </div>

        <div class="col-lg-3 col-md-3 col-6">
            <div class="info-box mb-3 bg-info">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <a href="{{route('admin.clients.situation',['situation' => 1])}}"><span class="info-box-text text-white">Andamento em Ordem</span></a>
                    <span class="info-box-number">{{ $progress }}</span>
                </div>
            </div>
            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <a href="{{route('admin.clients.situation',['situation' => 2])}}"><span class="info-box-text text-dark">Aguardando Cumprimento</span></a>
                    <span class="info-box-number">{{ $awaiting_fulfillment }}</span>
                </div>
            </div>
            <div class="info-box mb-3 bg-success">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <a href="{{route('admin.clients.situation',['situation' => 3])}}"><span class="info-box-text text-white">Finalizado Procedente</span></a>
                    <span class="info-box-number">{{ $procedente }}</span>
                </div>
            </div>
            <div class="info-box mb-3 bg-danger">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <a href="{{route('admin.clients.situation',['situation' => 4])}}"><span class="info-box-text text-white">Finalizado Improcedente</span></a>
                    <span class="info-box-number">{{ $improcedente }}</span>
                </div>
            </div>
            <div class="info-box mb-3 bg-warning">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <a href="{{route('admin.clients.situation',['situation' => 5])}}"><span class="info-box-text text-dark">Recursos</span></a>
                    <span class="info-box-number">{{ $resources }}</span>
                </div>
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
