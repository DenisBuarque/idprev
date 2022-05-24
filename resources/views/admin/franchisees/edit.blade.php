@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Franqueado Conveniado</h4>
        <a href="{{ route('admin.franchisees.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.franchisees.update', ['id' => $user->id]) }}">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de franqueado conveniado:</h3>
            </div>
            <div class="card-body">
                <small>Os compos com * são de preenchimento obrigatório:</small>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Nome: *</small>
                            <input type="text" name="name" value="{{ $user->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Telefone: *</small>
                            <input type="text" name="phone" value="{{ $user->phone ?? old('phone') }}"
                                onkeypress="mascara(this, '## #####-####')"
                                class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: 82 90000-0000"
                                maxlength="13" />
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Cep:</small>
                            <input type="text" name="zip_code" id="zip_code" value="{{ $user->zip_code ?? old('zip_code') }}"
                                onkeypress="mascara(this, '#####-###')" class="form-control" maxlength="9" />
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Endereço: *</small>
                            <input type="text" name="address" id="address" value="{{ $user->address ?? old('address') }}"
                                onblur="pesquisacep(this.value);"
                                class="form-control @error('address') is-invalid @enderror" maxlength="250" />
                            @error('address')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Número: *</small>
                            <input type="text" name="number" value="{{ $user->number ?? old('number') }}"
                                class="form-control @error('number') is-invalid @enderror" placeholder="nº" maxlength="5" />
                            @error('number')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Bairro: *</small>
                            <input type="text" name="district" id="district"
                                value="{{ $user->district ?? old('district') }}"
                                class="form-control @error('district') is-invalid @enderror" maxlength="50" />
                            @error('district')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Cidade: *</small>
                            <input type="text" name="city" id="city" value="{{ $user->city ?? old('city') }}"
                                class="form-control @error('city') is-invalid @enderror" maxlength="50" />
                            @error('city')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Estado: *</small>
                            <input type="text" name="state" id="state" value="{{ $user->state ?? old('state') }}"
                                class="form-control @error('state') is-invalid @enderror" maxlength="2" />
                            @error('state')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Complemento do endereço:</small>
                            <input type="text" name="complement" value="{{ $user->complement ?? old('complement') }}"
                                class="form-control" placeholder="(opcional)" maxlength="200" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>E-mail: *</small>
                            <input type="email" name="email" value="{{ $user->email ?? old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" maxlength="100" />
                            @error('email')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Senha:</small>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" placeholder="******" />
                            @error('password')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Confirme a senha:</small>
                            <input type="password" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                placeholder="******" />
                            @error('password_confirmation')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12 my-3">
                        <div class="form-group m-0">

                            @php
                            $arr = [
                                6 => 'Lista leads',
                                7 => 'Adicionar lead',
                                8 => 'Alterar lead',
                                9 => 'Excluir lead',
                                10 => 'Pesquisar lead',
                                11 => 'Comentários lead',
                                12 => 'Listar clientes',
                                13 => 'Adicionar cliente',
                                14 => 'Alterar cliente',
                                15 => 'Excluir cliente',
                                16 => 'Pesquisar cliente',
                                17 => 'Comentários cliente',
                                28 => 'Listar arquivo treinamento',
                                29 => 'Adicionar arquivo treinamento',
                                30 => 'Alterar arquivo treinamento',
                                31 => 'Excluir arquivo treinamento',
                                32 => 'Pesquisar arquivo treinamento',
                                33 => 'Listar eventos',
                                34 => 'Adicionar evento',
                                35 => 'Alterar evento',
                                36 => 'Excluir evento',
                                37 => 'Pesquisar evento',
                                53 => 'Listar ticket de atendimento',
                                54 => 'Abrir ticket de atendimento',
                                55 => 'Alterar ticket de atendimento',
                                56 => 'Excluir ticket de atendimento',
                                57 => 'Pesquisar ticket de atendimento',
                                58 => 'Listar prazo cliente',
                                59 => 'Alterar prazo cliente',
                                60 => 'Excluir prazo cliente',
                                61 => 'Pesquisar prazo cliente',
                            ];
                        @endphp

                            <small>Permissões de acesso ao sistema:</small>
                            <select name="permission[]" class="form-control" multiple style="height: 300px;">
                                @foreach($arr as $key1 => $value)
                                    @php
                                        $selected = '';
                                        if(old('permission')):
                                            foreach (old('permission') as $key2 => $value2):
                                                if($key1 == $key2 ):
                                                    $selected = 'selected';
                                                endif;
                                            endforeach;
                                        else:
                                            if($user){
                                                foreach( $user->permissions as $key => $permission):
                                                    if($permission->id == $key1):
                                                        $selected = "selected";
                                                    endif;
                                                endforeach;
                                            }
                                        endif;
                                    @endphp
                                    <option {{ $selected }} value="{{ $key1 }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.franchisees.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    <br />

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

        // cria mascara
        function mascara(t, mask) {
            var i = t.value.length;
            var saida = mask.substring(1, 0);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                t.value += texto.substring(0, 1);
            }
        }

        // Busca pelo cep
        function limpa_formulario_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('address').value = ("");
            document.getElementById('district').value = ("");
            document.getElementById('city').value = ("");
            document.getElementById('state').value = ("");
        }

        function meu_callback(conteudo) {
            if (!("erro" in conteudo)) {
                //Atualiza os campos com os valores.
                document.getElementById('address').value = (conteudo.logradouro);
                document.getElementById('district').value = (conteudo.bairro);
                document.getElementById('city').value = (conteudo.localidade);
                document.getElementById('state').value = (conteudo.uf);
            } else {
                //CEP não Encontrado.
                limpa_formulário_cep();
                alert("CEP não encontrado.");
            }
        }

        function pesquisacep(valor) {
            //Nova variável "cep" somente com dígitos.
            var cep = valor.replace(/\D/g, '');
            //Verifica se campo cep possui valor informado.
            if (cep != "") {
                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;
                //Valida o formato do CEP.
                if (validacep.test(cep)) {
                    //Preenche os campos com "..." enquanto consulta webservice.
                    document.getElementById('address').value = "...";
                    document.getElementById('district').value = "...";
                    document.getElementById('city').value = "...";
                    document.getElementById('state').value = "...";
                    //Cria um elemento javascript.
                    var script = document.createElement('script');
                    //Sincroniza com o callback.
                    script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';
                    //Insere script no documento e carrega o conteúdo.
                    document.body.appendChild(script);

                } else {
                    //cep é inválido.
                    limpa_formulário_cep();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep();
            }
        };
    </script>
@stop
