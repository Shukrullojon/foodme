<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Lang;
use Illuminate\Http\Request;

class CircleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $circles     = Circle::latest()->paginate(20);
        return view('circle.index',[
            'circles' => $circles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $langs = Lang::all()->pluck('name','id');
        return view('circle.create',[
            'langs' => $langs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'info' => 'required',
            'lang_id' => 'required',
            'status' => 'required',
        ]);
        Circle::create($request->all());
        return redirect()->route('circle.index')->with('success','Circle created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $circle = Circle::find($id);
        return view('circle.show',[
            'circle' => $circle,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $circle = Circle::find($id);
        $langs = Lang::all()->pluck('name','id');
        return view('circle.edit',[
            'circle' => $circle,
            'langs' => $langs,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'info' => 'required',
            'lang_id' => 'required',
            'status' => 'required',
        ]);
        $circle = Circle::find($id);
        $circle->update($request->all());
        return redirect()->route('circle.index')
            ->with('success','Circle updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Circle::where('id',$id)->delete();
        return redirect()->route('circle.index')->with('success','Circle deleted successfully');
    }
}
