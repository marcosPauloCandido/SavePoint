<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Jogo</title>
</head>
<body>
    <h2>Editar Jogo</h2>
    <form method="POST" action="{{ route('jogos.update', $jogo->idJogo) }}">
        @csrf
        @method('PUT')
        <input type="text" name="titulo" value="{{ $jogo->titulo }}" required>
        <input type="text" name="imagem_url" value="{{ $jogo->imagem_url }}">
        <input type="text" name="desenvolvedora" value="{{ $jogo->desenvolvedora }}">
        <input type="text" name="distribuidora" value="{{ $jogo->distribuidora }}">
        <input type="date" name="data_lancamento" value="{{ $jogo->data_lancamento }}">
        <textarea name="descricao">{{ $jogo->descricao }}</textarea>
        <button type="submit">Salvar Alterações</button>
    </form>
    <a href="/">Voltar</a>
</body>
</html>
