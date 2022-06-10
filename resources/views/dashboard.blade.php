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
                @can('list-franchisee')
                    <a href="{{ route('admin.franchisees.index') }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endcan

            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $waiting }}</h3>
                    <p>Leads Aguardando</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
                @can('list-lead')
                    @if($waiting > 0)
                    <a href="{{ route('admin.leads.tag', ['tag' => 2]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @else
                        <a class="small-box-footer">
                            &nbsp;
                        </a>
                    @endif
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endcan
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $converted_lead }}</h3>
                    <p>Leads Convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                @can('list-lead')
                    @if ($converted_lead > 0)
                    <a href="{{ route('admin.leads.tag', ['tag' => 3]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @else
                        <a class="small-box-footer">
                            &nbsp;
                        </a>
                    @endif
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endcan
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $unconverted_lead }}</h3>
                    <p>Leads não convertidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-thumbs-down"></i>
                </div>
                @can('list-lead')
                    @if($unconverted_lead > 0)
                    <a href="{{ route('admin.leads.tag', ['tag' => 4]) }}" class="small-box-footer">
                        Listar registros <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @else
                        <a class="small-box-footer">
                            &nbsp;
                        </a>
                    @endif
                @else
                    <a class="small-box-footer">
                        &nbsp;
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-9 col-6">

            @if (session('success'))
                <div id="message" class="alert alert-success mb-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Lista de leads de atendimento</h3>
                            <div class="card-tools">
                                @can('create-lead')
                                    <a href="" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-lead"><i
                                            class="fa fa-plus mr-2"></i> Adicionar novo lead</a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body table-responsive p-0" style="height: 410px;">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Nome</th>
                                        <th>Contato</th>
                                        <th></th>
                                        @can('comments-lead')
                                            <th class="text-center" style="width: 60px"></th>
                                        @endcan
                                        @can('edit-lead')
                                            <th class="text-center" style="width: 40px">Edit</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($leads as $lead)
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
                                            @can('comments-lead')
                                                <td class="px-1">
                                                    <a href="{{ route('admin.leads.show', ['id' => $lead->id]) }}"
                                                        class="btn btn-xs btn-light border btn-block">
                                                        <i class="fa fa-comments"></i> {{ count($lead->feedbackLeads) }}
                                                    </a>
                                                </td>
                                            @endcan
                                            @can('edit-lead')
                                                <td class="px-1">
                                                    <a href="{{ route('admin.leads.edit', ['id' => $lead->id]) }}"
                                                        class="btn btn-info btn-xs btn-block">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            @endcan
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <span>Nenhum registro cadastrado</span>
                                            </td>
                                        </tr>
                                        @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-lead" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-lg">
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
                                <small>Os campos com * são de preenchimento obrigatório:</small>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <small>Nome: *</small>
                                            <input type="text" name="name" value="{{ old('name') }}" autofocus
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
                                            <small>Franqueado: *</small>
                                            <select name="user_id"
                                                class="form-control @error('user_id') is-invalid @enderror">
                                                <option value="">Selecione um franqueado</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="text-red">{{ $message }}</div>
                                            @enderror
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
                                            <small>Endereço:</small>
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
                    <span class="info-box-number">Abertos: {{ $tickets }} Penentes:
                        {{ $tickets_pendentes }}</span>
                </div>
            </div>
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes Procedentes</span>
                    <span class="info-box-number">{{ $originating_customers }}</span>
                </div>
            </div>
            <div class="info-box">
                <span class="info-box-icon bg-danger"><i class="far fa-flag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Clientes Improcedentes</span>
                    <span class="info-box-number">{{ $unfounded_customers }}</span>
                </div>
            </div>
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Coube Recursos</span>
                    <span class="info-box-number">{{ $resources }}</span>
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

    <!-- div class="row row-cols-2 my-5">
        <div class="col">
            <canvas id="myChartLine"></canvas>
        </div>
        <div class="col">
            <canvas id="myChart"></canvas>
        </div>
    </div -->

    <div class="card card-success">
        <div class="card-body">

            <div class="row">

                @foreach ($events as $event)
                    <div class="col-md-12 col-lg-6 col-xl-4">
                        <div class="card mb-2">
                            <img class="card-img-top" src="{{ asset('storage/' . $event->image) }}"
                                alt="{{ $event->title }}">
                            <div class="d-flex flex-column justify-content-end mt-2 p-3">
                                <h4 class="text-primary mb-3">{{ $event->title }}</h4>
                                <strong>Data: {{ $event->date_event->format('d/m/Y') }}</strong>
                                <p>{!! $event->description !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        setTimeout(() => {
            document.getElementById('message').style.display = 'none';
        }, 6000);

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
