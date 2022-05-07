@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.tickets.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <select name="search" class="form-control" required>
                    <option value="">Selecione um status</option>
                    <option value="1" @if ($search == 1) selected @endif>Aberto</option>
                    <option value="2" @if ($search == 2) selected @endif>Resolvido</option>
                    <option value="3" @if ($search == 3) selected @endif>Pendente</option>
                </select>
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Buscar</button>
                </span>
            </div>
            <a href="{{ route('admin.tickets.create') }}" class="btn bg-info">Criar Ticket de Atendimento</a>
        </div>
    </form>
@stop

@section('content')

    <div class="card mb-3">
        <div class="row">
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 100%</span>
                    <h5 class="description-header">{{ count($ticket_total) }}</h5>
                    <span class="description-text">TICKETS</span>
                </div>

            </div>

            <div class="col-sm-3 col-6">

                <div class="description-block border-right">
                    <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> {{ count($open) }}0%</span>
                    <h5 class="description-header">{{ count($open) }}</h5>
                    <span class="description-text">TICKETS ABERTOS</span>
                </div>

            </div>

            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                    <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> {{ count($resolved) }}0%</span>
                    <h5 class="description-header">{{ count($resolved) }}</h5>
                    <span class="description-text">TICKETS RESOLVIDOS</span>
                </div>

            </div>
            <div class="col-sm-3 col-6">
                <div class="description-block">
                    <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> {{ count($pending) }}0%</span>
                    <h5 class="description-header">{{ count($pending) }}</h5>
                    <span class="description-text">TICKETS PENDETES</span>
                </div>

            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-12">

            @if (session('success'))
                <div class="alert alert-success mb-2" role="alert">
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
                                        <th>Data</th>
                                        <th>Código</th>
                                        <th>Franqueado</th>
                                        <th>Status</th>
                                        <th class="text-center">Comentários</th>
                                        <th style="width: 100px;" class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>{{ $ticket->created_at->format('d/m/Y H:m:s') }}</td>
                                            <td>{{ $ticket->code }}</td>
                                            <td>{{ $ticket->advisor->name }}</td>
                                            <td>
                                                @foreach ($array as $key => $value)
                                                    @if ($key == $ticket->status)
                                                        {{ $value }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.tickets.response', ['id' => $ticket->id]) }}"
                                                    class="btn btn-sm border">
                                                    <i class="fa fa-comments"></i> {{ count($ticket->feedbackTickets) }}
                                                </a>
                                            </td>
                                            <td class='d-flex flex-row align-content-center justify-content-center'>

                                                @if ($ticket->status != 2)
                                                    <a href="{{ route('admin.tickets.edit', ['id' => $ticket->id]) }}"
                                                        class="btn btn-info btn-sm mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                                        action="{{ route('admin.tickets.destroy', ['id' => $ticket->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-lingt btn-sm mr-1">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-lingt btn-sm mr-1">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
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
    </script>
@stop
