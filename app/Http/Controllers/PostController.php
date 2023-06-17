<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Ingreso;
use App\Models\Carpetas;
use App\Models\Maeemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carpeta = Ingreso::whereDate('IngFecEgr', '1753-01-01')
            ->whereYear('IngFecAdm', '2022')
            ->orderBy("IngFecAdm", 'DESC')
            ->paginate(10);

        $contratos = Maeemp::where('MEestado', 0)
            ->where('MEEmpcod', 1)
            ->orderBy("MENOMB", 'asc')
            ->get();

        $carpetas = $carpeta->unique("MPCedu");
        return view('facturacion.carpetas.index', compact('carpetas', 'contratos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('post_create'), 403);

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Post::create($request->all());

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), 403);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), 403);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), 403);

        $post->delete();

        return redirect()->route('posts.index');
    }
}
