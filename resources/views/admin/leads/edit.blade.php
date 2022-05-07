@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Lead</h4>
        <a href="{{ route('admin.leads.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.leads.update', ['id' => $lead->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de lead:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group m-0">
                            <small>Nome completo: *</small>
                            <input type="text" name="name" value="{{ $lead->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Telefones: *</small>
                            <input type="text" name="phone" value="{{ $lead->phone ?? old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" maxlength="50"
                                placeholder="Ex: 82 99925-8977, 98854-7889 ..." />
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $lead->email ?? old('email') }}"
                                class="form-control" maxlength="100" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Etiqueta:</small>
                            <select name="tag" class="form-control">
                                <option value="1" @if ($lead->tag == 1) selected @endif>Novo</option>
                                <option value="2" @if ($lead->tag == 2) selected @endif>Aguardando</option>
                                <option value="3" @if ($lead->tag == 3) selected @endif>Convertido</option>
                                <option value="4" @if ($lead->tag == 4) selected @endif>Não Convertido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Franqueado:</small>
                            <select name="advisor_id" class="form-control">
                                <option value="">Selecione um franqueado</option>
                                @foreach ($users as $user)
                                    @if ($user->id == $lead->user_id)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">

                        <div class="form-group m-0">
                            <small>Comentários:</small>
                            <textarea name="comments" class="form-control">{{ $lead->comments ?? old('comments') }}</textarea>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach ($lead->feedbackLeads as $comment)
                                <li class="list-group-item">{{ $comment->comments }}</li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Cep:</small>
                            <input type="text" name="zip_code" id="zip_code" value="{{ $lead->zip_code ?? old('zip_code') }}"
                                class="form-control" maxlength="9" onkeypress="mascara(this, '#####-###')"
                                onblur="pesquisacep(this.value);" />
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Endreço:</small>
                            <input type="text" name="address" id="address" value="{{ $lead->address ?? old('address') }}"
                                class="form-control" maxlength="250" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <small>Número:</small>
                            <input type="text" name="number" value="{{ $lead->number ?? old('number') }}"
                                class="form-control" placeholder="nº" maxlength="5" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <small>Bairro:</small>
                            <input type="text" name="district" id="district"
                                value="{{ $lead->district ?? old('district') }}" class="form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <small>Cidade:</small>
                            <input type="text" name="city" id="city" value="{{ $lead->city ?? old('city') }}"
                                class="form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <small>Estado:</small>
                            <input type="text" name="state" id="state" value="{{ $lead->state ?? old('state') }}"
                                class="form-control" maxlength="2" />
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.leads.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
