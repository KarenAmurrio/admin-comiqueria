@extends('layouts.admin')

@section('header_title', 'Pedidos')

@section('content')

{{-- Flash message --}}
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center justify-between text-sm shadow-sm">
    <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="font-semibold">{{ session('success') }}</p>
    </div>
    <button type="button" class="text-green-500 hover:text-green-700 font-bold text-lg leading-none" onclick="this.parentElement.remove()">&times;</button>
</div>
@endif

{{-- ── TABS ── --}}
<div class="flex flex-wrap gap-2 mb-5">
    <button onclick="changeTab('all', this)"
            class="tab-btn tab-active flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-gray-900 text-white transition-all">
        <span>Todos</span>
        <span class="bg-white/15 px-1.5 py-0.5 rounded text-[10px]">{{ count($pedidos) }}</span>
    </button>
    <button onclick="changeTab('pendiente', this)"
            class="tab-btn flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-white text-gray-500 border border-gray-200 hover:border-yellow-300 hover:text-yellow-700 transition-all">
        <span>Pendientes de Pago</span>
        <span class="bg-yellow-100 text-yellow-700 px-1.5 py-0.5 rounded text-[10px]">{{ $pedidos->filter(fn($p) => ($p->estado_pago ?? 'pendiente') === 'pendiente')->count() }}</span>
    </button>
    <button onclick="changeTab('confirmado', this)"
            class="tab-btn flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-white text-gray-500 border border-gray-200 hover:border-green-300 hover:text-green-700 transition-all">
        <span>Confirmados</span>
        <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[10px]">{{ $pedidos->filter(fn($p) => in_array($p->estado_pago ?? 'completado', ['aprobado', 'completado']) && ($p->estado_envio ?? 'pendiente') === 'pendiente')->count() }}</span>
    </button>
    <button onclick="changeTab('completado', this)"
            class="tab-btn flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-white text-gray-500 border border-gray-200 hover:border-blue-300 hover:text-blue-700 transition-all">
        <span>Completados</span>
        <span class="bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded text-[10px]">{{ $pedidos->filter(fn($p) => ($p->estado_envio ?? 'pendiente') === 'completado')->count() }}</span>
    </button>
    <button onclick="changeTab('rechazado', this)"
            class="tab-btn flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg bg-white text-gray-500 border border-gray-200 hover:border-red-300 hover:text-red-700 transition-all">
        <span>Rechazados</span>
        <span class="bg-red-100 text-red-700 px-1.5 py-0.5 rounded text-[10px]">{{ $pedidos->filter(fn($p) => ($p->estado_pago ?? '') === 'rechazado')->count() }}</span>
    </button>
</div>

