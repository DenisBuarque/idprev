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

    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="invoice p-3 mb-3">
                        <!-- strat row  -->
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    <i class="fas fa-globe"></i> Informações de transação financeira
                                    <small class="float-right">
                                        Criado: {{ $lead->created_at->format('d/m/Y H:m:s') }}
                                    </small>
                                </h4>
                            </div>

                        </div>
                        <!-- end row  -->

                        <!-- start row  -->
                        <div class="row invoice-info">
                            <div class="col-sm-3 invoice-col">
                                Franqueado:
                                <address>
                                    <strong>{{ $lead->user->name }}</strong><br>
                                    {{ $lead->user->address . ', nº ' . $lead->user->number }}<br>
                                    {{ $lead->user->district . ', ' . $lead->user->zip_code . ', ' . $lead->user->city . '/' . $lead->user->state }}<br>
                                    Telefone: {{ $lead->user->phone }}<br>
                                    Email: {{ $lead->user->email }}
                                </address>
                            </div>
                            <div class="col-sm-3 invoice-col">
                                Cliente:
                                <address>
                                    <strong>{{ $lead->name }}</strong><br>
                                    {{ $lead->address . ', nº ' . $lead->number }}<br>
                                    {{ $lead->district . ', ' . $lead->zip_code . ', ' . $lead->city . '/' . $lead->state }}<br>
                                    Telefone: {{ $lead->phone }}<br>
                                    Email: {{ $lead->email }}
                                </address>
                            </div>
                            <div class="col-sm-3 invoice-col">
                                Informações:
                                <address>
                                    @php
                                        $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                        foreach ($array_tags as $key => $value) {
                                            if ($key == $lead->tag) {
                                                echo '<strong>' . $value . '</strong><br/>';
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
                                @isset($lead->term)
                                    Prazo:
                                    <address>
                                        <strong>{{ $lead->term->format('d/m/Y') }}</strong><br>
                                        @isset($lead->responsible)
                                            {{ $lead->responsible }}<br>
                                        @endisset
                                        @isset($lead->date_fulfilled)
                                            {{ $lead->date_fulfilled }}<br />
                                        @endisset
                                    </address>
                                @endisset
                            </div>
                        </div>
                        <!-- end row  -->

                        @if (isset($lead->financy->id))
                            <form method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.financial.update', ['id' => $lead->financy->id]) }}">
                                @csrf
                                @method('PUT')
                            @else
                                <form method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.financial.store') }}">
                                    @csrf
                        @endif

                        <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
                        <input type="hidden" name="user_id" value="{{ $lead->user_id }}" />

                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                            href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                                            aria-selected="true">Pagamento do Franqueado</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                            href="#custom-tabs-four-profile" role="tab"
                                            aria-controls="custom-tabs-four-profile" aria-selected="false">Recebimento da
                                            Matriz</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-home-tab">

                                        <div class="row">
                                            @can('list-user')
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <small>Confirmação do pagamento pela matriz:</small>
                                                        <select name="payment_confirmation"
                                                            class="form-control @error('payment_confirmation') is-invalid @enderror">
                                                            <option value="N" @if ($lead->financy->payment_confirmation == "N" or old('payment_confirmation') == 'N') selected @endif>
                                                                Em analise, aguardando confirmação...</option>
                                                            <option value="S" @if ($lead->financy->payment_confirmation == "S" or old('payment_confirmation') == 'S') selected @endif>
                                                                Pagamento confirmado pela matriz, obrigado!</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endcan
                                            
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Número do Precatório:</small>
                                                    <input type="text" name="precatory" id="precatory"
                                                        value="{{ $lead->financy->precatory ?? old('precatory') }}"
                                                        class="form-control @error('precatory') is-invalid @enderror"
                                                        maxlength="30" />
                                                    @error('precatory')
                                                        <div class="text-red">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Qual a data do pagamento?</small>
                                                    @if (isset($lead->financy->receipt_date))
                                                        <input type="date" name="receipt_date" id="receipt_date"
                                                            value="{{ $lead->financy->receipt_date->format('Y-m-d') ?? old('receipt_date') }}"
                                                            class="form-control @error('receipt_date') is-invalid @enderror" />
                                                    @else
                                                        <input type="date" name="receipt_date" id="receipt_date"
                                                            value="{{ old('receipt_date') }}"
                                                            class="form-control @error('receipt_date') is-invalid @enderror" />
                                                    @endif
                                                    @error('receipt_date')
                                                        <div class="text-red">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    @php
                                                        $banks = [1 => 'Banco do Brasil', 2 => 'Banco Ítau', 3 => 'Caixa economica Federal', 4 => 'Bradesco', 5 => 'Banco Santander'];
                                                    @endphp
                                                    <small>Qual banco realizou o pagamento?</small>
                                                    <select name="bank"
                                                        class="form-control @error('bank') is-invalid @enderror">
                                                        <option value=""></option>
                                                        @if (isset($lead->financy->bank))
                                                            @foreach ($banks as $key => $bank)
                                                                @if ($lead->financy->bank == $key || old('bank') == $key)
                                                                    <option value="{{ $key }}" selected>
                                                                        {{ $bank }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">
                                                                        {{ $bank }}</option>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @foreach ($banks as $key => $bank)
                                                                @if (old('bank') == $key)
                                                                    <option value="{{ $key }}" selected>
                                                                        {{ $bank }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">
                                                                        {{ $bank }}</option>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('bank')
                                                        <div class="text-red">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Valor total do pagamento:</small>
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
                                                    <small>Informe o valor total do cliente:</small>
                                                    @if (isset($lead->financy->value_client))
                                                        <input type="text" name="value_client" id="value_client"
                                                            onkeyup="moeda(this);"
                                                            @if (!empty($lead->financy->value_client)) value="{{ number_format($lead->financy->value_client, 2, ',', '.') }}"
                                                            @else 
                                                                value="{{ old('value_client') }}" @endif
                                                            class="form-control @error('value_client') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @else
                                                        <input type="text" name="value_client" id="value_client"
                                                            onkeyup="moeda(this);" value="{{ old('value_client') }}"
                                                            class="form-control @error('value_client') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Informe o valor dos honorários:</small>
                                                    @if (!empty($lead->financy->fees))
                                                        <input type="text" name="fees" id="fees" onkeyup="moeda(this);"
                                                            @if ($lead->financy->fees) value="{{ number_format($lead->financy->fees, 2, ',', '.') }}"
                                                            @else 
                                                                value="{{ old('fees') }}" @endif
                                                            class="form-control @error('fees') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @else
                                                        <input type="text" name="fees" id="fees" onkeyup="moeda(this);"
                                                            value="{{ old('fees') }}"
                                                            class="form-control @error('fees') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Os honorários foram recebido?</small>
                                                    <select name="fees_received"
                                                        class="form-control @error('fees_received') is-invalid @enderror">
                                                        @if (isset($lead->financy->fees_received))
                                                            <option value="N"
                                                                @if ($lead->financy->fees_received == 'N') selected @endif>
                                                                Aguardando pagamento</option>
                                                            <option value="S"
                                                                @if ($lead->financy->fees_received == 'S') selected @endif>
                                                                Honorários recebido</option>
                                                        @else
                                                            <option value="N"
                                                                @if (old('fees_received') == 'N') selected @endif>
                                                                Aguardando pagamento</option>
                                                            <option value="S"
                                                                @if (old('fees_received') == 'S') selected @endif>
                                                                Honorários recebidos</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group m-0">
                                                    <small>Informe o total de parcelas se ouver:</small>
                                                    <input type="number" name="installments"
                                                        value="{{ $lead->financy->installments ?? old('installments') }}"
                                                        min="1" max="100" class="form-control" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                        aria-labelledby="custom-tabs-four-profile-tab">
                                        <!-- start row -->
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Qual a data do recebimento?</small>
                                                    @if (isset($lead->financy->payday))
                                                        <input type="date" name="payday" id="payday"
                                                            value="{{ $lead->financy->payday ?? old('payday') }}"
                                                            class="form-control @error('payday') is-invalid @enderror" />
                                                    @else
                                                        <input type="date" name="payday" id="payday"
                                                            value="{{ old('payday') }}"
                                                            class="form-control @error('payday') is-invalid @enderror" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Qual valor recebido?</small>
                                                    @if (isset($lead->financy->payment_amount))
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            onkeyup="moeda(this);"
                                                            value="{{ number_format($lead->financy->payment_amount, 2, ',', '.') ?? old('payment_amount') }}"
                                                            class="form-control @error('payment_amount') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @else
                                                        <input type="text" name="payment_amount" id="payment_amount"
                                                            onkeyup="moeda(this);" value="{{ old('payment_amount') }}"
                                                            class="form-control @error('payment_amount') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Qual banco recebeu?</small>
                                                    @if (isset($lead->financy->payment_bank))
                                                        <select name="payment_bank"
                                                            class="form-control @error('payment_bank') is-invalid @enderror">
                                                            <option value=""></option>
                                                            @foreach ($banks as $key => $bank)
                                                                @if ($lead->financy->payment_bank == $key)
                                                                    <option value="{{ $key }}" selected>
                                                                        {{ $bank }}</option>
                                                                @else
                                                                    <option value="{{ $key }}">
                                                                        {{ $bank }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select name="payment_bank"
                                                            class="form-control @error('payment_bank') is-invalid @enderror">
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
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Qual data recebeu o pagamento?</small>
                                                    @if (isset($lead->financy->confirmation_date))
                                                        <input type="date" name="confirmation_date" id="confirmation_date"
                                                            value="{{ $lead->financy->confirmation_date ?? old('confirmation_date') }}"
                                                            class="form-control @error('confirmation_date') is-invalid @enderror" />
                                                    @else
                                                        <input type="date" name="confirmation_date" id="confirmation_date"
                                                            value="{{ old('confirmation_date') }}"
                                                            class="form-control @error('confirmation_date') is-invalid @enderror" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Nome do responsável:</small>
                                                    @if (isset($lead->financy->people))
                                                        <input type="text" name="people" id="people"
                                                            value="{{ $lead->financy->people ?? old('people') }}"
                                                            class="form-control @error('people') is-invalid @enderror" />
                                                    @else
                                                        <input type="text" name="people" id="people"
                                                            value="{{ old('people') }}"
                                                            class="form-control @error('people') is-invalid @enderror" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <small>Telefone para contato:</small>
                                                    @if (isset($lead->financy->contact))
                                                        <input type="text" name="contact" id="contact"
                                                            value="{{ $lead->financy->contact ?? old('contact') }}"
                                                            class="form-control @error('contact') is-invalid @enderror" />
                                                    @else
                                                        <input type="text" name="contact" id="contact"
                                                            value="{{ old('contact') }}"
                                                            class="form-control @error('contact') is-invalid @enderror" />
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group m-0">
                                                    <textarea name="comments" class="form-control" rows="3"
                                                        placeholder="Digite alguma comentário caso precise acrescenter mais informações.">{{ $lead->financy->comments }}</textarea>
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

                        <!-- start row -->
                        <div class="row mt-3">
                            <div class="col-6">
                                <p class="lead">Anexos de documento:</p>
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
                        <!-- end row  -->

                        <!-- start row -->
                        <div class="row no-print mt-10">
                            <div class="col-12">
                                <hr />
                                <a href="{{ route('admin.financial.index') }}" class="btn btn-default">Cancelar</a>
                                <button id="button" onClick="ocultarExibir()" type="submit"
                                    class="btn btn-info float-right">
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
                        <!-- end row  -->

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </section>

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
