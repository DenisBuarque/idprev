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

                    <!-- div class="callout callout-info">
                                        <h5><i class="fas fa-info"></i> Messagem:</h5>
                                        Descrição da mensagem enviado pelo financeiro.
                                    </div -->

                    <div class="invoice p-3 mb-3">

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
                                    {{ $lead->court }}<br />
                                    {{ $lead->stick }}
                                </address>
                            </div>
                            <div class="col-sm-3 invoice-col">
                                Prazo:
                                <address>
                                    <strong>{{ $lead->term->format('d/m/Y') }}</strong><br>
                                    {{ $lead->responsible }}<br>

                                    {{ $lead->date_fulfilled }}<br />

                                    @foreach ($actions as $action)
                                        @if ($lead->action == $action->id)
                                            {{ $action->name }}
                                        @endif
                                    @endforeach
                                </address>
                            </div>

                        </div>

                        @foreach ($errors->all() as $message)
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                        @endforeach

                        @if(isset($lead->financy->id))
                            <form method="POST" action="{{ route('admin.financial.update', ['id' => $lead->financy->id]) }}">
                                @csrf
                                @method('PUT')
                        @else
                            <form method="POST" action="{{ route('admin.financial.store') }}">
                                @csrf
                        @endif
                            
                            <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
                            <input type="hidden" name="user_id" value="{{ $lead->user_id }}" />

                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill"
                                                href="#custom-tabs-four-home" role="tab"
                                                aria-controls="custom-tabs-four-home" aria-selected="true">Sobe o
                                                Pagamento</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill"
                                                href="#custom-tabs-four-profile" role="tab"
                                                aria-controls="custom-tabs-four-profile" aria-selected="false">Dados do
                                                Recebimento</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-home-tab">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Confirmação de pagamento: *</small>
                                                        <select name="payment_confirmation"
                                                            class="form-control @error('payment_confirmation') is-invalid @enderror">
                                                            <option value="N"
                                                                @if (old('payment_confirmation') == 'N') selected @endif>Não
                                                                pago</option>
                                                            <option value="S"
                                                                @if (old('payment_confirmation') == 'S') selected @endif>
                                                                Pagamento realizado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Nº Precatorio: *</small>
                                                        <input type="text" name="precatory" id="precatory"
                                                            value="{{ $lead->financy->precatory ?? old('precatory') }}"
                                                            class="form-control @error('precatory') is-invalid @enderror"
                                                            maxlength="30" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data de pagamento: *</small>
                                                        @if (isset($lead->financy->receipt_date))
                                                            <input type="date" name="receipt_date" id="receipt_date"
                                                                value="{{ $lead->financy->receipt_date->format('Y-m-d') ?? old('receipt_date') }}"
                                                                class="form-control @error('receipt_date') is-invalid @enderror" />
                                                        @else
                                                            <input type="date" name="receipt_date" id="receipt_date"
                                                                value="{{ old('receipt_date') }}"
                                                                class="form-control @error('receipt_date') is-invalid @enderror" />
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        @php
                                                            $banks = [1 => 'Banco do Brasil', 2 => 'Banco Ítau', 3 => 'Caixa economica Federal', 4 => 'Bradesco', 5 => 'Banco Santander'];
                                                        @endphp
                                                        <small>Banco: *</small>
                                                        <select name="bank"
                                                            class="form-control @error('bank') is-invalid @enderror">
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
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor Total:</small>
                                                        <input type="text" name="value_total" id="value_total"
                                                            onkeyup="moeda(this);"
                                                            value="{{ number_format($lead->financial, 2, ',', '.') }}"
                                                            class="form-control @error('value_total') is-invalid @enderror"
                                                            maxlength="13" placeholder="0,00" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor do Cliente:</small>
                                                        @if (isset($lead->financy->value_client))
                                                            <input type="text" name="value_client" id="value_client"
                                                                onkeyup="moeda(this);"
                                                                value="{{ number_format($lead->financy->value_client, 2, ',', '.') ?? old('value_client') }}"
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
                                                        <small>Honorários:</small>
                                                        @if (isset($lead->financy->fees))
                                                            <input type="text" name="fees" id="fees" onkeyup="moeda(this);"
                                                                value="{{ number_format($lead->financy->fees, 2, ',', '.') ?? old('fees') }}"
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
                                                        <small>Honorários recebido: *</small>
                                                        <select name="fees_received"
                                                            class="form-control @error('fees_received') is-invalid @enderror">
                                                            @if (isset($lead->financy->fees_received))
                                                                <option value="N"
                                                                    @if ($lead->financy->fees_received == 'N') selected @endif>
                                                                    Aguardando</option>
                                                                <option value="S"
                                                                    @if ($lead->financy->fees_received == 'S') selected @endif>
                                                                    Recebido</option>
                                                            @else
                                                                <option value="N"
                                                                    @if (old('fees_received') == 'N') selected @endif>
                                                                    Aguardando</option>
                                                                <option value="S"
                                                                    @if (old('fees_received') == 'S') selected @endif>
                                                                    Recebido</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-profile-tab">


                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data do recebimento:</small>
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
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Valor do recebimento:</small>
                                                        @if (isset($lead->financy->payment_amount))
                                                            <input type="text" name="payment_amount" id="payment_amount"
                                                                onkeyup="moeda(this);"
                                                                value="{{ number_format($lead->financy->payment_amount,2,',','.') ?? old('payment_amount') }}"
                                                                class="form-control @error('payment_amount') is-invalid @enderror"
                                                                maxlength="13" placeholder="0,00" />
                                                        @else
                                                            <input type="text" name="payment_amount" id="payment_amount"
                                                                onkeyup="moeda(this);"
                                                                value="{{ old('payment_amount') }}"
                                                                class="form-control @error('payment_amount') is-invalid @enderror"
                                                                maxlength="13" placeholder="0,00" />
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Banco do recebimento: *</small>
                                                        @if (isset($lead->financy->payment_bank))
                                                            <select name="payment_bank"
                                                                class="form-control @error('payment_bank') is-invalid @enderror">
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
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Data de confirmação recebimento:</small>
                                                        @if (isset($lead->financy->confirmation_date))
                                                            <input type="date" name="confirmation_date"
                                                                id="confirmation_date"
                                                                value="{{ $lead->financy->confirmation_date ?? old('confirmation_date') }}"
                                                                class="form-control @error('confirmation_date') is-invalid @enderror" />
                                                        @else
                                                            <input type="date" name="confirmation_date"
                                                                id="confirmation_date"
                                                                value="{{ old('confirmation_date') }}"
                                                                class="form-control @error('confirmation_date') is-invalid @enderror" />
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="form-group m-0">
                                                        <small>Pessoa que confirmou recebimento:</small>
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
                                                <div class="col-md-3">
                                                    <div class="form-group m-0">
                                                        <small>Contato pessoa:</small>
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
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <div class="row mt-3">

                                <div class="col-6">
                                    <p class="lead">Instruções financeira:</p>
                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quidem suscipit rerum
                                        consectetur delectus eius repellendus, quam sed totam voluptatum atque
                                        recusandae
                                        quisquam, ut commodi animi culpa vel tempora harum non.</p>
                                </div>

                                <div class="col-6">
                                    <p class="lead">valor total do pagamento:</p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Valor Total:</th>
                                                    <td>{{ number_format($lead->financial, 2, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Honorários:</th>
                                                    <td>
                                                        @if (isset($lead->financy->fees))
                                                            R$ {{ number_format($lead->financy->fees,2,',','.')}}
                                                        @else
                                                            R$ 0,00
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Valo de Pagamento:</th>
                                                    <td>
                                                        @if (isset($lead->financy->payment_amount))
                                                            R$ {{ number_format($lead->financy->payment_amount,2,',','.')}}
                                                        @else
                                                            R$ 0,00
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total:</th>
                                                    <td>{{ number_format($lead->financial, 2, ',', '.') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="row no-print">
                                <div class="col-12">
                                    <a href="{{ route('admin.financial.index') }}" class="btn btn-default">Cancelar</a>
                                    <button id="button" onClick="ocultarExibir()" type="submit" class="btn btn-info float-right">
                                        <i class="far fa-save"></i> Salvar financeiro
                                    </button>
                                    <a id="spinner" class="btn btn-md btn-info float-right text-center">
                                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </a>
                                </div>
                            </div>

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