{{-- ── TABLE ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px]">
            <thead>
                <tr class="bg-gray-50 text-[10px] text-gray-400 uppercase tracking-widest font-bold border-b border-gray-100">
                    <th class="px-5 py-3">ID</th>
                    <th class="px-5 py-3">Cliente</th>
                    <th class="px-5 py-3">Entrega</th>
                    <th class="px-5 py-3">Artículos</th>
                    <th class="px-5 py-3">Pago</th>
                    <th class="px-5 py-3">Total</th>
                    <th class="px-5 py-3">Fecha</th>
                    <th class="px-5 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="pedidos-tbody" class="divide-y divide-gray-50 text-sm">
                @forelse($pedidos as $pedido)
                @php
                    $statusPago  = $pedido->estado_pago ?? (($pedido->metodo_pago ?? 'paypal') == 'qr' ? 'pendiente' : 'completado');
                    $statusEnvio = $pedido->estado_envio ?? 'pendiente';

                    $raw = $pedido->metodo_entrega;
                    if (str_starts_with($raw, 'Domicilio:')) {
                        $tipoEntrega    = 'Domicilio';
                        $detailEntrega  = trim(substr($raw, 10));
                    } elseif (str_starts_with($raw, 'Terminal:')) {
                        $tipoEntrega    = 'Terminal';
                        $detailEntrega  = trim(substr($raw, 9));
                    } else {
                        $tipoEntrega    = 'Retiro';
                        $detailEntrega  = '';
                    }
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors order-row"
                    data-status-pago="{{ $statusPago }}"
                    data-status-envio="{{ $statusEnvio }}">

                    {{-- ID --}}
                    <td class="px-5 py-4 font-mono text-xs text-gray-400 font-bold whitespace-nowrap">
                        #{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}
                    </td>

                    {{-- Cliente --}}
                    <td class="px-5 py-4">
                        <p class="font-bold text-gray-800 whitespace-nowrap">{{ $pedido->cliente }}</p>
                        <p class="text-gray-400 text-xs mt-0.5 max-w-[160px] truncate" title="{{ $pedido->correo }}">{{ $pedido->correo }}</p>
                        <p class="text-gray-400 text-xs mt-0.5">{{ $pedido->telefono }}</p>
                    </td>

                    {{-- Entrega --}}
                    <td class="px-5 py-4 min-w-[140px]">
                        @if($tipoEntrega == 'Domicilio')
                            <span class="bg-orange-50 text-orange-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-orange-100 inline-block">📍 Domicilio</span>
                        @elseif($tipoEntrega == 'Terminal')
                            <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-blue-100 inline-block">🚌 Terminal</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-gray-200 inline-block">🏢 Local</span>
                        @endif

                        @if($detailEntrega)
                            <p class="text-gray-400 text-[10px] mt-1 max-w-[130px] truncate" title="{{ $detailEntrega }}">{{ $detailEntrega }}</p>
                        @endif

                        {{-- Estado de envío --}}
                        <div class="mt-1.5">
                            @if($statusEnvio == 'completado')
                                <span class="bg-green-50 text-green-700 text-[9px] font-black px-2 py-0.5 rounded-full border border-green-100 uppercase">✓ Entregado</span>
                            @else
                                <span class="bg-yellow-50 text-yellow-700 text-[9px] font-black px-2 py-0.5 rounded-full border border-yellow-100 uppercase">⏳ Pendiente</span>
                            @endif
                        </div>
                    </td>

                    {{-- Artículos --}}
                    <td class="px-5 py-4 max-w-[180px]">
                        <p class="text-gray-600 text-xs truncate" title="{{ $pedido->detalles }}">{{ $pedido->detalles }}</p>
                    </td>

                    {{-- Pago --}}
                    <td class="px-5 py-4">
                        <div class="flex flex-wrap gap-1 mb-1.5">
                            @if(($pedido->metodo_pago ?? 'paypal') == 'qr')
                                <span class="bg-gray-100 text-gray-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-gray-200">📱 QR</span>
                            @else
                                <span class="bg-blue-50 text-blue-600 text-[9px] font-bold px-2 py-0.5 rounded-full border border-blue-100">💳 PayPal</span>
                            @endif

                            @if($statusPago == 'completado' || $statusPago == 'aprobado')
                                <span class="bg-green-50 text-green-700 text-[9px] font-bold px-2 py-0.5 rounded-full border border-green-100">✓ Aprobado</span>
                            @elseif($statusPago == 'pendiente')
                                <span class="bg-yellow-50 text-yellow-700 text-[9px] font-bold px-2 py-0.5 rounded-full border border-yellow-100">⏳ Pendiente</span>
                            @elseif($statusPago == 'rechazado')
                                <span class="bg-red-50 text-red-700 text-[9px] font-bold px-2 py-0.5 rounded-full border border-red-100">✗ Rechazado</span>
                            @endif
                        </div>

                        @if($pedido->comprobante_pago)
                            <button onclick="verComprobante('{{ $pedido->comprobante_pago }}')"
                                    class="text-[10px] text-red-600 font-bold hover:text-red-700 hover:underline transition-colors">
                                👁 Ver Recibo
                            </button>
                        @endif
                    </td>

                    {{-- Total --}}
                    <td class="px-5 py-4 font-black text-red-600 whitespace-nowrap">
                        Bs. {{ number_format($pedido->total, 2) }}
                    </td>

                    {{-- Fecha --}}
                    <td class="px-5 py-4 text-gray-400 text-xs whitespace-nowrap">
                        {{ $pedido->created_at->format('d/m/Y') }}<br>
                        <span class="text-gray-300">{{ $pedido->created_at->format('H:i') }}</span>
                    </td>

                    {{-- Acciones --}}
                    <td class="px-5 py-4 text-right">
                        @if(($pedido->metodo_pago ?? 'paypal') == 'qr' && $statusPago == 'pendiente')
                            <div class="inline-flex gap-2">
                                <form action="{{ route('admin.pedidos.actualizar_estado', $pedido->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="estado_pago" value="aprobado">
                                    <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                        Aprobar
                                    </button>
                                </form>
                                <form action="{{ route('admin.pedidos.actualizar_estado', $pedido->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="estado_pago" value="rechazado">
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                        Rechazar
                                    </button>
                                </form>
                            </div>

                        @elseif(($statusPago == 'aprobado' || $statusPago == 'completado') && $statusEnvio == 'pendiente')
                            <form action="{{ route('admin.pedidos.actualizar_envio', $pedido->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="estado_envio" value="completado">
                                <button type="submit"
                                        class="bg-gray-900 hover:bg-gray-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap">
                                    🚚 Entregado
                                </button>
                            </form>

                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-16 text-center">
                        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gray-100 mb-4">
                            <span class="text-2xl">📦</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700 mb-1">Sin pedidos aún</h3>
                        <p class="text-xs text-gray-400">Cuando lleguen pedidos aparecerán aquí.</p>
                    </td>
                </tr>
                @endforelse

                {{-- Estado vacío para filtro --}}
                <tr id="empty-state-row" class="hidden">
                    <td colspan="8" class="py-16 text-center bg-gray-50/30">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                            <span class="text-xl">🔍</span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-700 mb-0.5">Sin pedidos en esta categoría</h3>
                        <p class="text-xs text-gray-400">No hay pedidos que coincidan con el filtro seleccionado.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL COMPROBANTE ── --}}
