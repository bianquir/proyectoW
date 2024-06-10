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
    public function index(): View 
    {
        return view('tags.index', [
            'tags'=> Tag::first()->paginate(5)
        ]);
    }

    public function create(): View
    {
        return view('tags.create');
    }
    
    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create($request->all());
        return redirect()->route('tag.index')->withSuccess('la etiqueta ha sido creada');
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
        return redirect()->back()
                ->withSuccess('tag is updated successfully.');
    }

    public function destroy(Tag $tag) : RedirectResponse
    {
        $tag->delete();
        return redirect()->route('tag.index')
                ->withSuccess('Tag is deleted successfully.');
    }


    
}
