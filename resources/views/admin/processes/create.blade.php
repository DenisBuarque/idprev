@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Processo</h4>
        <a href="{{ route('admin.processes.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.processes.store') }}">
        @csrf
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário cadastro de processo:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Cliente:</small>
                            <select name="client_id" class="form-control">
                                <option value="">Selecione um cliente</option>
                                @foreach ($clients as $client)
                                    <option value="{{$client->id}}">{{$client->name}}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group m-0">
                            <small>Pasta:</small>
                            <input type="text" name="folder" value="{{ old('folder') }}"
                                class="form-control @error('folder') is-invalid @enderror" placeholder="Nome ou número da pasta"
                                maxlength="50" />
                            @error('folder')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Título:</small>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="form-control @error('title') is-invalid @enderror" placeholder="Digite o título do processo"
                                maxlength="250" />
                            @error('title')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Etiqueta:</small>
                            @php
                                $array_tags = array(1 => 'Fase inicial', 2 => 'Consumidor', 3 => 'Criminal', 4 => 'Cível', 5 => 'Audiência', 6 => 'Citação', 7 => 'Conciliação', 8 => 'Contestação', 9 => 'Sentença', 10 => 'Trabalhista', 11 => 'Tributário');
                             @endphp
                            <select name="tag" class="form-control">
                                @foreach ($array_tags as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @error('tag')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Instância:</small>
                            @php
                                $array_instances = array(1 => '1º Grau', 2 => '2º Grau', 3 => 'Superior', 4 => 'Supremo', 5 => 'Outra');
                            @endphp
                            <select name="instance" class="form-control">
                                @foreach ($array_instances as $key => $value)
                                    <option value="{{$key}}">{{$value}}</option>
                                @endforeach
                            </select>
                            @error('instance')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Número do processo:</small>
                            <input type="text" name="number_process" value="{{ old('number_process') }}"
                                class="form-control @error('number_process') is-invalid @enderror" placeholder="nº"
                                maxlength="250" />
                            @error('number_process')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Juizo:</small>
                            <input type="text" name="juizo" value="{{ old('juizo') }}"
                                class="form-control @error('juizo') is-invalid @enderror" placeholder="nº"
                                maxlength="250" />
                            @error('juizo')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Vara:</small>
                            <input type="text" name="vara" value="{{ old('vara') }}"
                                class="form-control @error('vara') is-invalid @enderror" placeholder="Vara" maxlength="250" />
                            @error('vara')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Foro:</small>
                            <input type="text" name="foro" value="{{ old('foro') }}"
                                class="form-control @error('foro') is-invalid @enderror" placeholder="Foro" maxlength="250" />
                            @error('foro')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group m-0">
                            <small>Ação:</small>
                            <input type="text" name="action" value="{{ old('action') }}"
                                class="form-control @error('action') is-invalid @enderror" placeholder="Digite a ação do processo"
                                maxlength="250" />
                            @error('action')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Dias:</small>
                            <input type="number" name="days" value="{{ old('days') }}" min="1" max="999" placeholder="Qtd. de dias"
                                class="form-control @error('days') is-invalid @enderror" maxlength="3" />
                            @error('days')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Descrição do processo:</small>
                            <textarea name="description" placeholder="Descrição do processo" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Valor da causa:</small>
                            <input type="text" name="valor_causa" onkeyup="moeda(this);" value="{{ old('valor_causa') }}"
                                class="form-control @error('valor_causa') is-invalid @enderror" placeholder="R$ 0,00"
                                maxlength="12" />
                            @error('valor_causa')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Distribuido em:</small>
                            <input type="date" name="data" value="{{ old('data') }}"
                                class="form-control @error('data') is-invalid @enderror"/>
                            @error('data')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group m-0">
                            <small>Valor da condenação:</small>
                            <input type="text" name="valor_condenacao" onkeyup="moeda(this);" value="{{ old('valor_condenacao') }}"
                                class="form-control @error('valor_condenacao') is-invalid @enderror" placeholder="R$ 0,00"
                                maxlength="12" />
                            @error('valor_condenacao')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group m-0">
                            <small>Detalhes do processo:</small>
                            <textarea name="detail" class="form-control @error('detail') is-invalid @enderror">{{ old('detail') }}</textarea>
                            @error('detail')
                                <div class="text-red">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.processes.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                <button type="submit" class="btn btn-md btn-info float-right">
                    <i class="fas fa-save"></i>
                    Salvar dados
                </button>
            </div>
        </div>

    </form>

    <br/><br/>

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
    </script>
@stop
