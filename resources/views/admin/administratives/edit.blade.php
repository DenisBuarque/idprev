@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Administrativo</h4>
        <a href="{{ route('admin.administratives.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 900px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 900px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.administratives.update',['id' => $administrative->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 900px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário editar administrativo:</h3>
            </div>
            <div class="card-body">
                <small>Os campos com * são de preenchimento obrigatório:</small>
                <div class="row">

                    @can('list-user')
                        <div class="col-sm-12">
                            <div class="form-group m-0">
                                <small>Franqueado: *</small>
                                <select name="user_id" class="form-control @error('phone') is-invalid @enderror">
                                    <option value="">Selecione um franqueado</option>
                                    @foreach ($users as $user)
                                        @if ($user->id == $administrative->user_id)
                                            <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                        @else
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endcan

                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Nome: *</small>
                            <input type="text" name="name" value="{{ $administrative->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>RG: *</small>
                            <input type="text" name="rg" value="{{ $administrative->rg ?? old('rg') }}"
                                class="form-control @error('rg') is-invalid @enderror" maxlength="30" />
                            @error('rg')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>CPF: *</small>
                            <input type="text" name="cpf" value="{{ $administrative->cpf ?? old('cpf') }}" onkeypress="mascara(this, '###.###.###-##')"
                                class="form-control @error('cpf') is-invalid @enderror" maxlength="14" />
                            @error('cpf')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Telefones: *</small>
                            <input type="text" name="phone" value="{{ $administrative->phone ?? old('phone') }}" onkeypress="mascara(this, '## #####-####')"
                                class="form-control @error('phone') is-invalid @enderror" maxlength="14"
                                placeholder="Ex: 82 9XXXX-XXXX" />
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $administrative->email ?? old('email') }}" class="form-control"
                                maxlength="100" />
                        </div>
                    </div>
                    
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Cep:</small>
                            <input type="text" name="cep" id="cep" value="{{ $administrative->cep ?? old('cep') }}" class="form-control @error('cep') is-invalid @enderror"
                                maxlength="9" onkeypress="mascara(this, '#####-###')" onblur="pesquisacep(this.value);" />
                                @error('cep')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Endereço: *</small>
                            <input type="text" name="address" id="address" value="{{ $administrative->address ?? old('address') }}"
                                class="form-control @error('address') is-invalid @enderror" maxlength="250" />
                                @error('address')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Número: *</small>
                            <input type="text" name="number" value="{{ $administrative->number ?? old('number') }}" class="form-control @error('number') is-invalid @enderror"
                                placeholder="nº" maxlength="5" />
                                @error('number')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Bairro: *</small>
                            <input type="text" name="district" id="district" value="{{ $administrative->district ?? old('district') }}"
                                class="form-control @error('district') is-invalid @enderror" maxlength="50" />
                                @error('district')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Cidade: *</small>
                            <input type="text" name="city" id="city" value="{{ $administrative->city ?? old('city') }}" class="form-control @error('city') is-invalid @enderror"
                                maxlength="50" />
                                @error('city')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Estado: *</small>
                            <input type="text" name="state" id="state" value="{{ $administrative->state ?? old('state') }}" class="form-control @error('state') is-invalid @enderror"
                                maxlength="2" />
                                @error('state')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Senha meu INSS: *</small>
                            <input type="text" name="inss" id="inss" value="{{ $administrative->inss ?? old('inss') }}" class="form-control @error('inss') is-invalid @enderror"
                                maxlength="30" />
                                @error('inss')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Situação: *</small>
                            <input type="text" name="situation" id="situation" value="{{ $administrative->situation ?? old('situation') }}" class="form-control @error('situation') is-invalid @enderror"/>
                                @error('situation')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Exigencias:</small>
                            <textarea name="requirements" class="form-control" placeholder="Digite aqui as exigencias.">{{ $administrative->requirements ?? old('requirements') }}</textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Beneficios: *</small>
                            <textarea name="benefits" class="form-control" placeholder="Digite aqui os beneficios.">{{ $administrative->benefits ?? old('benefits') }}</textarea>
                            @error('benefits')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Resultado:</small>
                            <select name="results" class="form-control">
                                <option value="1">Deferido</option>
                                <option value="2">Indeferido</option>
                            </select>
                            @error('results')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Inicio do beneficio: *</small>
                            <input type="date" name="initial_date" id="initial_date" value="{{ $administrative->initial_date->format("Y-m-d") ?? old('initial_date') }}" class="form-control @error('initial_date') is-invalid @enderror"
                                maxlength="2" />
                                @error('initial_date')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Sessação do beneficio: *</small>
                            <input type="date" name="concessao_date" id="concessao_date" value="{{ $administrative->concessao_date->format("Y-m-d") ?? old('concessao_date') }}" class="form-control @error('concessao_date') is-invalid @enderror"
                                maxlength="2" />
                                @error('concessao_date')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Honorários: *</small>
                            <input type="text" name="fees" id="fees" value="{{ number_format($administrative->fees,2,',','.') ?? old('fees') }}" onkeyup="moeda(this);" class="form-control @error('fees') is-invalid @enderror"
                                maxlength="15" placeholder="0,00" />
                                @error('fees')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Forma de pagamento: *</small>
                            <textarea name="payment" class="form-control" placeholder="Descreva a forma de pagamento">{{ $administrative->payment ?? old('payment') }}</textarea>
                            @error('payment')
                            <div class="text-red">{{ $message }}</div>
                        @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.administratives.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button id="button" onClick="ocultarExibir()" type="submit" class="btn btn-md btn-info float-right">
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

        function moeda(i) {
            var v = i.value.replace(/\D/g, '');
            v = (v / 100).toFixed(2) + '';
            v = v.replace(".", ",");
            v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
            i.value = v;
        }

        //criação de mascara
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
