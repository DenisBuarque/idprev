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

        <div class="flex flex-col mb-5 md:flex-row md:justify-between">
            <div class="flex-1">
                <h1 class="text-4xl text-blue-900"><a href="{{ route('site.franchisee') }}">Área do Franqueado</a></h1>
            </div>
            <div class="flex-1">
                <form method="GET" action="{{ route('site.franchisee') }}" class="flex w-full">
                    @csrf
                    <div class="flex flex-1 flex-row mr-1 border border-gray-400 rounded items-center justify-center">
                        <div class="p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                        <input type="search" name="search" value="{{ $search }}"
                            class="w-full border-none bg-white p-2 focus:outline-none" placeholder="Nome do cliente" required />
                    </div>
                    <button type="submit" class="flex-none bg-blue-800 text-white px-5 py-2 rounded">Buscar</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
            @foreach ($clients as $client)
                <div class="border border-gray-500 hover:bg-gray-200 p-6 rounded-md shadow-md md:mb-0">
                    <h3 class="text-2xl mb-3 text-blue-600">{{ $client->name }}</h3>
                    <p>Entrada: 00/00/0000 00:00:00</p>
                    <p class="mb-3">Prazo: 45 dias</p>

                    <p class="mb-5 text-gray-600 text-base">Lorem ipsum dolor, sit amet consectetur adipisicing
                        elit. Minus tempore consectetur quasi eum. Lorem ipsum dolor sit amet consectetur
                        adipisicing elit. Nisi consectetur omnis est magni harum quia.</p>
                    <div class="flex justify-between">
                        <div>
                            <a href="{{ route('franchisee.client', ['id' => $client->id]) }}"
                                class="rounded hover:underline">+ Detalhes</a>
                        </div>
                        <div class="flex items-center justify-center">
                            <img src="{{ asset('images/icons/like.png') }}" alt="trash" class="w-5 h-5"
                                title="Cliente atendido" />
                            <a href="{{ route('franchisee.client', ['id' => $client->id]) }}" class="flex ml-3">
                                <img src="{{ asset('images/icons/anexo.png') }}" alt="trash" class="w-6 h-6"
                                    title="Anexar documentos" />
                                <small class="pt-2">{{ count($client->photos) }}</small>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </section>
    <!-- end -->

    @include('footer')

</body>

</html>
