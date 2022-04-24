@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.processes.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{$search}}" class="form-control"
                    placeholder="Título do processo" required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Buscar</button>
                </span>
            </div>
            <a href="{{ route('admin.processes.create') }}" class="btn bg-info">Adicionar Registro</a>
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
            <h3 class="card-title">Lista de Processos</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Cliente</th>
                        <th>Pasta</th>
                        <th>Nº Processo</th>
                        <th>Etiqueta</th>
                        <th>Dia(s)</th>
                        <th>Valor causa</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($processes as $process)
                        <tr>
                            <td>{{ $process->title }}</td>
                            <td>{{ $process->client->name }}</td>
                            <td>{{ $process->folder }}</td>
                            <td>{{ $process->number_process }}</td>
                            <td>
                                @php
                                    $array = array(1 => 'Fase inicial', 2 => 'Consumidor', 3 => 'Criminal', 4 => 'Cível', 5 => 'Audiência', 6 => 'Citação', 7 => 'Conciliação', 8 => 'Contestação', 9 => 'Sentença', 10 => 'Trabalhista', 11 => 'Tributário');
                                    foreach ($array as $key => $value) {
                                        if($process->tag == $key){
                                            echo $value;
                                        }
                                    }
                                @endphp
                            </td>
                            <td>{{ $process->days }} dia(s)</td>
                            <td>{{ number_format($process->valor_causa,2,',','.') }}</td>
                            <td class='text-cente'>
                                <div class="d-flex flex-row">
                                    <a href="{{route('admin.processes.pdf',['id' => $process->id])}}" target="_blank"
                                        class="btn btn-success btn-sm mr-1">
                                        <i class="fas fa-file"></i>
                                    </a>
                                    <a href="{{route('admin.processes.edit',['id' => $process->id])}}"
                                        class="btn btn-info btn-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{route('admin.processes.destroy',['id' => $process->id])}}">
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

            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $processes)
                    {{ $processes->links() }}
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
