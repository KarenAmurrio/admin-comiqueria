@extends('layouts.admin')

@section('header_title', 'Agregar Nuevo Cómic')

@section('content')
<div class="max-w-4xl bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
        <h2 class="text-2xl font-black text-gray-800">Detalles del Cómic</h2>
        <a href="{{ route('admin.comics.index') }}" class="text-sm text-gray-500 hover:text-red-600 font-bold transition-colors">← Cancelar y Volver</a>
    </div>

    <form action="{{ route('comics.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Título</label>
                <input type="text" name="titulo" placeholder="Ej. Batman: Año Uno" required class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
            </div>
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Categoría (Legado)</label>
                <select name="categoria" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none bg-white">
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <option value="Marvel">Marvel</option>
                    <option value="DC">DC Comics</option>
                    <option value="Manga">Manga</option>
                    <option value="Independiente">Independiente</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label>
            <textarea name="descripcion" placeholder="Sinopsis del cómic..." rows="4" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none resize-none"></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Precio (Bs.)</label>
                <input type="number" step="0.01" name="precio" placeholder="0.00" required class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Stock Inicial</label>
                <input type="number" name="stock" placeholder="0" required class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Formato</label>
                <select name="formato" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none bg-white">
                    <option value="todos" selected>Todos los formatos</option>
                    <option value="comics">Cómics</option>
                    <option value="mangas">Mangas</option>
                    <option value="novelas">Novelas Visuales</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">URL de la Imagen</label>
            <input type="text" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-red-500 outline-none">
        </div>

        <div class="border-t border-gray-100 pt-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Filtros Avanzados</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Editoriales -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-bold text-gray-800 mb-3">Editoriales</p>
                    <div class="space-y-3">
                        @foreach(['Marvel Comics', 'DC Comics', 'Image Comics', 'Panini Manga', 'Norma Editorial', 'Ivrea', 'Independiente'] as $ed)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="editorial[]" value="{{ $ed }}" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium transition-colors">{{ $ed }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Géneros -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-bold text-gray-800 mb-3">Géneros</p>
                    <div class="space-y-3">
                        @foreach(['Acción', 'Aventura', 'Ciencia Ficción', 'Fantasía', 'Terror / Psicológico', 'Romance', 'Recuentos de la vida (Slice of Life)', 'Misterio / Suspense', 'Isekai'] as $gen)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="genero[]" value="{{ $gen }}" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium transition-colors">{{ $gen }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Demografía -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-bold text-gray-800 mb-3">Demografía (Solo Mangas)</p>
                    <div class="space-y-3">
                        @foreach(['Shounen', 'Seinen', 'Shoujo', 'Josei'] as $demo)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="demografia[]" value="{{ $demo }}" class="w-5 h-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium transition-colors">{{ $demo }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-6 flex justify-end">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-red-500/30 transition-all hover:-translate-y-0.5">
                Guardar Cómic
            </button>
        </div>
    </form>
</div>
@endsection
