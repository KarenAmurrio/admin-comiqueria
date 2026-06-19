@extends('layouts.admin')

@section('header_title', 'Inventario de Cómics')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-black text-gray-800">Catálogo Actual</h2>
    <a href="{{ route('admin.comics.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-red-500/20 transition-all hover:-translate-y-0.5 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nuevo Cómic
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm uppercase tracking-wider">
                    <th class="p-4 font-bold border-b border-gray-100">Portada</th>
                    <th class="p-4 font-bold border-b border-gray-100">Título & Info</th>
                    <th class="p-4 font-bold border-b border-gray-100">Precio</th>
                    <th class="p-4 font-bold border-b border-gray-100">Stock</th>
                    <th class="p-4 font-bold border-b border-gray-100 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($comics as $comic)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="p-4">
                        <img src="{{ $comic->imagen_url }}" alt="{{ $comic->titulo }}" class="w-12 h-16 object-cover rounded-lg shadow-sm bg-white border border-gray-200">
                    </td>
                    <td class="p-4">
                        <p class="font-black text-gray-800 text-lg">{{ $comic->titulo }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="bg-gray-200 px-2 py-0.5 rounded text-gray-700 font-semibold uppercase">{{ $comic->formato ?? 'todos' }}</span>
                            <span class="ml-1">{{ $comic->categoria ?? 'Sin Categoría' }}</span>
                        </p>
                    </td>
                    <td class="p-4 font-bold text-gray-800">
                        Bs. {{ number_format($comic->precio, 2) }}
                    </td>
                    <td class="p-4">
                        @if($comic->stock > 5)
                            <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200">{{ $comic->stock }} Disp.</span>
                        @elseif($comic->stock > 0)
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200">¡Solo {{ $comic->stock }}!</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs font-bold px-3 py-1 rounded-full border border-red-200">AGOTADO</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('comics.edit', $comic->id) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            
                            <form action="{{ route('comics.destroy', $comic->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este cómic?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors" title="Borrar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        No tienes cómics en tu inventario. ¡Agrega el primero!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection