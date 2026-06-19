@extends('layouts.admin')

@section('header_title', 'Resumen General')

@section('content')

{{-- ── KPI Cards ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

    {{-- Ingresos --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Ingresos Totales</p>
                <h3 class="text-2xl font-black text-gray-900">Bs. {{ number_format($ingresosTotales, 2) }}</h3>
            </div>
            <div class="p-2.5 bg-green-50 text-green-600 rounded-xl border border-green-100 shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Pedidos --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Pedidos Totales</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalPedidos }}</h3>
            </div>
            <div class="p-2.5 bg-red-50 text-red-600 rounded-xl border border-red-100 shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Comics --}}
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Cómics en Inventario</p>
                <h3 class="text-2xl font-black text-gray-900">{{ $totalComics }}</h3>
            </div>
            <div class="p-2.5 bg-gray-100 text-gray-600 rounded-xl border border-gray-200 shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- ── Main Grid ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Últimos Pedidos --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-50">
            <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">Últimos Pedidos</h3>
            <a href="{{ route('admin.pedidos') }}" class="text-xs text-red-600 font-bold hover:text-red-700 transition-colors flex items-center gap-1">
                Ver todos
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-400 text-[10px] uppercase tracking-widest font-bold border-b border-gray-50">
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Entrega</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-xs divide-y divide-gray-50">
                    @forelse($pedidosRecientes as $pedido)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $pedido->cliente }}</p>
                            <p class="text-gray-400 mt-0.5 truncate max-w-[140px]">{{ $pedido->correo }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $pedido->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $raw = $pedido->metodo_entrega;
                                if (str_starts_with($raw, 'Domicilio:')) { $tipo = 'Domicilio'; }
                                elseif (str_starts_with($raw, 'Terminal:')) { $tipo = 'Terminal'; }
                                else { $tipo = 'Retiro'; }
                            @endphp
                            @if($tipo == 'Domicilio')
                                <span class="bg-orange-50 text-orange-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-orange-100">📍 Domicilio</span>
                            @elseif($tipo == 'Terminal')
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-blue-100">🚌 Terminal</span>
                            @else
                                <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-gray-200">🏢 Local</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right font-black text-red-600">Bs. {{ number_format($pedido->total, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs">No hay pedidos recientes.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Alertas de Inventario --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">Stock Bajo / Agotado</h3>
        </div>

        <div class="p-4 space-y-3 max-h-80 overflow-y-auto">
            @forelse($comicsAgotados as $comic)
            <div class="flex items-center gap-3 p-3 bg-red-50/60 rounded-xl border border-red-100">
                <img src="{{ $comic->imagen_url }}" alt="{{ $comic->titulo }}"
                     class="w-9 h-12 object-cover rounded-lg shadow-sm bg-white border border-red-100 shrink-0">
                <div class="min-w-0">
                    <h4 class="font-bold text-xs text-gray-800 leading-tight truncate">{{ $comic->titulo }}</h4>
                    <span class="text-[9px] font-black text-red-600 bg-red-100 border border-red-200 px-1.5 py-0.5 rounded mt-1 inline-block uppercase">Agotado</span>
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <div class="w-10 h-10 bg-green-50 border border-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-gray-500">Todo en orden</p>
                <p class="text-[10px] text-gray-400 mt-0.5">Inventario completo</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Analytics ── --}}
<div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center gap-2">
        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">Analíticas Avanzadas</h3>
    </div>
    <div class="w-full overflow-hidden bg-gray-50 flex justify-center">
        <iframe
            class="w-full max-w-5xl"
            height="1200"
            src="https://datastudio.google.com/embed/reporting/a1b08928-1615-408f-b06b-918ad0617bab/page/kIV1C"
            frameborder="0"
            style="border:0"
            allowfullscreen
            sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
        </iframe>
    </div>
</div>

@endsection
