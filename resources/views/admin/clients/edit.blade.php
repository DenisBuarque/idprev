@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Cliente</h4>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.clients.update', ['id' => $client->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário edição de cliente:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group m-0">
                            <small>Nome completo:</small>
                            <input type="text" name="name" value="{{ $client->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>CPF:</small>
                            <input type="text" name="cpf" value="{{ $client->cpf ?? old('cpf') }}"
                                onkeypress="mascara(this, '###.###.###-##')"
                                class="form-control @error('cpf') is-invalid @enderror" placeholder="nº"
                                maxlength="14" />
                            @error('cpf')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Telefone:</small>
                            <input type="text" name="phone" value="{{ $client->phone ?? old('phone') }}"
                                onkeypress="mascara(this, '## #####-####')"
                                class="form-control @error('phone') is-invalid @enderror" placeholder="Ex: 82 90000-0000"
                                maxlength="13" />
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $client->email ?? old('email') }}"
                                class="form-control @error('email') is-invalid @enderror" maxlength="100" />
                            @error('email')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Cep:</small>
                            <input type="text" name="zip_code" id="cep"
                                value="{{ $client->zip_code ?? old('zip_code') }}"
                                onkeypress="mascara(this, '#####-###')" class="form-control" maxlength="9" />
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Endereço:</small>
                            <input type="text" name="address" id="address"
                                value="{{ $client->address ?? old('address') }}" onblur="pesquisacep(this.value);"
                                class="form-control @error('address') is-invalid @enderror" maxlength="250" />
                            @error('address')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <small>Número:</small>
                        <div class="form-group m-0">
                            <input type="text" name="number" value="{{ $client->number ?? old('number') }}"
                                class="form-control @error('number') is-invalid @enderror" placeholder="nº" maxlength="5" />
                            @error('number')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Bairro:</small>
                            <input type="text" name="district" id="district"
                                value="{{ $client->district ?? old('district') }}"
                                class="form-control @error('district') is-invalid @enderror" maxlength="50" />
                            @error('district')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Cidade:</small>
                            <input type="text" name="city" id="city" value="{{ $client->city ?? old('city') }}"
                                class="form-control @error('city') is-invalid @enderror" maxlength="50" />
                            @error('city')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Estado:</small>
                            <input type="text" name="state" id="state" value="{{ $client->state ?? old('state') }}"
                                class="form-control @error('state') is-invalid @enderror" maxlength="2" />
                            @error('state')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Complemento do endereço:</small>
                            <input type="text" name="complement" value="{{ $client->complement ?? old('complement') }}"
                                class="form-control" placeholder="(opcional)" maxlength="200" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <small>Anexo de documentos do cliente:</small>
                            <br/>
                            <input type="file" name="photos[]" multiple/>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.clients.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar dados
                </button>
            </div>
        </div>
    </form>

    <div class="card" style="max-width: 700px; margin: auto; margin-top: 10px;">
        <div class="card-header">
            <h3 class="card-title">Imagens de documentos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($client->photos as $photo)
                    <div class="col-md-3">
                        <img src="{{asset('storage/'.$photo->image)}}" alt="foto" class="img-fluid" />
                        <form action="{{route('admin.client.document.remove')}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="photo" value="{{$photo->image}}" />
                            <button type="submit" class="btn btn-sm btn-default mt-1 mb-2">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
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
