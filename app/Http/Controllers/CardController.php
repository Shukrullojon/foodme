<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Circle;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::latest()->paginate(20);
        return view('card.index',[
            'cards' => $cards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $circles = Circle::all()->pluck('name','id');
        return view('card.create',[
            'circles' => $circles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required',
            'circle_id' => 'required',
        ]);
        Card::create($request->all());
        return redirect()->route('card.index')->with('success','Card created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $card = Card::find($id);
        return view('card.show',[
            'card' => $card,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $card = Card::find($id);
        $circles = Circle::all()->pluck('name','id');
        return view('card.edit',[
            'circles' => $circles,
            'card' => $card,
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
            'circle_id' => 'required',
        ]);
        $card = Card::find($id);
        $card->update($request->all());
        return redirect()->route('card.index')
            ->with('success','Card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Card::where('id',$id)->delete();
        return redirect()->route('card.index')->with('success','Card deleted successfully');
    }
}
