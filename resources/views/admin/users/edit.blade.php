@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Usuário do Sistema</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{route('admin.users.update',['id' => $user->id])}}">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de usuário:</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Nome do usuário:</small>
                            <input type="text" name="name" value="{{ $user->name ?? old('name')}}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="Nome do usuário" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $user->email ?? old('email')}}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="E-mail: atendimento@idprev.com.br" />
                            @error('email')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Senha:</small>
                            <input type="password" name="password"
                                class="form-control" placeholder="Senha" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <small>Confirme sua senha:</small>
                        <div class="form-group m-0">
                            <input type="password" name="password_confirmation"
                                class="form-control"
                                placeholder="Confirmar senha" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{route('admin.users.index')}}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar dados
                </button>
            </div>
        </div>

    </form>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop
