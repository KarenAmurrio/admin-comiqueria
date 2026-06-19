<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Pedido;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalComics = Comic::count();
        $totalPedidos = Pedido::count();
        $ingresosTotales = Pedido::sum('total');
        
        $pedidosRecientes = Pedido::orderBy('created_at', 'desc')->take(5)->get();
        $comicsAgotados = Comic::where('stock', 0)->get();

        return view('admin_dashboard', compact(
            'totalComics', 
            'totalPedidos', 
            'ingresosTotales', 
            'pedidosRecientes', 
            'comicsAgotados'
        ));
    }
}
