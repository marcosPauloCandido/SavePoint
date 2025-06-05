<?php

namespace App\Http\Controllers;

use App\Models\Comentarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentariosController extends Controller {
    public function store(Request $request, $idJogo) {
        $request->validate([
            'texto' => 'required|string|max:500',
        ]);
        Comentarios::create([
            'idUsuario' => Auth::id(),
            'idJogo' => $idJogo,
            'texto' => $request->texto,
        ]);
        return redirect()->back()->with('success', 'Comentário adicionado com sucesso!');
    }

}
