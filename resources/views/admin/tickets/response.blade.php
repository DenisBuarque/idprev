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
        <div class="alert alert-success mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert" style="max-width: 800px; margin: auto;">
            {{ session('error') }}
        </div>
    @endif




    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                @if (isset($ticket->user->image))
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset('storage/' . $ticket->user->image) }}" alt="Franqueado">
                                @endif
                            </div>
                            <h3 class="profile-username text-center">{{ $ticket->user->name }}</h3>
                            <p class="text-muted text-center">Franqueado</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Comentários ticket</b> <a class="float-right">{{ count($feedbacks) }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Contato</b> <a class="float-right">{{ $ticket->user->phone }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Membro desde</b> <a class="float-right">{{ $ticket->user->created_at->format('d/m/Y') }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Advogados</b> <a class="float-right">{{ count($ticket->user->lawyers) }}</a>
                                </li>
                            </ul>

                            <ul class="users-list">
                                @foreach ($ticket->user->lawyers as $lawyer)
                                <li>
                                    @if (isset($lawyer->image))
                                        <img src="{{asset('storage/' . $lawyer->image) }}" alt="Foto">
                                    @else
                                        <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto">
                                    @endif
                                    <a class="users-list-name" href="#">{{ $lawyer->name }}</a>
                                    <span class="users-list-date">
                                        Adv.
                                    </span>
                                </li>
                                @endforeach
                            </ul>

                        </div>

                    </div>


                </div>

                <div class="col-md-9">

                    <form method="POST" action="{{ route('admin.tickets.feedback') }}">
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}" />
                        @csrf
                        <div class="card card-info">
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
                                                            <span class="bg-info rounded p-2 float-left">{!! $feed->description !!}</span>
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
                                                            <span class="bg-success rounded p-2 float-right">{!! $feed->description !!}</span>
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
    </script>
@stop
