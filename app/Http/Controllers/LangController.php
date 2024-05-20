<?php

namespace App\Http\Controllers;

use App\Models\Lang;
use Illuminate\Http\Request;

class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $langs = Lang::latest()->paginate(20);
        return view('lang.index',[
            'langs' => $langs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        Lang::create($request->all());
        return redirect()->route('lang.index')->with('success','Lang created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lang = Lang::find($id);
        return view('lang.show',[
            'lang' => $lang,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lang = Lang::find($id);
        return view('lang.edit',[
            'lang' => $lang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
        ]);
        $lang = Lang::find($id);
        $lang->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        return redirect()->route('lang.index')
            ->with('success','Lang updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Lang::where('id',$id)->delete();
        return redirect()->route('lang.index')
            ->with('success','Lang deleted successfully');
    }
}
