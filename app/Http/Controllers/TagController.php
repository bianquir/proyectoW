<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(Request $request): View
{
    $selectedTagId = $request->input('filter_tag');
    
    // Consulta para obtener todas las etiquetas disponibles para el filtro
    $tagsForFilter = Tag::all();
    
    // Si hay un filtro aplicado, filtra los resultados
    if ($selectedTagId) {
        $tags = Tag::where('id', $selectedTagId)->paginate(5);
    } else {
        // Si no hay filtro, muestra todos los resultados
        $tags = Tag::paginate(5);
    }

    return view('tags.index', [
        'tags' => $tags,
        'tagsForFilter' => $tagsForFilter
    ]);
}
    

    public function create(): View
    {
        return view('tags.create');
    }
    
    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create($request->all());
        return redirect()->route('tag.index')->withSuccess('Etiqueta creada con éxito');
    }

    public function edit(Tag $tag): View
    {
        return view('tags.edit', [
            'tag' => $tag
        ]);
    }
    public function update(UpdateTagRequest $request, Tag $tag) : RedirectResponse
    {
        $tag->update($request->all());
        return redirect()->route('tag.index')
                ->withSuccess('Etiqueta editada con éxito');
    }

    public function destroy(Tag $tag) : RedirectResponse
    {
        $tag->delete();
        return redirect()->route('tag.index')
                ->withSuccess('Etiqueta eliminada con éxito');
    }


    
}