<div id="comprobanteModal"
     class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm"
     style="display:none">
    <div class="bg-white rounded-2xl max-w-lg w-full overflow-hidden shadow-2xl relative border border-gray-100 flex flex-col max-h-[90vh]">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-gray-800 text-sm">Comprobante de Pago</h3>
            <button onclick="cerrarModal()"
                    class="text-gray-400 hover:text-gray-600 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors text-lg font-bold">
                &times;
            </button>
        </div>
        <div class="p-6 overflow-y-auto bg-gray-50 flex justify-center items-center grow min-h-[280px]">
            <img id="comprobanteImg" src="" alt="Comprobante de Pago"
                 class="max-w-full max-h-[65vh] object-contain rounded-xl shadow-md border border-gray-200">
        </div>
        <div class="px-5 py-4 border-t border-gray-100 flex justify-end bg-white">
            <a id="descargarComprobanteBtn" href="" download target="_blank"
               class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-colors flex items-center gap-2">
                📥 Descargar Imagen
            </a>
        </div>
    </div>
</div>

<script>
    /* ── Comprobante modal ── */
    function verComprobante(url) {
        document.getElementById('comprobanteImg').src = url;
        document.getElementById('descargarComprobanteBtn').href = url;
        const modal = document.getElementById('comprobanteModal');
        modal.style.display = 'flex';
    }
    function cerrarModal() {
        document.getElementById('comprobanteModal').style.display = 'none';
    }
    document.getElementById('comprobanteModal').addEventListener('click', function(e) {
        if (e.target === this) cerrarModal();
    });

    /* ── Tab filtering ── */
    function changeTab(status, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('bg-gray-900', 'text-white', 'tab-active');
            b.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200');
        });
        btn.classList.add('bg-gray-900', 'text-white', 'tab-active');
        btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200');

        const rows = document.querySelectorAll('.order-row');
        let visible = 0;

        rows.forEach(row => {
            const pago  = row.getAttribute('data-status-pago');
            const envio = row.getAttribute('data-status-envio');
            let show = false;

            if (status === 'all')          show = true;
            else if (status === 'pendiente')   show = (pago === 'pendiente');
            else if (status === 'confirmado')  show = ((pago === 'aprobado' || pago === 'completado') && envio === 'pendiente');
            else if (status === 'completado')  show = (envio === 'completado');
            else if (status === 'rechazado')   show = (pago === 'rechazado');

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        document.getElementById('empty-state-row').classList.toggle('hidden', visible > 0);
    }
</script>
@endsection