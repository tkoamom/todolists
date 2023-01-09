<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $catalogs = Catalog::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('home', compact('catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        return view('add_catalog');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tasks' => 'required|string'
        ]);

        $catalog = new Catalog;
        $catalog->title = $request->input('title');
        $catalog->description = $request->input('description');

        $catalog->user_id = Auth::user()->id;

        $catalog->save();

        $tasks = explode('&and', $request->input('tasks'));
        foreach ($tasks as $task){
            $item = new Item;
            $item->title = $task;
            $item->catalog_id = $catalog->id;
            $item->save();
        }

        return back()->with('success', 'To do list created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $catalog = Catalog::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $items = Item::where('catalog_id', $id)->get();
        return view('show_catalog', compact('catalog', 'items'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $catalog = Catalog::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $items = Item::where('catalog_id', $id)->get();
        $items_string = '';
        foreach ($items as $item){
            $items_string .= $item->title . '&and';
        }
        $items_string = substr($items_string, 0, -4);
        return view('edit_catalog', compact('catalog', 'items_string'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tasks' => 'required|string'
        ]);

        $catalog = Catalog::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $catalog->title = $request->input('title');
        $catalog->description = $request->input('description');

        $catalog->save();

        $tasks = explode('&and', $request->input('tasks'));
        foreach ($tasks as $task){
            $item = Item::where('title', $task)->where('catalog_id', $id)->firstOrFail();
            $item->title = $task;
            $item->save();
        }

        return back()->with('success', 'To do list updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $catalog = Catalog::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $catalog->delete();
        return redirect()->route('catalog.index')->with('success', 'To do list deleted successfully');
    }
}
