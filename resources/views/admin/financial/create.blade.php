@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Financeiro</h4>
        <a href="{{ route('admin.financial.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.financial.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card card-info" style="max-width: 800px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário financeiro:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Nome completo: *</small>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                            @error('name')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Telefones: *</small>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control @error('phone') is-invalid @enderror" maxlength="50"
                                placeholder="Ex: 82 99925-8977, 98854-7889 ..." />
                            @error('phone')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>E-mail:</small>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                maxlength="100" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Cep: *</small>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" class="form-control @error('zip_code') is-invalid @enderror"
                                maxlength="9" onkeypress="mascara(this, '#####-###')" onblur="pesquisacep(this.value);" />
                                @error('zip_code')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                            </div>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-group m-0">
                            <small>Endereço: *</small>
                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                class="form-control @error('address') is-invalid @enderror" maxlength="250" />
                                @error('address')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <small>Número: *</small>
                            <input type="text" name="number" value="{{ old('number') }}" class="form-control @error('number') is-invalid @enderror"
                                placeholder="nº" maxlength="5" />
                                @error('number')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <small>Bairro: *</small>
                            <input type="text" name="district" id="district" value="{{ old('district') }}"
                                class="form-control @error('district') is-invalid @enderror" maxlength="50" />
                                @error('district')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <small>Cidade: *</small>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" class="form-control @error('city') is-invalid @enderror"
                                maxlength="50" />
                                @error('city')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <small>Estado: *</small>
                            <input type="text" name="state" id="state" value="{{ old('state') }}" class="form-control @error('state') is-invalid @enderror"
                                maxlength="2" />
                                @error('state')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Franqueado: *</small>
                            <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                <option value="">Selecione um franqueado</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Etiqueta: *</small>
                            <select name="tag" class="form-control @error('tag') is-invalid @enderror">
                                <option value="2" @if (old('tag') == 2) selected @endif>Aguardando</option>
                                <option value="3" @if (old('tag') == 3) selected @endif>Convertido</option>
                                <option value="4" @if (old('tag') == 4) selected @endif>Não Convertido</option>
                            </select>
                            @error('tag')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Situação: *</small>
                            <select name="situation" class="form-control @error('situation') is-invalid @enderror">
                                <option value="1" @if (old('situation') == 1) selected @endif>Andamento em ordem</option>
                                <option value="2" @if (old('situation') == 2) selected @endif>Aguardando cumprimento</option>
                                <option value="3" @if (old('situation') == 3) selected @endif>Finalizado Procedente</option>
                                <option value="4" @if (old('situation') == 4) selected @endif>Finalizado Improcedente</option>
                                <option value="5" @if (old('situation') == 5) selected @endif>Recursos</option>
                            </select>
                            @error('situation')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Processo Nº:</small>
                            <input type="text" name="process" id="process" value="{{ old('process') }}"
                                class="form-control" maxlength="30" />
                        </div>
                    </div>

                    <div class="col-sm-2">
                        <div class="form-group m-0">
                            <small>Financeiro:</small>
                            <input type="text" name="financial" id="financial" onkeyup="moeda(this);"
                                value="{{ old('financial') }}" class="form-control" maxlength="13"
                                placeholder="0,00" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Tipo de Ação:</small>
                            <select name="action" class="form-control" onchange="showDocuments(this.value)">
                                <option value="">Selecione um tipo</option>
                                @foreach ($actions as $action)
                                    <option value="{{ $action->id }}" @if (old('action') == $action->is) selected @endif>{{ $action->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group m-0">
                            <small>Tribunal:</small>
                            <input type="text" name="court" id="court" value="{{ old('court') }}" class="form-control"
                                maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Vara:</small>
                            <input type="text" name="stick" id="stick" value="{{ old('stick') }}" class="form-control"
                                maxlength="50" />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <small>Prazo:</small>
                            <input type="date" name="term" id="term" value="{{ old('term') }}" class="form-control @error('phone') is-invalid @enderror" />
                        </div>
                        @error('term')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <small>Comentários:</small>
                            <textarea name="comments" class="form-control">{{ old('comments') }}</textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.clients.index') }}" type="submit" class="btn btn-default">Cancelar</a>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

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

    </script>
@stop
