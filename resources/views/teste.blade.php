<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Página Principal</title>
    <link href="css/app.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        input[type="text"] {
            padding: 8px;
            width: 40%;
            background-color: white;
            color: #0e0e0e;
        }
        #resultados {
            margin-top: 20px;
        }
        .jogo-item {
            margin-bottom: 10px;
            background: rgba(255, 255, 255, 0.05);
            padding: 10px;
            border-radius: 10px;
        }
        .jogo-item:hover {
            background: rgba(250, 204, 21, 1);
        }
    </style>
</head>
<body class="bg-[#0e0e0e] justify-center min-h-screen">
    <div class="w-full relative" style="height: 35vh;">
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

                        <form method="GET" action="{{ route('perfil') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex px-5 py-1.5 justify-center align-center  border-[#3E3E3A] hover:border-[#62605b] border text-[#EDEDEC] rounded-sm text-sm leading-normal cursor-pointer"
                            >
                                <img src="{{ Auth::user()->avatar_url ? asset('storage/' . Auth::user()->avatar_url) : asset('images/avatar-default.png') }}" alt="Avatar" class="w-6 h-6 rounded-full">
                                {{ Auth::user()->name }}

                            </button>

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
        <img src="/images/background.jpg" alt="Fundo" class="w-full h-full object-fill opacity-70" />
        <img src="/images/Logo.png" alt="Logo" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10" />
          <div class="absolute bottom-0 left-0 w-full h-20 bg-gradient-to-t from-[#0e0e0e] to-transparent"></div>
    </div>
    <div class="w-full align-center flex items-center justify-center mt-5 ">

    <form onsubmit="event.preventDefault(); pesquisarJogos(); return false;" class="flex items-center w-full justify-center">
        <input type="text" id="pesquisa" placeholder="Pesquisar jogos..." onkeypress="return redirecionarResultados(event);" oninput="pesquisarJogos()" class="p-2 border-white h-full rounded-l-full bg-white">

      <button id="botaoPesquisa" type="button" class="cursor-pointer bg-white rounded-r-full h-full border-white border p-2">
         <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 3a7.5 7.5 0 006.15 13.65z" />
  </svg>

      </button>
         </form>
    </div>

    <div id="resultados" style="color: white" class="flex justify-center align-center"></div>

    <div class="w-full flex justify-center mt-10 gap-4 flex-wrap">
    @forelse ($jogosAleatorios as $jogo)
        <a href="{{ url('/jogos/' . $jogo->id_jogo) }}" class="jogo-item max-w-xs cursor-pointer hover:bg-yellow-500 transition rounded-2xl p-3">
            <img src="{{ $jogo->imagem_url ?: '/images/default-game.png' }}" alt="{{ $jogo->titulo }}" class="w-full h-24 object-cover rounded-2xl mb-2">
            <h3 class="text-white text-center font-semibold">{{ $jogo->titulo }}</h3>
        </a>
    @empty
        <p class="text-white">Nenhum jogo disponível para mostrar.</p>
    @endforelse
</div>


</body>
<bottom>
      <script>
    let debounceTimer;

    function pesquisarJogos() {
        const termo = document.getElementById('pesquisa').value.trim();
        const resultadosDiv = document.getElementById('resultados');

        if (termo === '') {
            // Limpa resultados imediatamente quando o campo está vazio
            resultadosDiv.innerHTML = '';
            if (debounceTimer) clearTimeout(debounceTimer);
            return;
        }

        // Se o termo não estiver vazio, faz a busca com debounce
        if (debounceTimer) clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            fetch(`/buscar-jogos?termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(data => {
                    resultadosDiv.innerHTML = '';

                    const jogosLimitados = data.slice(0, 5);

                    if (jogosLimitados.length > 0) {
                        jogosLimitados.forEach(jogo => {
                            const div = document.createElement('div');
                            div.classList.add('jogo-item');
                            div.innerHTML = `
                                <a href="/jogos/${jogo.id_jogo}">
                                    <div class="flex flex-col mr-1 hover:bg-yellow-500 transition justify-center align-center">
                                        <img src="${jogo.imagem_url || '#'}" alt="${jogo.titulo}" class="w-full h-24 object-cover rounded-2xl" />
                                        <h3>${jogo.titulo}</h3>
                                    </div>
                                </a>
                            `;
                            resultadosDiv.appendChild(div);
                        });
                    } else {
                        resultadosDiv.innerHTML = '<p>Nenhum jogo encontrado.</p>';
                    }
                })
                .catch(error => console.error('Erro:', error));
        }, 500);
    }

    document.getElementById('botaoPesquisa').addEventListener('click', () => {
        const termo = document.getElementById('pesquisa').value.trim();

        if (termo !== '') {
            window.location.href = `/todos-os-resultados?termo=${encodeURIComponent(termo)}`;
        }
    });

    function redirecionarResultados(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Impede o submit de outros forms
            const termo = document.getElementById('pesquisa').value.trim();
            if (termo !== '') {
                window.location.href = `/todos-os-resultados?termo=${encodeURIComponent(termo)}`;
            }
            return false;
        }
    }
</script>



    </script>
</bottom>
</html