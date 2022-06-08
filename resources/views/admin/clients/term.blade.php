@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.clients.term') }}">
        <div style="display: flex; justify-content: start;">
            <div class="input-group" style="width: 40%">
                <input type="search" name="search" class="form-control" placeholder="Pesquisa"
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
                        <th>Franqueado</th>
                        <th>Cliente</th>
                        <th>Situação</th>
                        <th>Prazo</th>
                        <th>Etiqueta</th>
                        <th>Anexos</th>
                        <th>Criado</th>
                        <th>Atualizado</th>
                        @can('edit-term')
                            <th style='width: 60px' class='text-center'></th>
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-term')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>
                                @if (isset($lead->user->image))
                                    <img src="{{asset('storage/' . $lead->user->image) }}" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @else
                                    <img src="https://dummyimage.com/28x28/b6b7ba/fff" alt="Foto" class="img-circle mr-2" style="width: 28px; height: 28px;">
                                @endif
                                {{ $lead->user->name }}
                            </td>
                            <td>{{ $lead->name }}</td>
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
                                    echo $docs. ' de ' . $anexos;
                                @endphp
                            </td>
                            <td>{{ $lead->created_at->format('d/m/Y H:m:s') }}</td>
                            <td>{{ $lead->updated_at->format('d/m/Y H:m:s') }}</td>
                            <td class='px-1'>
                                <a href="{{ route('admin.clients.show', ['id' => $lead->id]) }}"
                                    class="btn btn-xs border btn-block"><i class="fa fa-comments"></i>
                                    {{ count($lead->feedbackLeads) }}</a>
                            </td>
                            <td class='px-1'>
                                <a href="{{ route('admin.clients.edit_term', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-xs btn-block">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                            <td class='px-1'>
                                <form method="POST" onsubmit="return(confirmaExcluir())"
                                    action="{{ route('admin.clients.destroy', ['id' => $lead->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs btn-block">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">
                                <span>Nenhum registro cadastro</span>
                            </td>
                        </tr>
                    
                    @endforelse
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
