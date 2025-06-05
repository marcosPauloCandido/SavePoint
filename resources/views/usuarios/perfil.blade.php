<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Meu Perfil</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen p-8 font-sans">

    <a href="{{ url('/') }}" class="inline-block text-yellow-400 hover:underline mb-8">&larr; Voltar</a>

    <div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg p-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <img 
                src="{{ asset('storage/' . $user->avatar_url) }}" 
                alt="Avatar de {{ $user->name }}" 
                class="w-40 h-40 rounded-full border-4 border-yellow-400 object-cover shadow-lg"
            />

            <div class="flex-1">
                <h1 class="text-3xl font-extrabold mb-2">{{ $user->name }}</h1>
                <p class="text-gray-300 mb-4">
                    <strong>Email:</strong> {{ $user->email }}
                </p>
                @if(!empty($user->bio))
                    <p class="text-gray-300 italic">"{{ $user->bio }}"</p>
                @endif
               <form action="{{ route('perfil.uploadAvatar') }}" method="POST" enctype="multipart/form-data" class="mt-6">
    @csrf
    <label class="block text-gray-300 mb-2" for="avatar">Atualizar foto de perfil</label>

    <input 
        type="file" 
        name="avatar" 
        id="avatar" 
        accept="image/*" 
        required 
        class="hidden"
        onchange="document.getElementById('file-name').textContent = this.files[0]?.name || ''"
    />

    <label 
        for="avatar" 
        class="inline-block cursor-pointer bg-yellow-400 text-gray-900 py-2 px-4 rounded hover:bg-yellow-500 transition select-none"
        >
        Escolher arquivo
    </label>

    <span id="file-name" class="ml-3 text-gray-300 italic"></span>

    @error('avatar')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror

    <div class="mt-4">
        <button type="submit" class="bg-yellow-400 text-gray-900 py-2 px-4 rounded hover:bg-yellow-500 transition">
            Upload
        </button>
    </div>
</form>


            </div>
        </div>
    </div>

    {{-- Jogos Curtidos --}}
    <section class="max-w-4xl mx-auto mt-12 bg-gray-800 rounded-lg p-6 shadow-md">
        <h2 class="text-2xl font-bold mb-4 border-b border-yellow-400 pb-2">🎮 Jogos Curtidos</h2>
        @if($jogosCurtidos->isEmpty())
            <p class="text-gray-400">Você ainda não curtiu nenhum jogo.</p>
        @else
            <ul class="list-disc list-inside space-y-2 text-yellow-300">
                @foreach($jogosCurtidos as $jogo)
                    <li>
                        <a href="{{ route('jogos.show', $jogo->id_jogo) }}" 
                           class="hover:underline hover:text-yellow-500 transition">
                           {{ $jogo->titulo }}
                           <img src="{{ $jogo->imagem_url }}" alt="{{ $jogo->titulo }}" class="inline-block w-16 h-8 rounded ml-2">
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Comentários --}}
    <section class="max-w-4xl mx-auto mt-10 bg-gray-800 rounded-lg p-6 shadow-md">
        <h2 class="text-2xl font-bold mb-4 border-b border-yellow-400 pb-2">💬 Comentários</h2>
        @if($comentarios->isEmpty())
            <p class="text-gray-400">Você ainda não fez nenhum comentário.</p>
        @else
            <ul class="space-y-4">
                @foreach($comentarios as $comentario)
                    <li class="bg-gray-700 p-4 rounded-md shadow-inner">
                        <p class="mb-2">{{ $comentario->texto }}</p>
                        <p class="text-sm text-yellow-400 font-semibold">
                            No jogo: 
                            <a href="{{ route('jogos.show', $comentario->jogo->id_jogo) }}" class="underline hover:text-yellow-300">
                                {{ $comentario->jogo->titulo }}
                            </a>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">{{ $comentario->adicionado_em }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{-- Jogos por Status --}}
    <section class="max-w-4xl mx-auto mt-10 bg-gray-800 rounded-lg p-6 shadow-md">
        <h2 class="text-2xl font-bold mb-6 border-b border-yellow-400 pb-2">🎲 Meus Jogos</h2>

        @php
            $statusList = ['Jogando', 'Quero Jogar', 'Completado', 'Abandonado'];
        @endphp

        @foreach($statusList as $statusNome)
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-3 text-yellow-400">{{ $statusNome }}</h3>
                @if(isset($jogosPorStatus[$statusNome]) && $jogosPorStatus[$statusNome]->isNotEmpty())
                    <ul class="list-disc list-inside space-y-2 text-yellow-300">
                        @foreach($jogosPorStatus[$statusNome] as $registro)
                            <li>
                                <a href="{{ route('jogos.show', $registro->jogo->id_jogo) }}" 
                                   class="hover:underline hover:text-yellow-500 transition">
                                   {{ $registro->jogo->titulo }}
                                   <img src="{{ $registro->jogo->imagem_url }}" alt="{{ $registro->jogo->titulo }}" class="inline-block w-16 h-8 rounded ml-2">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-400 italic">Nenhum jogo nessa categoria.</p>
                @endif
            </div>
        @endforeach
    </section>

</body>
</html>
