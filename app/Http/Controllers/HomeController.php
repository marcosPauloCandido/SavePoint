<?php

namespace App\Http\Controllers;

use App\Models\Jogo;

class HomeController extends Controller
{
    public function index()
    {
        $jogosAleatorios = Jogo::inRandomOrder()->limit(5)->get();

        return view('teste', compact('jogosAleatorios'));
    }
}
?>