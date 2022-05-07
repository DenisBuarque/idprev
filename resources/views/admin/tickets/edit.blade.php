@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Ticket</h4>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-md bg-info">Listar Tickets</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tickets.update',['id' => $ticket->id]) }}">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário editar ticket de atendimento:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group m-0">
                            @php
                                $array = array(1 => 'Aberto', 2 => 'Resolvido', 3 => 'Pendente')
                            @endphp
                            <small>Informe a situação do seu ticket de atendimento:</small>
                            <select name="status" class="form-control">
                                @foreach ($array as $key => $item)
                                    @if ($ticket->status == $key)
                                        <option value="{{ $key }}" selected>{{ $item }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('active')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.tickets.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button id="button" onClick="ocultarExibir()" type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save mr-2"></i>
                   Salvar registro
                </button>
                <a id="spinner" class="btn btn-md btn-info float-right text-center">
                    <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </a>
            </div>
        </div>

    </form>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>
    document.getElementById("button").style.display = "block"; 
    document.getElementById("spinner").style.display = "none"; 

    function ocultarExibir() {
        document.getElementById("button").style.display = "none"; 
        document.getElementById("spinner").style.display = "block"; 
    }
</script>
@stop
