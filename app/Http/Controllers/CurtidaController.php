<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\Curtida;
use Illuminate\Support\Facades\Auth;

class CurtidaController extends Controller {
    public function curtir($idJogo) {
        $usuarioID = Auth::id();

        $curtida = Curtida::where('idUsuario', $usuarioID)
        ->where('idJogo', $idJogo)
        ->first();

        if ($curtida) {
            $curtida->delete();
            $mensagem = 'Curtida removida com sucesso!';
        } else {
            Curtida::create([
                'idUsuario' => $usuarioID,
                'idJogo' => $idJogo
            ]);
            $mensagem = 'Curtida adicionada com sucesso!';
        }
        return redirect()->back()->with('mensagem', $mensagem);
    }
}