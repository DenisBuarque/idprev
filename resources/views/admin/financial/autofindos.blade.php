@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.financial.autofindos') }}">
        <div style="display: flex; justify-content: start;">
            @can('search-financial')
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
            <h3 class="card-title">Financeiro Auto Findos</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Franqueado</th>
                        <th>Confirmação</th>
                        <th>Valor Total</th>
                        <th>Data Pag.</th>
                        <th>Valor Rec.</th>
                        @can('edit-financial')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-financial')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leads as $lead)
                        @isset($lead->financy->payment_confirmation)
                            @if ($lead->financy->payment_confirmation == 'S' && $lead->financy->fees_received == 'S')
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
                                            {{ number_format($lead->financy->value_total, 2, ',', '.') }}
                                        @else
                                            <small class="badge badge-warning">Aguardando</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($lead->financy->receipt_date))
                                            {{ $lead->financy->receipt_date->format('d/m/Y') }}
                                        @else
                                            <small class="badge badge-warning">Aguardando</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($lead->financy->payment_amount))
                                            {{ number_format($lead->financy->payment_amount, 2, ',', '.') }}
                                        @else
                                            <small class="badge badge-warning">Aguardando</small>
                                        @endif
                                    </td>
                                    <td class='px-1'>
                                        @can('edit-financial')
                                            <a href="{{ route('admin.financial.edit', ['id' => $lead->id]) }}"
                                                class="btn btn-info btn-xs btn-block">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </td>
                                    <td class='px-1'>
                                        @can('delete-financial')
                                            <form method="POST"
                                                action="{{ route('admin.financial.destroy', ['id' => $lead->id]) }}"
                                                onsubmit="return(confirmaExcluir())">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs btn-block">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endif
                        @endisset
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

        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);
    </script>
@stop
