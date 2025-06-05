<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>{{ $jogo->titulo }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-8 font-sans">
    @if(session('success'))
    <div class="max-w-5xl mx-auto bg-green-600 text-white p-4 rounded mb-6">
        {{ session('success') }}
    </div>
@endif

    <a href="{{ url('/') }}" class="inline-block text-yellow-400 hover:underline mb-6">&larr; Voltar</a>

    <main class="max-w-5xl mx-auto bg-gray-800 rounded-lg shadow-lg p-8">
        <h1 class="text-4xl font-extrabold mb-6">{{ $jogo->titulo }}</h1>

        <div class="flex flex-col md:flex-row md:items-start gap-8">
            {{-- Imagem do jogo --}}
            @if ($jogo->imagem_url)
                <img src="{{ $jogo->imagem_url }}" alt="{{ $jogo->titulo }}" class="rounded-lg shadow-md w-full max-w-md object-cover" />
            @else
                <div class="w-full max-w-md h-48 bg-gray-700 flex items-center justify-center rounded-lg text-gray-400 font-semibold">
                    Sem imagem
                </div>
            @endif

            {{-- Form status + info --}}
            <div class="flex-1 flex flex-col justify-between">
                {{-- Form status --}}
                @if(auth()->check())
                    <form action="{{ route('jogos.atualizar-status', $jogo->id_jogo) }}" method="POST" class="mb-6">
                        @csrf
                        <label for="status" class="block mb-2 font-semibold">Status do jogo:</label>
                        <select name="idStatus" id="status" class="w-full p-3 rounded bg-gray-100 text-black shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                            @foreach($statusDisponiveis as $status)
                                <option value="{{ $status->idStatus }}"
                                    @if($statusUsuario && $statusUsuario->idStatus == $status->idStatus) selected @endif>
                                    {{ $status->status }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded transition">
                            Salvar
                        </button>
                    </form>
                @endif

                {{-- Informações do jogo --}}
                <div>
                    <p class="text-gray-300 mb-4 whitespace-pre-line">{{ $jogo->descricao }}</p>

                    <p class="text-sm text-gray-400 mb-1"><strong>Lançamento:</strong> {{ $jogo->data_lancamento }}</p>
                    <p class="text-sm text-gray-400 mb-1"><strong>Desenvolvedora:</strong> {{ $jogo->desenvolvedora }}</p>
                    <p class="text-sm text-gray-400 mb-1"><strong>Distribuidora:</strong> {{ $jogo->distribuidora }}</p>

                    {{-- Curtir + plataformas --}}
                    <div class="mt-6 flex flex-wrap items-center gap-6">
                        <form action="{{ route('jogo.curtir', $jogo->id_jogo) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                class="px-5 py-2 rounded bg-yellow-500 hover:bg-yellow-600 text-black font-semibold transition">
                                @if ($jogo->curtidas->contains('idUsuario', auth()->id()))
                                    💔 Descurtir
                                @else
                                    ❤️ Curtir
                                @endif
                            </button>
                        </form>

                        <div>
                            <h3 class="font-semibold text-yellow-400 mb-1">Plataformas:</h3>
                            <ul class="flex flex-wrap gap-4 list-none p-0 m-0">
                                @foreach ($jogo->plataformas as $plataforma)
                                    <li class="flex items-center gap-2 bg-gray-700 rounded px-3 py-1 shadow-inner">
                                        <img src="/images/{{ $plataforma->nome ?? $plataforma->plataforma }}.png"
                                             alt="{{ $plataforma->nome ?? $plataforma->plataforma }}"
                                             width="24" height="24" />
                                        <span>{{ $plataforma->nome ?? $plataforma->plataforma }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comentários --}}
        <section class="mt-12">
            <form action="{{ route('comentarios.store', $jogo->id_jogo) }}" method="POST">
                @csrf
                <textarea name="texto" rows="4" 
                    class="w-full p-3 rounded bg-gray-700 text-white resize-y focus:outline-none focus:ring-2 focus:ring-yellow-400" 
                    placeholder="Deixe um comentário..."></textarea>
                <button type="submit" 
                    class="mt-3 px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded transition">
                    Enviar
                </button>
            </form>

            <div class="mt-10">
                <h3 class="text-2xl font-bold mb-6 border-b border-yellow-400 pb-2">Comentários</h3>

                @forelse ($jogo->comentarios as $comentario)
                    <div class="mb-6 p-4 bg-gray-700 rounded-lg shadow-inner">
                        <p class="text-sm text-yellow-400 font-semibold mb-1">{{ $comentario->usuario->name }} disse:</p>
                        <p class="mb-2">{{ $comentario->texto }}</p>
                        <p class="text-xs text-gray-400">{{ $comentario->adicionado_em }}</p>
                    </div>
                @empty
                    <p class="text-gray-400 italic">Nenhum comentário ainda.</p>
                @endforelse
            </div>
        </section>
    </main>

</body>
</html>
