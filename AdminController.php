<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
       
        $admins = Admin::all();

       
        return view('dashboard.admins.index',compact('admins'));
    }
    public function create()
    {
        return view('dashboard.admins.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string|required',
            'phone' => 'numeric|required',
            'email' => 'email|required',
            'image' => 'image|nullable',
            'status' => 'nullable',
            'password' => 'required|min:10',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = 'imgs/' . $request->file('image')->storeAs('Admin', 
            time() . '.' . $request->file('image')->extension());
        }

        $validatedData['password'] = bcrypt($request->password);

        Admin::create($validatedData);

        return redirect()->route('admins.index');
    }

    public function edit($id)
    {
        $admin = admin::findOrFail($id);
        return view('dashboard.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $validatedData =  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $admin = admin::findOrFail($id);


        if ($request->hasFile('image')) {
            if ($admin->image && file_exists(storage_path('app/public/' . $admin->image))) {
                unlink(storage_path('app/public/' . $admin->image));
            }
            $imagePath = $request->file('image')->store('admins_images', 'public');
        } else {
            $imagePath = $admin->image;
        }

        $admin->update($validatedData);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully!');
    }

    public function destroy($id)
    {
        $admin = admin::findOrFail($id);
        
        if ($admin->image && file_exists(storage_path('app/public/' . $admin->image))) {
            unlink(storage_path('app/public/' . $admin->image));
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully!');
    }
}
