<?php

namespace App\Http\Controllers;

use App\Models\Fcategory;
use App\Models\Fproduct;
use Illuminate\Http\Request;

class FproductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fproducts = Fproduct::latest()->paginate(20);
        return view('fproduct.index',[
            'fproducts' => $fproducts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fcategories = Fcategory::where('status',1)->get()->pluck('name','id');
        return view('fproduct.create',[
            'fcategories' => $fcategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->image){
            $file_name = date('Y_m_d_H_i_s').rand(10000, 99999).'.'.$request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $file_name);
        }
        Fproduct::create([
            'name' => $request->name,
            'info' => $request->info,
            'price' => $request->price,
            'old_price' => $request->old_price,
            'come_price' => $request->come_price,
            'wallet_discount' => $request->wallet_discount,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'image' => $file_name ?? '',
        ]);
        return redirect()->route('fproduct.index')->with('success', 'Fproduct create successfuly');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fproduct = Fproduct::find($id);
        $fproduct->delete();
        return redirect()->route('fproduct.index')
            ->with('success','Fproduct deleted successfully');
    }
}
