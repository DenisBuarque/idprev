@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Cliente (Lead)</h4>
        <a href="{{ route('admin.leads.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <form method="POST" action="{{ route('admin.leads.update', ['id' => $lead->id]) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Formulário cadastro de cliente:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Nome completo: *</small>
                            <input type="text" name="name" value="{{ $lead->name ?? old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Telefones: *</small>
                            <input type="text" name="phone" value="{{ $lead->phone ?? old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" maxlength="50" placeholder="Ex: 82 99925-8977, 98854-7889 ..."/>
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ $lead->email ?? old('email') }}"
                                class="form-control" maxlength="100" />
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
                            <input type="text" 
                                   name="cep" 
                                   id="cep" 
                                   value="{{ $lead->cep ?? old('cep') }}" 
                                   class="form-control" 
                                   maxlength="9" 
                                   onkeypress="mascara(this, '#####-###')" 
                                   onblur="pesquisacep(this.value);"  />
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
                            <input type="text" name="district" id="district" value="{{ $lead->district ?? old('district') }}"
                                class="form-control" maxlength="50" />
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
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Franqueado:</small>
                            <select name="advisor_id" class="form-control">
                                <option value="">Selecione um franqueado</option>
                                @foreach ($advisors as $advisor)
                                    @if ($advisor->id == $lead->advisor_id)
                                        <option value="{{$advisor->id}}" selected>{{$advisor->name}}</option>
                                    @else
                                        <option value="{{$advisor->id}}">{{$advisor->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Etiqueta:</small>
                            <select name="tag" class="form-control">
                                <option value="1" @if($lead->tag == 1) selected @endif>Novo</option>
                                <option value="2" @if($lead->tag == 2) selected @endif>Aguardando</option>
                                <option value="3" @if($lead->tag == 3) selected @endif>Convertido</option>
                                <option value="4" @if($lead->tag == 4) selected @endif>Não Convertido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Situação:</small>
                            <select name="situation" class="form-control">
                                <option value="1">Andamento em ordem</option>
                                <option value="2">Aguardando</option>
                                <option value="3">Finalizado Procedente</option>
                                <option value="4">Finalizado Improcedente</option>
                                <option value="5">Recursos</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Processo Nº:</small>
                            <input type="text" name="process" id="process" value="{{ $lead->process ?? old('process') }}" class="form-control" maxlength="30" />
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Financeiro:</small>
                            <input type="text" name="financial" id="financial" onkeyup="moeda(this);" value="{{ $lead->finencial ?? old('financial') }}" class="form-control" maxlength="13" placeholder="0,00" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Tipo de Ação:</small>
                            <select name="action" class="form-control">
                                 @foreach ($actions as $action)
                                 @if ($action->id == $lead->action) 
                                    <option value="{{$action->id}}" selected>{{$action->name}}</option>
                                 @else
                                    <option value="{{$action->id}}">{{$action->name}}</option>
                                 @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Tribunal:</small>
                            <input type="text" name="court" id="court" value="{{ $lead->court ?? old('court') }}" class="form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Vara:</small>
                            <input type="text" name="stick" id="stick" value="{{ $lead->stick ?? old('stick') }}" class="form-control" maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Prazo:</small>
                            <input type="date" name="term" id="term" value="{{ $lead->term ?? old('term') }}" class="form-control" />
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
                <a href="{{ route('admin.leads.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar dados
                </button>
            </div>
        </div>
    </form>

    <div class="card" style="margin-top: 10px;">
        <div class="card-header">
            <h3 class="card-title">Imagens de documentos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($lead->photos as $photo)
                    <div class="col-md-3">
                        <img src="{{asset('storage/'.$photo->image)}}" alt="foto" class="img-fluid" />
                        <form action="{{route('admin.lead.document.remove')}}" method="POST">
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
    <br/>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>

        function moeda(i) {
            var v = i.value.replace(/\D/g,'');
            v = (v/100).toFixed(2) + '';
            v = v.replace(".", ",");
            v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
            i.value = v;
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
