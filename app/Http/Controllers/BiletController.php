<?php

namespace App\Http\Controllers;

use App\Models\Bilet;
use App\Models\Card;
use Illuminate\Http\Request;

class BiletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bilets = Bilet::latest()->paginate(20);
        return view('bilet.index',[
            'bilets' => $bilets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cards = Card::all()->pluck('name','id');
        return view('bilet.create',[
            'cards' => $cards,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'card_id' => 'required',
            'name' => 'required',
            'info' => 'required',
        ]);
        Bilet::create($request->all());
        return redirect()->route('bilet.index')->with('success','Bilet created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bilet = Bilet::find($id);
        return view('bilet.show',[
            'bilet' => $bilet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bilet = Bilet::find($id);
        $cards = Card::all()->pluck('name','id');
        return view('bilet.edit',[
            'cards' => $cards,
            'bilet' => $bilet,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'card_id' => 'required',
            'name' => 'required',
            'info' => 'required',
        ]);
        Bilet::where('id',$id)->update($request->all());
        return redirect()->route('bilet.index')
            ->with('success','Bilet updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Bilet::where('id',$id)->delete();
        return redirect()->route('bilet.index')->with('success','Bilet deleted successfully');
    }
}
