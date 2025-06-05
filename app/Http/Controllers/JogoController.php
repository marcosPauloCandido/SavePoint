<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jogo;
use App\Models\Plataforma;
use App\Models\JogoUser;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;

class JogoController extends Controller
{
   public function create() {
    $plataformas = Plataforma::all();  // busca todas as plataformas

    $jogos = Jogo::all();
    return view('jogos.create', compact('plataformas', 'jogos'));
}

    public function store(Request $request) {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_lancamento' => 'nullable|date',
            'imagem_url' => 'nullable|string|max:255',
            'desenvolvedora' => 'nullable|string|max:255',
            'distribuidora' => 'nullable|string|max:255',
        ]);

        $jogo = Jogo::create([
        'titulo' => $validated['titulo'],
        'descricao' => $validated['descricao'] ?? null,
        'data_lancamento' => $validated['data_lancamento'] ?? null,
        'imagem_url' => $validated['imagem_url'] ?? null,
        'desenvolvedora' => $validated['desenvolvedora'] ?? null,
        'distribuidora' => $validated['distribuidora'] ?? null,

        ]);
        
         $plataformasIds = json_decode($request->input('plataformas', '[]'));

    $jogo->plataformas()->sync($plataformasIds);

    return redirect()->route('jogos.create')->with('success', 'Jogo adicionado com sucesso!');
    }
    
    public function buscarJogos(Request $request) {
    $termo = $request->input('termo');

    $jogos = Jogo::where('titulo', 'like', '%' . $termo . '%')
        ->orWhere('descricao', 'like', '%' . $termo . '%')
        ->get();

    return response()->json($jogos);
}
    public function mostrarTodosResultados(Request $request) {
        $termo = $request->query('termo', '');

        $jogos = Jogo::where('titulo', 'like', '%' . $termo . '%')->get();


        return view('jogos.resultados', compact ('jogos', 'termo'));
    }

    public function show($id)
    {
        $jogo = Jogo::with(['comentarios.usuario', 'curtidas', 'plataformas'])->findOrFail($id);

        $statusUsuario = null;

if (Auth::check()) {
    $statusUsuario = \App\Models\JogoUser::where('idUsers', Auth::id())
                                          ->where('idJogo', $id)
                                          ->first();
}

$statusDisponiveis = \App\Models\Status::all();


        return view('jogos.jogo', compact('jogo', 'statusUsuario', 'statusDisponiveis'));
    }
    public function atualizarStatus(Request $request, $id)
{
    $request->validate([
        'idStatus' => 'required|exists:status,idStatus'
    ]);

    $userId = Auth::id();

    // Verifica se já existe registro para esse jogo e usuário
    $registro = JogoUser::where('idUsers', $userId)
                         ->where('idJogo', $id)
                         ->first();

    if ($registro) {
        // Atualiza o status
        $registro->idStatus = $request->idStatus;
        $registro->save();
    } else {
        // Cria o novo vínculo
        JogoUser::create([
            'idUsers' => $userId,
            'idJogo' => $id,
            'idStatus' => $request->idStatus
        ]);
    }

    return redirect()->back()->with('success', 'Status atualizado com sucesso!');
}
}
