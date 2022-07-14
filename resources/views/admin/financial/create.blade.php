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
        <div class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif
    
    <section class="content mb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="invoice p-3">
                        <div class="row invoice-info">

                            <div class="col-sm-3 invoice-col">
                                <strong>Franqueado:</strong>
                                <address>
                                    {{ $lead->user->name }}<br>
                                    {{ $lead->user->address . ', nº ' . $lead->user->number }},
                                    {{ $lead->user->district . ', ' . $lead->user->zip_code . ', ' . $lead->user->city . '/' . $lead->user->state }}<br>
                                    Telefone: {{ $lead->user->phone }}<br>
                                    Email: {{ $lead->user->email }}
                                </address>
                            </div>

                            <div class="col-sm-3 invoice-col">
                                <strong>Cliente:</strong>
                                <address>
                                    {{ $lead->name }}<br/>
                                    {{ $lead->address . ', nº ' . $lead->number }},
                                    {{ $lead->district . ', ' . $lead->zip_code . ', ' . $lead->city . '/' . $lead->state }}<br>
                                    Telefone: {{ $lead->phone }}<br>
                                    Email: {{ $lead->email }}
                                </address>
                            </div>

                            <div class="col-sm-3 invoice-col">
                                <strong>Informações adicionais:</strong>
                                <address>
                                    @php
                                        $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                        foreach ($array_tags as $key => $value) {
                                            if ($key == $lead->tag) {
                                                echo $value . '<br/>';
                                            }
                                        }
                                        
                                        $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                        foreach ($array_situations as $key => $value) {
                                            if ($key == $lead->situation) {
                                                echo $value . '<br/>';
                                            }
                                        }
                                    @endphp
                                    @foreach ($actions as $action)
                                        @if ($lead->action == $action->id)
                                            Ação: {{ $action->name }}<br />
                                        @endif
                                    @endforeach
                                    {{ $lead->court }}<br />
                                    {{ $lead->stick }}
                                </address>
                            </div>

                            <div class="col-sm-3 invoice-col">
                                <strong>Financeiro:</strong>
                                <address>
                                    Valor da causa<br>
                                    R$ {{ number_format($lead->financial, 2, ',', '.') }} reais
                                </address>
                            </div>

                        </div>
                        <!-- end row  -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form method="POST" action="{{ route('admin.financial.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
        <input type="hidden" name="user_id" value="{{ $lead->user_id }}" />

        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-12">

                        <div class="invoice p-3 mb-3">
                            <!-- start row  -->

                            <div class="card card-primary card-outline card-outline-tabs">

                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                                href="#custom-tabs-four-home" role="tab"
                                                aria-controls="custom-tabs-four-home" aria-selected="true">Pagamento do
                                                Franqueado</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-four-profile" role="tab"
                                                aria-controls="custom-tabs-four-profile" aria-selected="false">Recebimento
                                                da Matriz</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-home-tab">

                                            <div class="row">
                                                @can('list-user')
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <small>Confirmação do pagamento pela matriz:</small>
                                                            <select name="confirmation" class="form-control">
                                                                <option value="N">
                                                                    Em analise, aguardando confirmação.</option>
                                                                <option value="S">
                                                                    Pagamento confirmado pela matriz.</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">&nbsp;</div>
                                                @endcan

                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Número do Precatório: *</small>
                                                        <input type="text" name="precatory" id="precatory"
                                                            value="{{ old('precatory') }}"
                                                            class="form-control @error('precatory') is-invalid @enderror"
                                                            maxlength="30" />
                                                        @error('precatory')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data do pagamento: *</small>
                                                        <input type="date" name="receipt_date" id="receipt_date"
                                                            value="{{ old('receipt_date') }}"
                                                            class="form-control @error('receipt_date') is-invalid @enderror" />
                                                        @error('receipt_date')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        @php
                                                            $banks = [1 => 'Banco do Brasil', 2 => 'Banco Ítau', 3 => 'Caixa Economica Federal', 4 => 'Bradesco', 5 => 'Banco Santander'];
                                                        @endphp
                                                        <small>Banco que realizou o pagamento: *</small>
                                                        <select name="bank"
                                                            class="form-control @error('bank') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach ($banks as $key => $bank)
                                                                @if (old('bank') == $key)
                                                                    <option value="{{ $key }}" selected>
                                                                        {{ $bank }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">
                                                                        {{ $bank }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                        @error('bank')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor da causa: *</small>
                                                        <input type="text" name="value_total" id="value_total"
                                                            onkeyup="moeda(this);"
                                                            value="{{ number_format($lead->financial, 2, ',', '.') }}"
                                                            class="form-control @error('value_total') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                        @error('value_total')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor total do cliente: *</small>
                                                        <input type="text" name="value_client" id="value_client"
                                                            onkeyup="moeda(this);" value="{{ old('value_client') }}"
                                                            class="form-control @error('value_client') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                        @error('value_client')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor dos honorários: *</small>
                                                        <input type="text" name="fees" id="fees"
                                                            onkeyup="moeda(this);" value="{{ old('fees') }}"
                                                            class="form-control @error('fees') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                        @error('fees')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Honorários recebido? *</small>
                                                        <select name="fees_received"
                                                            class="form-control @error('fees_received') is-invalid @enderror">
                                                            <option value="N"
                                                                @if (old('fees_received') == 'N') selected @endif>
                                                                Aguardando pagamento.</option>
                                                            <option value="S"
                                                                @if (old('fees_received') == 'S') selected @endif>
                                                                Sim, recebido.</option>
                                                        </select>
                                                        @error('fees_received')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Total de parcelas do pagamento:</small>
                                                        <input type="number" name="installments"
                                                            value="{{ $lead->financy->installments ?? old('installments') }}"
                                                            min="1" max="99" class="form-control"
                                                            placeholder="Opcional" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-profile-tab">
                                            <!-- start row -->
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data do recebimento da matriz:</small>
                                                        <input type="date" name="payday" id="payday"
                                                            value="{{ old('payday') }}" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor recebido pela matriz:</small>
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            onkeyup="moeda(this);" value="{{ old('payment_amount') }}"
                                                            class="form-control" maxlength="13" placeholder="0,00" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Banco que recebeu pagamento:</small>
                                                        <select name="payment_bank" class="form-control">
                                                            <option value=""></option>
                                                            @foreach ($banks as $key => $bank)
                                                                @if (old('payment_bank') == $key)
                                                                    <option value="{{ $key }}" selected>
                                                                        {{ $bank }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">
                                                                        {{ $bank }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data que recebeu o pagamento:</small>
                                                        <input type="date" name="confirmation_date"
                                                            id="confirmation_date"
                                                            value="{{ old('confirmation_date') }}"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <small>Nome do responsável pelo pagamento:</small>
                                                        <input type="text" name="people" id="people"
                                                            value="{{ old('people') }}" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <small>Telefone para contato:</small>
                                                        <input type="text" name="contact" id="contact"
                                                            value="{{ old('contact') }}" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group m-0">
                                                        <textarea name="comments" class="form-control" rows="3"
                                                            placeholder="Digite alguma comentário caso precise acrescenter mais informações."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end row -->
                                        </div>
                                    </div>
                                </div>
                                <!-- end card-body -->
                            </div>
                            <!-- end card -->
                        </div>

                    </div>

                </div>
            </div>

        </section>

        <section class="content mb-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3">
                            <!-- start row -->
                            <div class="row mt-3">
                                <div class="col-6">
                                    <p>Adicione todas as fotos dos comprovantes de pagamentos como anexo para análise do
                                        financeiro, isto
                                        é importante para que a matriz aprove sua tranzação financeiro. Selecione, arraste e
                                        solte todos os documentos sobre o ficheiro.</p>
                                    <div class="form-group">
                                        <input type="file" name="photos[]"
                                            style="border: 1px solid #cccccc; width: 100%; padding: 2px;" multiple>
                                    </div>

                                    @isset($lead->financy->photos)
                                        <div class="row mt-5">
                                            @foreach ($lead->financy->photos as $item)
                                                <div class="col-sm-2">
                                                    <a href="{{ asset('storage/' . $item->image) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $item->image) }}" alt="photo"
                                                            style="width: 100%" /><br />
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endisset

                                </div>
                                <div class="col-6">
                                    @isset($lead->financy->installments)
                                        <p class="lead">Valor total das parelas:</p>
                                        <div class="table-responsive">
                                            @php
                                                $total = $lead->financial / $lead->financy->installments;
                                            @endphp
                                            <table class="table">
                                                <tbody>
                                                    @for ($i = 0; $i < $lead->financy->installments; $i++)
                                                        <tr>
                                                            <th style="width:50%">
                                                                @php
                                                                    echo date('d/m/Y', strtotime($lead->financy->receipt_date->format('Y-m-d') . ' +' . $i . ' month'));
                                                                @endphp
                                                            </th>
                                                            <td>{{ number_format($total, 2, ',', '.') }}</td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="invoice p-3">
                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="{{ route('admin.financial.index') }}" class="btn btn-default">Cancelar</a>
                                    <button id="button" onClick="ocultarExibir()" type="submit"
                                        class="btn btn-success float-right">
                                        <i class="far fa-save"></i> Salvar financeiro
                                    </button>
                                    <a id="spinner" class="btn btn-md btn-info float-right text-center">
                                        <div id="spinner" class="spinner-border" role="status"
                                            style="width: 20px; height: 20px;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </form>

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
