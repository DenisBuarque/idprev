@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-client')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" class="form-control" placeholder="Pesquisa." />
                    <select name="situation" class="form-control" style="margin: 0 2px;">
                        <option></option>
                        <option value="1">Andamento em ordem</option>
                        <option value="2">Aguardando cumprimento</option>
                        <option value="3">Finalizado procedente</option>
                        <option value="4">Finalizado improcedente</option>
                        <option value="5">Recursos</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i>
                            Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-client')
                <a href="{{ route('admin.clients.create') }}" class="btn bg-info">
                    <i class="fa fa-plus mr-1"></i> Adicionar Registro
                </a>
            @endcan
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
                <a href="{{ route('admin.clients.tag', ['tag' => 2]) }}" class="small-box-footer">
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
                <a href="{{ route('admin.clients.tag', ['tag' => 3]) }}" class="small-box-footer">
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
                <a href="{{ route('admin.clients.tag', ['tag' => 4]) }}" class="small-box-footer">
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
                    <h3 class="card-title">Lista de clientes</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Franqueado</th>
                                <th>Cliente</th>
                                <th></th>
                                <th>Situação</th>
                                <th class='text-center' style="width: 130px;">Anexos</th>
                                @can('comments-client')
                                    <th style='width: 60px' class='text-center'></th>
                                @endcan
                                @can('edit-client')
                                    <th style='width: 50px' class='text-center'>Edit</th>
                                @endcan
                                @can('delete-client')
                                    <th style='width: 50px' class='text-center'>Del</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leads as $lead)
                                <tr>
                                    <td>
                                        @if (isset($lead->user->image))
                                            <img src="{{ asset('storage/' . $lead->user->image) }}" alt="Foto"
                                                class="img-circle mr-2" style="width: 28px; height: 28px;">
                                        @else
                                            <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto"
                                                class="img-circle mr-2" style="width: 28px; height: 28px;">
                                        @endif
                                        {{ $lead->user->name }}
                                    </td>
                                    <td>{{ $lead->name }}</td>
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
                                    <td class="text-center">
                                        @php
                                            $docs = count($lead->photos);
                                            $anexos = 0;
                                            foreach ($models as $model) {
                                                if ($model->action_id == $lead->action) {
                                                    $anexos += 1;
                                                }
                                            }
                                            echo $docs . ' de ' . $anexos;
                                        @endphp
                                    </td>
                                    <td class='px-1'>
                                        @can('comments-client')
                                            <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                                class="btn btn-xs border btn-block"><i class="fa fa-comments"></i>
                                                {{ count($lead->feedbackLeads) }}</a>
                                        @endcan
                                    </td>
                                    <td class='px-1'>
                                        @can('edit-client')
                                            <a href="{{ route('admin.clients.edit', ['id' => $lead->id]) }}"
                                                class="btn btn-info btn-xs btn-block">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </td>
                                    <td class='px-1'>
                                        @can('delete-client')
                                            <form method="POST" onsubmit="return(confirmaExcluir())"
                                                action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs btn-block">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-6">
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('admin.clients.situation', ['situation' => 1]) }}">
                                <span class="info-box-text">Andamento em Ordem</span>
                            </a>
                            <span class="info-box-number">{{ $progress }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-clock"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('admin.clients.situation', ['situation' => 2]) }}">
                                <span class="info-box-text">Aguardando Cumprimento</span>
                            </a>
                            <span class="info-box-number">{{ $awaiting_fulfillment }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('admin.clients.situation', ['situation' => 3]) }}">
                                <span class="info-box-text">Finalizado Procedente</span>
                            </a>
                            <span class="info-box-number">{{ $procedente }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('admin.clients.situation', ['situation' => 4]) }}">
                                <span class="info-box-text">Finalizado Improcedente</span>
                            </a>
                            <span class="info-box-number">{{ $improcedente }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <a href="{{ route('admin.clients.situation', ['situation' => 5]) }}">
                                <span class="info-box-text">Recursos</span>
                            </a>
                            <span class="info-box-number">{{ $resources }}</span>
                        </div>
                    </div>
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
