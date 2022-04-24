<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        h1,
        h3 {
            font-family: 'Bebas Neue', cursive !important;
        }

    </style>
</head>

<body>

    <header class="w-full bg-blue-900 shadow-outline">
        <nav class="p-5 md:container md:m-auto md:p-0 md:py-5">

            <div class="flex justify-between">
                <div class="mb-3 md:mb-0">
                    <a href=""><img src="{{ asset('images/logotipo-white.png') }}" class="w-48 m-auto"
                            alt="Logotipo Id Preve" /></a>
                </div>
                <div class="flex items-center justify-center">
                    <div class="hidden md:block">
                        <div class="flex items-center justify-center">
                            <!-- img src="https://dummyimage.com/100x100/000/fff" alt="Foto Franqueado"
                                class="w-8 h-8 rounded-full mr-2" / -->
                            <span class="text-white">Olá, {{ auth()->guard('advisor')->user()->name }}</span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('logout.franchisee') }}" class="ml-10 text-red-500 font-bold">Sair [x]</a>
                    </div>
                </div>
            </div>

        </nav>
    </header>

    <!-- tipos de serviços -->
    <section class="w-full px-4 py-10 md:px-0 md:container md:m-auto">

        <div class="flex flex-col">

            <div class="flex items-center justify-between">
                <h3 class="text-4xl text-blue-800">Franqueado: Detalhe do Cliente</h3>
                <a href="{{ route('site.franchisee') }}" class="border border-gray-300 px-5 py-1 rounded">Voltar</a>
            </div>
            <p class="border border-gray-300 p-5 rounded">
                {{ $client->name }}<br />
                CPF: {{ $client->cpf }}<br />
                {{ $client->phone }}<br />
                {{ $client->email }}<br />
                {{ $client->address }}, {{ $client->number }},
                @if (isset($client->complement))
                    ({{ $client->complement }}),
                @endif
                {{ $client->district }}, {{ $client->city }}/{{ $client->state }}
            </p>
            <br />

            @foreach ($client->processes as $process)
                <p class="border border-gray-300 p-5 rounded">
                    Pasta: {{ $process->folder }}<br />
                    Título: {{ $process->title }}<br />
                    Juízo: {{ $process->juizo }}<br />
                    Vara: {{ $process->vara }}<br />
                    Foro: {{ $process->foro }}<br />
                    Prazo: {{ $process->days }} dias<br />
                    Valor da causa: {{ number_format($process->valor_causa,2,',','.') }}<br />
                </p>
                <br />
                <strong>Descrição:</strong>
                <p>{{ $process->description }}</p>
                <br/>
                <strong>Detalhes:</strong>
                <p>Detalhes: {{ $process->detail }}</p>
                <br/>
            @endforeach
                
            <h3 class="text-4xl text-blue-800">Documentos</h3>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="file" name="image" />
            </form>
            <br />
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-5">
                @foreach ($client->photos as $photo)
                    <div class="">
                        <img src="{{ asset('storage/' . $photo->image) }}" alt="image doc" class="w-full" />
                    </div>
                @endforeach
            </div>

        </div>

    </section>
    <!-- end -->

    @include('footer')

</body>

</html>
