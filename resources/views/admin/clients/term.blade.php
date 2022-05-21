@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.term') }}">
        <div style="display: flex; justify-content: start;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Pesquisa."
                    required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">
                        <i class="fa fa-search mr-1"></i>
                        Buscar
                    </button>
                </span>
            </div>
        </div>
    </form>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Clientes convertidos em prazo:</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Prazo</th>
                        <th>Situação</th>
                        <th>Nome</th>
                        <th>Franqueado</th>
                        <th>Etiqueta</th>
                        <th>Anexos</th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        <th class='text-center' style="width: 150px">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>
                                @php
                                    $now = Carbon\Carbon::now()->format('Y-m-d');
                                    if ($lead->term < $now) {
                                        echo '<small class="badge badge-danger">' . $lead->term->format('d/m/Y') . '</small>';
                                    } else {
                                        echo $lead->term->format('d/m/Y');
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $array_situations = [1 => 'Andamento em ordem', 2 => 'Aguardando cumprimento', 3 => 'Finalizado procedente', 4 => 'Finalizado improcedente', 5 => 'Recursos'];
                                    foreach ($array_situations as $key => $value) {
                                        if ($key == $lead->situation) {
                                            echo $value;
                                        }
                                    }
                                @endphp
                            </td>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->user->name }}</td>
                            <td>
                                @php
                                    $array_tags = [1 => 'Novo', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                    foreach ($array_tags as $key => $value) {
                                        if ($key == $lead->tag) {
                                            if ($key == 2) {
                                                echo '<small class="badge badge-warning">' . $value . '</small>';
                                            } elseif ($key == 3) {
                                                echo '<small class="badge badge-success">' . $value . '</small>';
                                            } elseif ($key == 4) {
                                                echo '<small class="badge badge-danger">' . $value . '</small>';
                                            }
                                        }
                                    }
                                @endphp
                            </td>
                            <td>
                                @php
                                    $docs = count($lead->photos);
                                    $anexos = 0;
                                    foreach ($models as $model) {
                                        if ($model->action_id == $lead->action) {
                                            $anexos += 1;
                                        }
                                    }
                                    
                                    if ($anexos > $docs) {
                                        $falta = $anexos - $docs;
                                        echo $docs .' <i class="fas fa-paperclip"></i> falta ' . $falta . ' doc.';
                                    } else {
                                        echo '<i class="fas fa-thumbs-up"></i> ' . $docs . ' anexo(s)';
                                    }
                                    
                                @endphp

                            </td>
                            <td>{{ $lead->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $lead->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                    class="btn btn-xs border mr-1"><i class="fa fa-comments"></i>
                                    {{ count($lead->feedbackLeads) }}</a>
                                <a href="{{ route('admin.clients.edit_term', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-xs px-2 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs px-2">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $leads)
                    {{ $leads->links() }}
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
    </script>
@stop
