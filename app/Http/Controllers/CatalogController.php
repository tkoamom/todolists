<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $shared_ids = DB::table('shared_catalog')->where('user_id', Auth::user()->id)->get('catalog_id')->toArray();
        $id_arr = [];
        foreach($shared_ids as $id){
            $id_arr[] = $id->catalog_id;
        }
        $shared_catalogs = Catalog::whereIn('id', $id_arr)->get();
        return view('home', compact('catalogs', 'shared_catalogs'));
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
        $catalog = Catalog::where('id', $id)->where('user_id', Auth::user()->id)->first();
        if (!$catalog){
            $shared_catalogs = DB::table('shared_catalog')->where('catalog_id', $id)->where('user_id', Auth::user()->id)->first();
            if ($shared_catalogs){
                $catalog = Catalog::where('id', $id)->firstOrFail();
            }
            else{
                abort(404);
            }
        }
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

    /**
     * Share the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share(Request $request, $id)
    {
        $catalog = Catalog::where('id', $id)->first();
        $catalog->shared = true;
        $catalog->save();
        $user = User::where('email', $request->share_email)->first();
        if ($user){
            $catalog->sharedUsers()->attach($user);
        }
        else{
            return back()->withErrors('User not found');
        }
        return redirect()->route('catalog.index')->with('success', 'List shared successfully!');
    }
}
