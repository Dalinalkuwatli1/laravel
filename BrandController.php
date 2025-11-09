<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\brand;
use Illuminate\Http\Request;

class BrandController  extends Controller
{
    public function index()
    {
       
        $brand = brand::all();

       
        return view('dashboard.brand',compact('brand'));
    }
    public function create()
    {
        return view('dashboard.brand.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
          
            'Name' => 'string|required',  
            'slug' => 'string|required', 
            'status'=> 'string|required',
          'showonhome'=>'required',
        ]);
    //return dd($validatedData);
     brand::create($validatedData);
        return redirect()->route('brand.index');
}
    public function edit($id)
    {
        $brand = brand::findOrFail($id);
        return view('dashboard.brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $validatedData=  $request->validate([
            
            'Name' => 'string|required',  
            'slug' => 'string|required', 
            'status'=> 'string|required',
            'showonhome'=>'required|in:0,1',
        ]);
        $brand = brand::findOrFail($id);
        $brand->update($validatedData);

        return redirect()->route('brand.index')->with('success', 'brand updated successfully!');
    }

    public function destroy($id)
    {
        $brand = brand::findOrFail($id);
       

        $brand->delete();

        return redirect()->route('brand.index')->with('success', 'brand deleted successfully!');
    }
}
