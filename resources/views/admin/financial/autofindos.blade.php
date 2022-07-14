@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.financial.autofindos') }}">
        <div style="display: flex; justify-content: start;">
            @can('search-financial')
                <div class="input-group" style="width: 40%">
                    <input type="search" name="search" class="form-control" placeholder="Pesquisa."
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

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($total, 2, ',', '.') }}</h3>
                    <p>Acumulado</p>
                </div>
                <div class="icon">
                    <i class="fa fa-coins"></i>
                </div>
                <a href="" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>

            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($received, 2, ',', '.') }}</h3>
                    <p>Recebidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <a href="" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($fees, 2, ',', '.') }}</h3>
                    <p>Honorários a Pagar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                <a href="" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ number_format($unreceived, 2, ',', '.') }}</h3>
                    <p>A receber</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                <a href="" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

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
                        <th>Franqueado</th>
                        <th>Cliente</th>
                        <th>Valor causa</th>
                        <th>Pagamento</th>
                        <th>Recebido</th>
                        <th>Confirmação</th>
                        @can('edit-financial')
                            <th style='width: 60px' class='text-center'>Edit</th>
                        @endcan
                        @can('delete-financial')
                            <th style='width: 50px' class='text-center'>Del</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($financials as $financial)
                        <tr>
                            <td>{{ $financial->user->name }}</td>
                            <td>{{ $financial->lead->name }}</td>
                            <td>{{ number_format($financial->value_total, 2, ',', '.') }}</td>
                            <td>{{  \Carbon\Carbon::parse($financial->receipt_date)->format('d/m/Y') }}</td>
                            <td>{{ number_format($financial->payment_amount, 2, ',', '.') }}</td>
                            <td>{{  \Carbon\Carbon::parse($financial->confirmation_date)->format('d/m/Y') }}</td>
                            @can('edit-financial')
                                <td class='px-1'>
                                    <a href="{{ route('admin.financial.edit', ['id' => $financial->lead_id]) }}"
                                        class="btn btn-info btn-xs btn-block">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            @endcan
                            @can('delete-financial')
                                <td class='px-1'>
                                    <form method="POST" action="{{ route('admin.financial.destroy', ['id' => $financial->id]) }}"
                                        onsubmit="return(confirmaExcluir())">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs btn-block">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 mr-3 ml-3">
                @if ($financials)
                    {{ $financials->links() }}
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
