<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
       
        $admins = Admin::all();

       
        return view('dashboard.admins.index',compact('admins'));
    }
    public function create()
    {
        return view('dashboard.admins.create',compact('admins'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'string|required',
            'phone' => 'numeric|required',
            'email' => 'email|required',
            'image' => 'image|nullable',
            'status' => 'required',
            'password' => 'required|min:10',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['image'] = 'imgs/' . $request->file('image')->storeAs('Admin', time() . '.' . $request->file('image')->extension());
        }

        $validatedData['password'] = bcrypt($request->password);

        Admin::create($validatedData);

        return redirect()->route('admins.index');
    }

    public function edit($email)
    {
        $admin = Admin::where('email', $email)->firstOrFail(); 
        return view('dashboard.admins.edit', compact('admin'));
    }

    public function update(Request $request, $email)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'phone' => 'required|string|max:15',
            'status' => 'required|in:Active,Inactive',
            'password' => 'nullable|string|min:6',
            'quota' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $admin = Admin::where('email', $email)->firstOrFail();

        if ($request->hasFile('image')) {
            if ($admin->image && file_exists(storage_path('app/public/' . $admin->image))) {
                unlink(storage_path('app/public/' . $admin->image));
            }
            $imagePath = $request->file('image')->store('admins_images', 'public');
        } else {
            $imagePath = $admin->image;
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'password' => $request->password ? bcrypt($request->password) : $admin->password,
            'quota' => $request->quota,
            'image' => $imagePath,
        ]);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully!');
    }

    public function destroy($email)
    {
        $admin = Admin::where('email', $email)->firstOrFail();
        
        if ($admin->image && file_exists(storage_path('app/public/' . $admin->image))) {
            unlink(storage_path('app/public/' . $admin->image));
        }

        $admin->delete();

        return redirect()->route('admins.index')->with('success', 'Admin deleted successfully!');
    }
}
