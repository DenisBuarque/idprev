@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="" class="form-control" placeholder="Nome do contrato" required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Buscar</button>
                </span>
            </div>
            <a href="{{ route('admin.contracts.create') }}" class="btn bg-info">Adicionar Registro</a>
        </div>
    </form>
@stop

@section('content')

    @if (session('alert'))
        <div class="alert alert-success mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Contratos</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título do processo</th>
                        <th>Assessor(es)</th>
                        <th>Observação</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contracts as $contract)
                        <tr>
                            <td>{{ $contract->process->title }}</td>
                            <td>
                                <a href="" class="btn btn-sm" data-toggle="modal" data-target="#modal-default{{$contract->id}}">
                                    <i class="fas fa-user"></i> {{count($contract->advisors)}}
                                </a>
                            </td>
                            <td style="max-width: 600px;">{{ $contract->description }}</td>
                            <td class='text-cente'>
                                <div class="d-flex flex-row">
                                    <a href="{{ route('admin.contracts.pdf', ['id' => $contract->id]) }}" target="_blanck"
                                        class="btn btn-success btn-sm mr-1">
                                        <i class="fas fa-file"></i>
                                    </a>
                                    <a href="{{ route('admin.contracts.edit', ['id' => $contract->id]) }}"
                                        class="btn btn-info btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST"
                                        action="{{ route('admin.contracts.destroy', ['id' => $contract->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            @foreach ($contracts as $contract)
                <div class="modal fade" id="modal-default{{$contract->id}}" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $contract->process->title }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Assessor(es) envolvido(s) no processo de contrato:</p>
                                @foreach ($contract->advisors as $adv)
                                    <span>{{ $adv->name }}</span><br/>
                                @endforeach
                                <br/>
                                <p>Observação: {{$contract->description}}</p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Ok obigado!</button>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach

            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $contracts)
                    {{ $contracts->links() }}
                @endif
            </div>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop
