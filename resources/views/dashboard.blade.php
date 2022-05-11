@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')

    <div class="row">

        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ count($users) }}</h3>
                    <p>Franqueados</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <a href="{{ route('admin.franchisees.index') }}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ count($converted_lead) }}</h3>
                    <p>Leads Convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <a href="{{route('admin.clients.converted')}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ count($waiting) }}</h3>
                    <p>Leads Aguardando</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <a href="{{route('admin.clients.index')}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ count($unconverted_lead) }}</h3>
                    <p>Leads não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <a href="{{route('admin.clients.unconverted')}}" class="small-box-footer">
                    Listar registros <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-lg-9 col-6">

            @if (session('success'))
                <div class="alert alert-success mb-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de leads de atendimento</h3>
                            <div class="card-tools">
                                <a href="" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-lead"><i
                                        class="fa fa-plus mr-2"></i> Adicionar novo lead</a>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0" style="height: 410px;">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Nome</th>
                                        <th>Contato</th>
                                        <th>Etiqueta</th>
                                        <th class="text-center">Coments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($leads as $lead)
                                        <tr>
                                            <td>{{ $lead->created_at->format('d/m/Y H:m:s') }}</td>
                                            <td>{{ $lead->name }}</td>
                                            <td>{{ $lead->phone }}</td>
                                            <td>
                                                @php
                                                    $array_tags = [1 => 'Novo lead', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                                    foreach ($array_tags as $key => $value) {
                                                        if ($key == $lead->tag) {
                                                            if ($key == 1) {
                                                                echo '<small class="badge badge-info">' . $value . '</small>';
                                                            } else {
                                                                echo '<small class="badge badge-warning">' . $value . '</small>';
                                                            }
                                                        }
                                                    }
                                                @endphp
                                            </td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-xs border" data-toggle="modal"
                                                    data-target="#modal-{{ $lead->id }}">
                                                    <i class="fa fa-comments"></i>
                                                    {{ count($lead->feedbackLeads) }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>

            @foreach ($leads as $lead)
                <div class="modal fade" id="modal-{{ $lead->id }}" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <form method="POST" action="">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    @php
                                        $array_tags = [1 => 'Novo', 2 => 'Aguardando', 3 => 'Convertido', 4 => 'Não convertido'];
                                        foreach ($array_tags as $key => $value) {
                                            if ($key == $lead->tag) {
                                                echo '<h4 class="modal-title">Comentário Lead - ' . $value . '</h4>';
                                            }
                                        }
                                    @endphp

                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        Nome: {{ $lead->name }}<br />
                                        Contato(s): {{ $lead->phone }}<br />

                                        @if ($lead->email)
                                            Email: {{ $lead->email }}<br />
                                        @endif

                                        @if ($lead->address)
                                            Endereço:
                                            {{ $lead->address . ', ' . $lead->number . ', ' . $lead->cep . ' ' . $lead->disitrict . ', ' . $lead->city . ', ' . $lead->state }}
                                        @endif
                                    </p>
                                    <textarea name="obs" class="form-control h-20" placeholder="Digite um comentário."></textarea>
                                    <strong>Comentário(s):</strong>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($lead->feedbackLeads as $comment)
                                            <li class="list-group-item">{{ $comment->comments }}</li>
                                        @endforeach
                                    </ul>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                    <button id="button" onClick="ocultarExibir()" type="submit" class="btn btn-info">
                                        <i class="fa fa-save mr-2"></i> Salvar Comentário
                                    </button>
                                    <a id="spinner" class="btn btn-md btn-info float-right text-center">
                                        <div id="spinner" class="spinner-border" role="status"
                                            style="width: 20px; height: 20px;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="modal fade" id="modal-lead" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <form method="POST" action="{{ route('dashboard.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header bg-info">
                                <h4 class="modal-title">Novo Lead</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">x</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <small>Nome completo: *</small>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="form-control @error('name') is-invalid @enderror" maxlength="100" />
                                            @error('name')
                                                <div class="text-red">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group m-0">
                                            <small>Telefones: *</small>
                                            <input type="text" name="phone" value="{{ old('phone') }}"
                                                class="form-control @error('phone') is-invalid @enderror" maxlength="50"
                                                placeholder="Ex: 82 99925-8977, 98854-7889 ..." />
                                            @error('phone')
                                                <div class="text-red">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group m-0">
                                            <small>Franqueado:</small>
                                            <select name="user_id" class="form-control">
                                                <option value="">Selecione um franqueado</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group m-0">
                                            <small>Comentários:</small>
                                            <textarea name="comments" class="form-control">{{ old('comments') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-sm-10">
                                        <div class="form-group m-0">
                                            <small>Endreço:</small>
                                            <input type="text" name="address" id="address" value="{{ old('address') }}"
                                                class="form-control" maxlength="250" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group m-0">
                                            <small>Número:</small>
                                            <input type="text" name="number" value="{{ old('number') }}"
                                                class="form-control" placeholder="nº" maxlength="5" />
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <small>Bairro:</small>
                                            <input type="text" name="district" id="district"
                                                value="{{ old('district') }}" class="form-control" maxlength="50" />
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group m-0">
                                            <small>Cidade:</small>
                                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                                class="form-control" maxlength="50" />
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group m-0">
                                            <small>Estado:</small>
                                            <input type="text" name="state" id="state" value="{{ old('state') }}"
                                                class="form-control" maxlength="2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-md btn-info">
                                    <i class="fas fa-save mr-2"></i>
                                    Salvar dados
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-lg-3 col-6">

            <div class="info-box bg-warning">
                <span class="info-box-icon"><i class="fas fa-tag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Tickets</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>

            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes Procedentes</span>
                    <span class="info-box-number">{{ count($originating_customers) }}</span>
                </div>
            </div>

            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes Improcedentes</span>
                    <span class="info-box-number">{{ count($unfounded_customers) }}</span>
                </div>
            </div>

            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Coube Recursos</span>
                    <span class="info-box-number">{{ count($resources) }}</span>
                </div>
            </div>

            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="far fa-star"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Auto Findos</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>

        </div>

    </div>

    <div class="row row-cols-2 mb-5">
        <div class="col">
            <canvas id="myChartLine"></canvas>
        </div>
        <div class="col">
            <canvas id="myChart"></canvas>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>

        // ChartJs Line - Gráfio em linhas
        const context = document.getElementById('myChartLine');
        const myChartLine = new Chart(context, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Pendentes',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: [0, 20, 5, 2, 20, 30, 42],
                    },
                    {
                        label: 'Resolvidos',
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        data: [3, 15, 12, 25, 40, 35, 20],
                    }
                ]
            },
            options: {}
        });


        //ChatJs Bar - Gráfico em Barras
        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@stop
