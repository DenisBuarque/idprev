@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <form method="GET" action="{{ route('admin.advisors.index') }}">
        <div style="display: flex; justify-content: space-between;">
            <div class="input-group" style="width: 30%">
                <input type="search" name="search" value="{{$search}}" class="form-control"
                    placeholder="Pesquisa." required />
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat">Buscar</button>
                </span>
            </div>
            <a href="{{ route('admin.advisors.create') }}" class="btn bg-info">Adicionar Registro</a>
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
            <h3 class="card-title">Lista de Franqueados</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Telefone</th>
                        <th>E-mail</th>
                        <th>Endereço</th>
                        <th class='text-center'>Cliente(s)</th>
                        <th style="width: 100px; text-align: center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advisors as $advisor)
                        <tr>
                            <td>{{ $advisor->name }}</td>
                            <td>{{ $advisor->phone }}</td>
                            <td>{{ $advisor->email }}</td>
                            <td>{{$advisor->address}}, {{$advisor->number}}, {{$advisor->district}}, {{$advisor->city}}/{{$advisor->state}}</td>
                            <td class='text-center'>{{ count($advisor->clients) }}</td>
                            <td class='d-flex flex-row align-content-center justify-content-center'>
                                <a href="{{route('admin.advisors.edit',['id' => $advisor->id])}}"
                                    class="btn btn-info btn-sm mr-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{route('admin.advisors.destroy',['id' => $advisor->id])}}" onsubmit="return(confirmaExcluir())">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="mt-3 mr-3 ml-3">
                @if (!$search && $advisors)
                    {{ $advisors->links() }}
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
