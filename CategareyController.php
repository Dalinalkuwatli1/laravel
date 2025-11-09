<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\categarey;
use Illuminate\Http\Request;

class CategareyController  extends Controller
{
    public function index()
    {
       
        $categareys = categarey::all();

       
        return view('dashboard.categarey.index',compact('categareys'));
    }
    public function create()
    {
        return view('dashboard.categarey.create');
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
     categarey::create($validatedData);
        return redirect()->route('categarey.index');
}
    public function edit($id)
    {
        $categareys = categarey::findOrFail($id);
        return view('dashboard.categarey.edit', compact('categareys'));
    }

    public function update(Request $request, $id)
    {
        $validatedData=  $request->validate([
            
            'Name' => 'string|required',  
            'slug' => 'string|required', 
            'status'=> 'string|required',
            'showonhome'=>'required|in:0,1',
        ]);

        $categareys = categarey::findOrFail($id);



        $categareys->update($validatedData);

        return redirect()->route('categarey.index')->with('success', 'categarey updated successfully!');
    }

    public function destroy($id)
    {
        $categarey = categarey::findOrFail($id);
       

        $categarey->delete();

        return redirect()->route('categarey.index')->with('success', 'categarey deleted successfully!');
    }
}
