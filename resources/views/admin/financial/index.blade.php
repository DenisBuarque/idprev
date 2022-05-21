@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.financial.index') }}">
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

    @if (session('success'))
        <div class="alert alert-success mb-2" role="alert">
            {{ session('success') }}
        </div>
    @elseif (session('alert'))
        <div class="alert alert-warning mb-2" role="alert">
            {{ session('alert') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger mb-2" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Financeiro</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Franqueado</th>
                        <th>Conf. Pag.</th>
                        <th>Valor Total</th>
                        <th>Data Pag.</th>
                        <th>Valor Rec.</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        <tr>
                            <td>{{ $lead->name }}</td>
                            <td>{{ $lead->user->name }}</td>
                            <td>
                                @if (isset($lead->financy->payment_confirmation))
                                    @if ($lead->financy->payment_confirmation == 'N')
                                        <small class="badge badge-danger">Não confirmado</small>
                                    @else
                                        <small class="badge badge-success">Confirmado</small>
                                    @endif
                                @else
                                    <small class="badge badge-warning">Aguardando</small>
                                @endif
                            </td>
                            <td>
                                @if (isset($lead->financy->value_total))
                                    {{ number_format($lead->financy->value_total,2,',','.')}}
                                @else
                                    <small class="badge badge-warning">Aguardando</small>
                                @endif
                            </td>
                            <td>
                                @if (isset($lead->financy->receipt_date))
                                    {{$lead->financy->receipt_date->format('d/m/Y')}}
                                @else
                                    <small class="badge badge-warning">Aguardando</small>
                                @endif
                            </td>
                            <td>
                                @if (isset($lead->financy->payment_amount))
                                    {{number_format($lead->financy->payment_amount,2,',','.')}}
                                @else
                                    <small class="badge badge-warning">Aguardando</small>
                                @endif
                            </td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{ route('admin.financial.edit', ['id' => $lead->id]) }}"
                                    class="btn btn-info btn-xs px-2 mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.financial.destroy', ['id' => $lead->id]) }}"
                                    onsubmit="return(confirmaExcluir())">
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
            var conf = confirm("Deseja mesmo excluir? Todos os dados serão perdidos e não pederão ser recuperados.");
            if (conf) {
                return true;
            } else {
                return false;
            }
        }
    </script>
@stop
