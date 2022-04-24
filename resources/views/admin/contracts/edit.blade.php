@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Cliente</h4>
        <a href="{{ route('admin.contracts.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.contracts.update', ['id' => $contract->id]) }}">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de contrato</h3>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Processo:</small>
                            <select name="process_id" class="form-control">
                                <option value="">Selecione um processo</option>
                                @foreach ($processes as $process)
                                    @if ($contract->process_id == $process->id)
                                        <option value="{{$process->id}}" selected>{{$process->title}}</option> 
                                    @else
                                        <option value="{{$process->id}}">{{$process->title}}</option> 
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Deixe sua observação: (opcional)</small>
                            <textarea name="description" id="" class="form-control">{{$contract->description ?? old('description')}}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <span class="mb-3 d-block">Selecione um ou mais assessor(es) para receber a proposta de contrato em sua área privada:</span>
                            @foreach ($advisors as $advisor)
                                @if($contract->advisors->contains($advisor))
                                    <input type="checkbox" name="advisor_id[]" value="{{$advisor->id}}" checked /> {{$advisor->name}}<br/> 
                                @else
                                    <input type="checkbox" name="advisor_id[]" value="{{$advisor->id}}" /> {{$advisor->name}}<br/> 
                                @endif
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
    <script>
        
    </script>
@stop
