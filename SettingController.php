<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\setting;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class SettingController  extends Controller
{
    public function index()
    {
       
        $settings = setting::all();

       
        return view('dashboard.setting.index',compact('settings'));
    }
    public function create()
    {
        return view('dashboard.setting.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
         'company_name' => 'required',
         'phone' => 'required',
          'email' => 'required',
          'logo' => 'required',
          'address' => 'required',
        'about_company' => 'required',
           'feb' => 'required',
          'linkedin' => 'required',

        ]);
      // return dd($validatedData);
       $create = setting::create($validatedData);
       return back()->with('success','your data have been created!');
}
   

    public function update(Request $request, $id)
    {
        $validatedData=  $request->validate([
            
            'company_name' => 'string|required',
         'phone' => 'required|numeric',
          'email' => 'required|email',
          'logo' => 'required|string',
          'address' => 'string|required',
        'about_company' => 'string|required',
           'feb' => 'string|required',
          'linkedin' => 'required|url',
        ]);

        $setting = setting::findOrFail($id);

        $setting->update($validatedData);

        return redirect()->route('settings.index')->with('success', 'setting updated successfully!');
    }

    public function destroy($id)
    {
        $setting = setting::findOrFail($id);
       

        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'setting deleted successfully!');
    }
}

