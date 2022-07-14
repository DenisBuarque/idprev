@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-client')
                <div class="input-group" style="width: 50%">
                    @can('list-user')
                        <select name="franchisee" class="form-control mr-1">
                            <option></option>
                            @foreach ($franchisees as $franchisee)
                                <option value="{{ $franchisee->id }}">{{ $franchisee->name }}</option>
                            @endforeach
                        </select>
                    @endcan
                    <select name="situation" class="form-control mr-1">
                        <option></option>
                        <option value="1">Andamento em ordem</option>
                        <option value="2">Aguardando cumprimento</option>
                        <option value="3">Finalizado procedente</option>
                        <option value="4">Finalizado improcedente</option>
                        <option value="5">Recusos</option>
                    </select>
                    <input type="search" name="search" class="form-control" placeholder="Cliente" />
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
                @if ($waiting != 0)
                    <a href="{{ route('admin.leads.tag', ['tag' => 2]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
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
                @if ($converted_lead != 0)
                    <a href="{{ route('admin.clients.tag', ['tag' => 3]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
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
                @if ($unconverted_lead != 0)
                    <a href="{{ route('admin.clients.tag', ['tag' => 4]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endif
            </div>
        </div>
    </div>

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

    <div class="row">
        <div class="col-lg-9 col-md-9 col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de clientes</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Franqueado</th>
                                <th>Cliente</th>
                                <th>Situação</th>
                                <th class='text-center' style="width: 130px;">Anexos</th>
                                <th style='width: 60px' class='text-center'></th>
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
                                    <td>
                                        <span>{{ $lead->name }}</span><br />
                                        @isset($lead->address)
                                            <small>{{ $lead->address }}, nº {{ $lead->number }}, {{ $lead->district }},
                                                {{ $lead->city }}, {{ $lead->state }}</small>
                                        @endisset
                                    </td>
                                    <td>
                                        @php
                                            $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                            foreach ($array_situations as $key => $value) {
                                                if ($key == $lead->situation) {
                                                    echo '<span>' . $value . '</span>';
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
                                        <a href="#" class="btn btn-xs border btn-block" title="Prazos"
                                            data-toggle="modal" data-target="#modal-lead{{ $lead->id }}">
                                            <i class="fa fa-clock"></i>
                                            {{ count($lead->terms) }}
                                        </a>
                                    </td>
                                    @can('comments-client')
                                        <td class='px-1'>
                                            <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                                title="Comentários" class="btn btn-xs border btn-block"><i
                                                    class="fa fa-comments"></i>
                                                {{ count($lead->feedbackLeads) }}
                                            </a>
                                        </td>
                                    @endcan
                                    @can('edit-client')
                                        <td class='px-1'>
                                            <a href="{{ route('admin.clients.edit', ['id' => $lead->id]) }}"
                                                class="btn btn-info btn-xs btn-block">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    @endcan
                                    @can('delete-client')
                                        <td class='px-1'>
                                            <form method="POST" onsubmit="return(confirmaExcluir())"
                                                action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs btn-block">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum registro encontrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @forelse ($leads as $lead)
                        <div class="modal fade" id="modal-lead{{ $lead->id }}" style="display: none;"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header bg-info">
                                        <h4 class="modal-title">Prazos: {{ $lead->name }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">x</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Comentário</th>
                                                    <th>Prazo</th>
                                                    <th>Audiência</th>
                                                    <th>Hora</th>
                                                    <th>Endereço</th>
                                                    <th>Status</th>
                                                    @can('edit-client')
                                                        <th style='width: 50px' class='text-center'>Edit</th>
                                                    @endcan
                                                    @can('delete-client')
                                                        <th style='width: 50px' class='text-center'>Del</th>
                                                    @endcan
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($terms as $item)
                                                    @if ($lead->id == $item->lead_id)
                                                        <tr>
                                                            <td>{{ $item->comments }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($item->term)->format('d/m/Y') }}
                                                            </td>
                                                            <td>
                                                                @isset($item->audiencia)
                                                                    {{ \Carbon\Carbon::parse($item->audiencia)->format('d/m/Y') }}
                                                                @endisset
                                                            </td>
                                                            <td>
                                                                @isset($item->hour)
                                                                    {{ $item->hour }}
                                                                @endisset
                                                            </td>
                                                            <td>{{ $item->address }}</td>
                                                            <td>
                                                                @if ($item->tag == 1)
                                                                    <span>Aguardando</span>
                                                                @else
                                                                    <span>Cumprido</span>
                                                                @endif
                                                            </td>
                                                            @can('edit-client')
                                                                <td class='px-1'>
                                                                    <a href="{{ route('admin.terms.edit', ['id' => $item->id]) }}"
                                                                        class="btn btn-info btn-xs btn-block">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </td>
                                                            @endcan
                                                            @can('delete-client')
                                                                <td class='px-1'>
                                                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                                                        action="{{ route('admin.terms.delete', ['id' => $item->id]) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-danger btn-xs btn-block">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            @endcan
                                                        </tr>
                                                    @endif
                                                @empty
                                                    <tr>
                                                        <td colspan="4">
                                                            <span class="text-center">Renhum prazo adicionado.</span>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Fechar</button>
                                        @can('create-client')
                                            <a href="{{ route('admin.terms.create', ['id' => $lead->id]) }}"
                                                class="btn btn-md btn-info">
                                                <i class="fas fa-plus mr-2"></i>
                                                Adicionar Prazo
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-3 mr-3 ml-3">
                        @if ($leads)
                            {{ $leads->links() }}
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-6">

            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Prazos não cumpridos</h3>
                    <div class="card-tools"></div>
                </div>
                <div class="card-body p-0" style="display: block;">
                    <table class="table">
                        <tbody>
                            @forelse ($terms_cumpridos as $item)
                                <tr>
                                    <td>
                                        @php
                                            $hoje = \Carbon\Carbon::parse(now())->format('Y-m-d');
                                        @endphp
                                        <strong>{{ $item->lead->name }}</strong><br />
                                        @if ($hoje > $item->term)
                                            <span>Prazo: {{ \Carbon\Carbon::parse($item->term)->format('d/m/Y') }}</span>
                                            <small class="badge badge-danger">Prazo vencido</small><br />
                                        @else
                                            <span>Prazo:
                                                {{ \Carbon\Carbon::parse($item->term)->format('d/m/Y') }}</span><br />
                                        @endif
                                        <small>{{ $item->comments }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">
                                        <span>Nenhum prazo a cumprir</span>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Andamento em Ordem</span>
                            <span class="info-box-number">{{ $progress }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-clock"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Aguardando Cumprimento</span>
                            <span class="info-box-number">{{ $awaiting_fulfillment }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Finalizado Procedente</span>
                            <span class="info-box-number">{{ $procedente }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Finalizado Improcedente</span>
                            <span class="info-box-number">{{ $improcedente }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Recursos</span>
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
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>

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
