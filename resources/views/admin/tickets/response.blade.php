@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div style="display: flex; justify-content: space-between">
        <h4>Meu Ticket Resposta</h4>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-md bg-info">Listar Tickets</a>
    </div>
@stop

@section('content')

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 700px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tickets.feedback') }}">
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
        @csrf
        <div class="card card-info" style="max-width: 700px; margin: auto">
            <div class="card-header">
                <h3 class="card-title">Formulário resposta de ticket:</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">

                        <p>Estamos a sua disposição para melhor lhe atender, descreva sua dúvida, elogio ou reclamação que
                            em breve responderemos seu ticket de contato.</p>
                        <div class="direct-chat-messages">
                            @foreach ($feedbacks as $feed)
                                @if ($feed->user_id == auth()->user()->id)
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix mb-1">
                                            <span class="direct-chat-name float-left">{{ auth()->user()->name }}</span>
                                            <span
                                                class="direct-chat-timestamp ml-2">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                        </div>
                                        <div>
                                            <span class="bg-info rounded p-2 float-left">{{ $feed->description }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-infos clearfix mb-1">
                                            @foreach ($users as $user)
                                                @if ($feed->user_id == $user->id)
                                                    <span
                                                        class="direct-chat-timestamp ml-2 float-right">{{ $feed->created_at->format('d/m/Y H:m:s') }}</span>
                                                    <span class="direct-chat-name float-right">{{ $user->name }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span
                                                class="bg-success rounded p-2 float-right">{{ $feed->description }}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if ($ticket->status != 2)
                            <div class="form-group m-0 mt-3">
                                <textarea name="description" placeholder="Digite seu comentário aqui."
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-red">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.tickets.index') }}" type="submit" class="btn btn-default">Cancelar</a>
                @if ($ticket->status != 2)
                    <button id="button" type="submit" onClick="ocultarExibir()" class="btn btn-md btn-info float-right">
                        <div id="text">
                            <i class="fas fa-save mr-2"></i>
                            Responder Ticket
                        </div>
                    </button>
                    <a id="spinner" class="btn btn-md btn-info float-right text-center">
                        <div id="spinner" class="spinner-border" role="status" style="width: 20px; height: 20px;">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </a>
                @endif
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
    </script>
@stop
