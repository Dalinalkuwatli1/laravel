<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\user;
use Illuminate\Http\Request;

class usercontroller extends Controller
{
    //
    public function index()
    {
       
        $users = User::all();

       
        return view('dashboard.users.index',compact('users'));
    }
    public function create()
    {
        return view('dashboard.users.cerate');
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
            $validatedData['image'] = 'imgs/' . $request->file('image')->storeAs('user', time() . '.' . $request->file('image')->extension());
        }

        $validatedData['password'] = bcrypt($request->password);

       User::create($validatedData);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = user::findOrFail($id);
        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'phone' => 'nullable|string|max:15',
            'status' => 'nullable|',
            'password' => 'nullable|string|min:6',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = user::findOrFail($id);


        if ($request->hasFile('image')) {
            if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
                unlink(storage_path('app/public/' . $user->image));
            }
            $imagePath = $request->file('image')->store('users_images', 'public');
        } else {
            $imagePath = $user->image;
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ?? 1,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'image' => $imagePath,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function delete($id)
    {
        $user = user::findOrFail($id);
        
        if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
            unlink(storage_path('app/public/' . $user->image));
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
