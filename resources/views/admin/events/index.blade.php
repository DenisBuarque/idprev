@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.events.index') }}">
        <div style="display: flex; justify-content: space-between;">
            @can('search-event')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Título" required />
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-info btn-flat">
                            <i class="fa fa-search mr-1"></i> Buscar
                        </button>
                    </span>
                </div>
            @endcan
            @can('create-event')
                <a href="{{ route('admin.events.create') }}" class="btn bg-info">
                    <i class="fa fa-plus mr-1"></i> Adicionar Registro
                </a>
            @endcan
        </div>
    </form>
@stop

@section('content')

    @if (session('success'))
        <div id="message" class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div id="message" class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Eventos Treinamento</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Data do Evento</th>
                        <th style="width: 160px;">Criado</th>
                        <th style="width: 160px;">Atualizado</th>
                        @can('edit-event')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-event')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->date_event->format('d/m/Y') }}</td>
                            <td>{{ $event->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $event->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='px-1'>
                                @can('edit-event')
                                    <a href="{{ route('admin.events.edit', ['id' => $event->id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan
                            </td>
                            <td class='px-1'>
                                @can('delete-event')
                                    <form method="POST" onsubmit="return(confirmaExcluir())"
                                        action="{{ route('admin.events.destroy', ['id' => $event->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center" colspan="6">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $events)
                    {{ $events->links() }}
                @endif
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        function confirmaExcluir() {
            var conf = confirm("Deseja mesmo excluir? Os dados serão perdidos e não poderam ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
