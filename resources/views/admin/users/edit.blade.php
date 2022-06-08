@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Administrador do Sistema</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{route('admin.users.update',['id' => $user->id])}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de administrador:</h3>
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
                        <div class="form-group">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $user->email ?? old('email')}}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="E-mail: atendimento@idprev.com.br" />
                            @error('email')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <small>Foto do usuário:</small>
                        <div class="form-group">
                            <input type="file" name="image" />
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
                    <div class="col-md-12 my-3">
                        <div class="form-group m-0">
                            <small>Permissões de acesso ao sistema: Pressione a tecla 'Ctrl' e clique sobre a opção para selecionar.</small>
                            <select name="permission[]" class="form-control" multiple style="height: 300px;">
                                @foreach($permissions as $key => $value)
                                    @php
                                        $selected = '';
                                        if(old('permission')):
                                            foreach (old('permission') as $key => $value2):
                                                if($value->id == $value2->id ):
                                                    $selected = 'selected';
                                                endif;
                                            endforeach;
                                        else:
                                            if($user){
                                                foreach( $user->permissions as $key => $permission):
                                                    if($permission->id == $value->id):
                                                        $selected = "selected";
                                                    endif;
                                                endforeach;
                                            }
                                        endif;
                                    @endphp
                                    <option {{ $selected }} value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{route('admin.users.index')}}" type="submit" class="btn btn-default">Cancelar</a>
                <button id="button" type="submit" onClick="ocultarExibir()" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save mr-2"></i>
                    Salvar dados
                </button>
                <a id="spinner" class="btn btn-md btn-info float-right text-center">
                    <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </a>
            </div>
        </div>

    </form>
    <br/>

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
