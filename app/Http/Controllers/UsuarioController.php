<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Comentarios;
use App\Models\Curtida;
use App\Models\JogoUser;
use Illuminate\Support\Facades\Storage;

class UsuarioController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $jogosCurtidos = $user->curtidas;

        $comentarios = $user->comentarios()->with('jogo')->get();

        $jogosPorStatus = $user->jogosUsers()->with('jogo', 'status')->get()->groupBy('status.status');


        return view('usuarios.perfil', compact('user', 'jogosCurtidos', 'comentarios', 'jogosPorStatus'));
    }

      public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048', // max 2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Opcional: delete o avatar antigo se existir
            if ($user->avatar_url) {
                Storage::delete($user->avatar_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            // Atualiza o caminho no banco
            $user->avatar_url = $path;
            $user->save();
        }

        return back()->with('status', 'Foto de perfil atualizada com sucesso!');
    }
}