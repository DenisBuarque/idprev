@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Contrato</h4>
        <a href="{{ route('admin.contracts.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.contracts.store') }}">
        @csrf
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário cadastro de contrado</h3>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Processo:</small>
                            <select name="process_id" class="form-control">
                                <option value="">Selecione um processo</option>
                                @foreach ($processes as $process)
                                    <option value="{{$process->id}}">{{$process->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Deixe sua observação: (opcional)</small>
                            <textarea name="description" id="" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <span class="mb-3 d-block">Selecione um ou mais assessor(es) para receber a proposta de contrato em sua área privada:</span>
                            @foreach ($advisors as $advisor)
                                <input type="checkbox" name="advisor_id[]" value="{{$advisor->id}}" /> {{$advisor->name}}<br/> 
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>
            <div class="card-footer">
                <a href="{{ route('admin.contracts.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar
                </button>
            </div>
        </div>

    </form>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script></script>
@stop
