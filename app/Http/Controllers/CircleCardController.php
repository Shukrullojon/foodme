<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Circle;
use App\Models\CircleCard;
use Illuminate\Http\Request;

class CircleCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $circlecards = CircleCard::latest()->paginate(20);
        return view('circlecard.index',[
            'circlecards' => $circlecards,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $circles = Circle::all()->pluck('name','id');
        $cards = Card::all()->pluck('name','id');
        return view('circlecard.create',[
            'circles' => $circles,
            'cards' => $cards,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'circle_id' => 'required',
            'card_id' => 'required',
            'salary' => 'required',
            'start' => 'required',
            'status' => 'required',
        ]);
        $videoName = null;
        if ($request->hasFile('gif')) {
            $video = $request->file('gif');
            $videoName = time().'.'.$video->getClientOriginalExtension();
            $video->move(public_path('data/gifs'), $videoName);
        }
        CircleCard::create([
            'circle_id' => $request->circle_id,
            'card_id' => $request->card_id,
            'salary' => $request->salary,
            'start' => $request->start,
            'status' => $request->status,
            'gif' => $videoName,
        ]);
        return redirect()->route('ccard.index')->with('success','Circle Card created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $circlecard = CircleCard::find($id);
        return view('circlecard.show',[
            'circlecard' => $circlecard,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $circlecard = CircleCard::find($id);
        $circles = Circle::all()->pluck('name','id');
        $cards = Card::all()->pluck('name','id');
        return view('circlecard.edit',[
            'circlecard' => $circlecard,
            'circles' => $circles,
            'cards' => $cards,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'circle_id' => 'required',
            'card_id' => 'required',
            'salary' => 'required',
            'start' => 'required',
            'status' => 'required',
        ]);
        $circlecard = CircleCard::find($id);
        $videoName = $circlecard->gif;
        if ($request->hasFile('gif')) {
            if (!empty($videoName) and file_exists(public_path('data/gifs/'.$videoName)))
                unlink(public_path('data/gifs/'.$videoName));
            $video = $request->file('gif');
            $videoName = time().'.'.$video->getClientOriginalExtension();
            $video->move(public_path('data/gifs'), $videoName);
        }
        $circlecard->update([
            'circle_id' => $request->circle_id,
            'card_id' => $request->card_id,
            'salary' => $request->salary,
            'start' => $request->start,
            'status' => $request->status,
            'gif' => $videoName,
        ]);
        return redirect()->route('ccard.index')
            ->with('success','Circle Card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CircleCard::where('id',$id)->delete();
        return redirect()->route('ccard.index')->with('success','Circle Card deleted successfully');
    }
}
