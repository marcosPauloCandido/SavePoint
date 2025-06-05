<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Resultados</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#0e0e0e] text-white min-h-screen p-8">
    <div class="w-full flex h-12 mb-8">
        <img src="/images/Logo.png" alt="Logo" class="z-10" />
        <header class="absolute top-4 right-4 z-20 text-sm">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                    @if (Auth::user()->tipo_usuario === 'admin')
                    <a
                        href="{{ route('jogos.create') }}"
                        class="inline-block px-5 py-1.5  border-[#3E3E3A] hover:border-[#62605b] border text-[#EDEDEC] rounded-sm text-sm leading-normal cursor-pointer"
                    >
                        Adicionar Jogos
                    </a>
                @endif
                        <form method="POST" action="{{ route('logout') }}">
                          @csrf
                            <button
                                 type="submit"
                                class="inline-block px-5 py-1.5  border-[#3E3E3A] hover:border-[#62605b] border text-[#EDEDEC] rounded-sm text-sm leading-normal cursor-pointer   ">          
                            {{ __('Logout') }}
                            </button>
                        </form>

                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#FFFFFF] border border-transparent hover:border-[#FFFFFF] dark:hover:border-[#FFFFFF] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#ffffff] hover:border-[#ffffff] border text-[#ffffff] dark:border-[#ffffff] dark:hover:border-[#ffffff] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
    </div>

    <div>

    <h1 class="text-2xl font-bold mb-4">Resultados para: <span class="text-yellow-400">{{ $termo }}</span></h1>

    @if ($jogos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
           @foreach ($jogos as $jogo)
    <a href="{{ url('jogos/' . $jogo->id_jogo) }}" ... class=" bg-[#1b1b18] rounded-lg shadow p-4 flex flex-col items-center hover:bg-yellow-500 transition">
        @if ($jogo->imagem_url)
            <img src="{{ $jogo->imagem_url }}" alt="{{ $jogo->titulo }}" class="w-full h-48 object-cover rounded">
        @else
            <div class="w-full h-48 flex items-center justify-center bg-gray-700 text-white rounded">
                Sem imagem
            </div>
        @endif
        <h3 class="mt-2 text-lg font-semibold">{{ $jogo->titulo }}</h3>
    </a>
@endforeach
        </div>
    @else
        <p class="text-center text-gray-400">Nenhum jogo encontrado.</p>
    @endif
    </div>

</body>
</html>
