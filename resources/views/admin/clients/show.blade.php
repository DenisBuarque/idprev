@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Lead</h4>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-md bg-info">Listar Registros</a>
    </div>
@stop

@section('content')

    <section class="content">

        @if (session('err'))
            <div class="alert alert-danger mb-2" role="alert">
                {{ session('err') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Acompanhe os detalhes do seu lead:</h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-4">
                        <h3 class="text-primary"><i class="fas fa-user"></i> {{ $lead->name }}</h3>
                        <p class="text-muted">
                            @if (!empty($lead->address))
                                {{ $lead->address . ', ' . $lead->number . ', ' . $lead->district . ', ' . $lead->zip_code . ' ' . $lead->city . ', ' . $lead->state }}<br />
                            @endif
                            Telefone: {{ $lead->phone }}
                            @if (!empty($lead->email))
                                <br />E-mail: {{ $lead->email }}
                            @endif
                        </p>
                        <p class="text-sm">Franqueado:
                            <b class="d-block">{{ $lead->user->name }}</b>
                        </p>
                        <p class="text-sm m-0">Criado:
                            <b class="d-block">{{ $lead->created_at->format('d/m/Y H:m:s') }}</b>
                        </p>
                        <p class="text-sm m-0">Atualizado:
                            <b class="d-block">{{ $lead->updated_at->format('d/m/Y H:m:s') }}</b>
                        </p>
                        <p class="text-sm m-0">Nº Processo:
                            <b class="d-block">{{ $lead->process }}</b>
                        </p>
                        <p class="text-sm m-0">Tipo de Ação:
                            @foreach ($actions as $action)
                                @if ($action->id == $lead->action)
                                    <b class="d-block">{{ $action->name }}</b>
                                @endif
                            @endforeach
                        </p>
                        <p class="text-sm m-0">Tribunal:
                            <b class="d-block">{{ $lead->court }}</b>
                        </p>
                        <p class="text-sm m-0">Vara:
                            <b class="d-block">{{ $lead->stick }}</b>
                        </p>
                        @if ($lead->situation == 2)
                            <p class="text-sm m-0">Prazo:
                                <b class="d-block">{{ $lead->term->format('d/m/Y') }}</b>
                            </p>
                            <p class="text-sm m-0">Responsável:
                                <b class="d-block">{{ $lead->responsible }}</b>
                            </p>
                            @isset($lead->date_fulfilled)    
                                <p class="text-sm m-0">Data Cumprimento:
                                    <b class="d-block">{{ $lead->date_fulfilled->format('d/m/Y') }}</b>
                                </p>
                            @endisset
                            <p class="text-sm m-0">Cumprimento:
                                <b class="d-block">{!! $lead->greeting !!}</b>
                            </p>
                        @endif

                        <h5 class="mt-4 text-muted">Modelos de documentos a anexar:</h5>
                        <ul class="list-unstyled">
                            @foreach ($models as $model)
                                @if ($model->action_id == $lead->action)
                                    <li>
                                        <a href="{{ route('admin.client.model.download', ['id' => $model->id]) }}"
                                            title="Clique para baixar o documento" class="btn-link text-secondary">
                                            <i class="far fa-fw fa-file-pdf"></i> {{ $model->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>

                        <h5 class="mt-2 text-muted">Documentos anexados:</h5>
                        <ul class="list-unstyled">
                            @foreach ($lead->photos as $photo)
                                <li>
                                    <a href="{{ route('admin.client.document.download', ['id' => $photo->id, 'lead' => $lead->id]) }}"
                                        class="btn-link text-secondary"><i class="far fa-fw fa-image"></i>
                                        {{ Str::substr($photo->image, 30) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                    <div class="col-12 col-md-12 col-lg-8">
                        <div class="row">
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Criado</span>
                                        <span
                                            class="info-box-number text-center text-muted mb-0">{{ $lead->created_at->format('d/m/Y H:m:s') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Etiqueta</span>
                                        @php
                                            $array_tags = [1 => 'Novo Lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                            foreach ($array_tags as $key => $value) {
                                                if ($key == $lead->tag) {
                                                    echo '<span class="info-box-number text-center text-muted mb-0">' . $value . '</span>';
                                                }
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-text text-center text-muted">Situação</span>
                                        @php
                                            $arr = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado Procedente', 4 => 'Finalizado Improcedente', 5 => 'Recursos'];
                                            foreach ($arr as $key => $value) {
                                                if ($key == $lead->situation) {
                                                    echo '<span class="info-box-number text-center text-muted mb-0">' . $value . '</span>';
                                                }
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                @if (session('success'))
                                    <div id="message" class="alert alert-success mb-2" role="alert"
                                        style="max-width: 800px; margin: auto;">
                                        {{ session('success') }}
                                    </div>
                                @elseif (session('error'))
                                    <div class="alert alert-danger mb-2" role="alert"
                                        style="max-width: 800px; margin: auto;">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.client.feedback') }}">
                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}" />
                                    @csrf
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Comentários do lead:</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="direct-chat-messages">
                                                @foreach ($feedbackLeads as $feed)
                                                    @if ($feed->user_id == auth()->user()->id)
                                                        <div class="direct-chat-msg">
                                                            <div class="direct-chat-infos clearfix mb-1">
                                                                <span
                                                                    class="direct-chat-name float-left">{{ auth()->user()->name }}</span>
                                                                <span
                                                                    class="direct-chat-timestamp ml-2">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="bg-info rounded p-2 float-left">{!! $feed->comments !!}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="direct-chat-msg">
                                                            <div class="direct-chat-infos clearfix mb-1">
                                                                @foreach ($users as $user)
                                                                    @if ($feed->user_id == $user->id)
                                                                        <span
                                                                            class="direct-chat-timestamp ml-2 float-right">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                                                        <span
                                                                            class="direct-chat-name float-right">{{ $user->name }}</span>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                            <div>
                                                                <span
                                                                    class="bg-success rounded p-2 float-right">{!! $feed->comments !!}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>

                                            
                                                @if ($lead->status != 2)
                                                    <div class="form-group m-0 mt-3">
                                                        <textarea name="comments" placeholder="Digite seu comentário aqui."
                                                            class="form-control @error('comments') is-invalid @enderror">{{ old('description') }}</textarea>
                                                        @error('comments')
                                                            <div class="text-red">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endif
                                           
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('admin.leads.index') }}" type="submit"
                                                class="btn btn-default">Cancelar</a>
                                            
                                                @if ($lead->status != 2)
                                                    <button id="button" type="submit" onClick="ocultarExibir()"
                                                        class="btn btn-md btn-info float-right">
                                                        <div id="text">
                                                            <i class="fas fa-save mr-2"></i>
                                                            Salvar Comentário
                                                        </div>
                                                    </button>
                                                
                                                <a id="spinner" class="btn btn-md btn-info float-right text-center">
                                                    <div id="spinner" class="spinner-border" role="status"
                                                        style="width: 20px; height: 20px;">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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

    <script>
        document.getElementById("button").style.display = "block";
        document.getElementById("spinner").style.display = "none";

        function ocultarExibir() {
            document.getElementById("button").style.display = "none";
            document.getElementById("spinner").style.display = "block";
        }

        setTimeout(() => {
            console.log('fecha message...');
            document.getElementById('message').style.display = 'none';
        }, 5000);
    </script>
@stop
