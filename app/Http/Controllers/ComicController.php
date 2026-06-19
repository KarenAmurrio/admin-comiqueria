<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

class ComicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comics = Comic::all(); // Trae todos los cómics de la BD
        return view('admin_comics', compact('comics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin_comics_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Comic::create($request->all());
        return redirect()->route('admin.comics.index')->with('success', 'Cómic creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comic $comic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $comic = Comic::findOrFail($id);
        return view('admin_comics_edit', compact('comic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $comic = Comic::findOrFail($id);
        $comic->update($request->all());
        return redirect()->route('admin.comics.index')->with('success', 'Cómic actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comic $comic)
    {
        Comic::destroy($comic->id);
        return redirect()->back();
    }
}
