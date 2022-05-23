@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.tickets.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-ticket')
                <div class="input-group" style="width: 30%">
                    <select name="search" class="form-control" required>
                        <option value="">Selecione um status</option>
                        <option value="1" @if ($search == 1) selected @endif>Aberto</option>
                        <option value="2" @if ($search == 2) selected @endif>Resolvido</option>
                        <option value="3" @if ($search == 3) selected @endif>Pendente</option>
                    </select>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search mr-2"></i> Buscar</button>
                    </span>
                </div>
            @endcan
            @can('create-ticket')
                <a href="{{ route('admin.tickets.create') }}" class="btn bg-info"><i class="fa fa-tag"></i> Abrir Ticket
                    de Atendimento</a>
            @endcan
        </div>
    </form>
@stop

@section('content')

    <div class="card mb-3">
        <div class="row">
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 100%</span>
                    <h5 class="description-header">{{ $ticket_total }}</h5>
                    <span class="description-text">TICKETS</span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i>
                        {{ $open != 0 ?? '' }}0%</span>
                    <h5 class="description-header">{{ $open }}</h5>
                    <span class="description-text">TICKETS ABERTOS</span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i>
                        {{ $resolved != 0 ?? '' }}0%</span>
                    <h5 class="description-header">{{ $resolved }}</h5>
                    <span class="description-text">TICKETS RESOLVIDOS</span>
                </div>
            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i>
                        {{ $pending != 0 ?? '' }}0%</span>
                    <h5 class="description-header">{{ $pending }}</h5>
                    <span class="description-text">TICKETS PENDETES</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-12">

            @if (session('success'))
                <div id="message" class="alert alert-success mb-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Tickets de Atendimento</h3>
                            <div class="card-tools">

                            </div>
                        </div>

                        <div class="card-body table-responsive p-0">
                            @php
                                $array = [1 => 'Aberto', 2 => 'Resolvido', 3 => 'pendente'];
                            @endphp
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Aberto</th>
                                        <th>Código</th>
                                        <th>Franqueado</th>
                                        <th>Status</th>
                                        <th style="width: 150px;" class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->created_at->format('d/m/Y H:m:s') }}</td>
                                            <td>{{ $ticket->code }}</td>
                                            <td>{{ $ticket->user->name }}</td>
                                            <td>
                                                @foreach ($array as $key => $value)
                                                    @if ($key == $ticket->status)
                                                        {{ $value }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                                @can('comments-ticket')
                                                    <a href="{{ route('admin.tickets.response', ['id' => $ticket->id]) }}"
                                                        class="btn btn-xs px-2 border mr-1">
                                                        <i class="fa fa-comments"></i> {{ count($ticket->feedbackTickets) }}
                                                    </a>
                                                @endcan
                                                @if ($ticket->status != 2)
                                                    @can('edit-ticket')
                                                        <a href="{{ route('admin.tickets.edit', ['id' => $ticket->id]) }}"
                                                            class="btn btn-info btn-xs px-2 mr-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete-ticket')
                                                        <form method="POST" onsubmit="return(confirmaExcluir())"
                                                            action="{{ route('admin.tickets.destroy', ['id' => $ticket->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-xs px-2">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    @can('editi-ticket')
                                                        <button type="button" class="btn btn-lingt btn-xs px-2 mr-1">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete-ticket')
                                                        <button type="button" class="btn btn-lingt btn-xs px-2 mr-1">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endcan
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            <div class="mt-3 mr-3 ml-3">
                                @if (!$search && $tickets)
                                    {{ $tickets->links() }}
                                @endif
                            </div>

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
            var conf = confirm("Deseja mesmo excluir? Os dados seram pedidos e não poderam ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 10000);
    </script>
@stop
