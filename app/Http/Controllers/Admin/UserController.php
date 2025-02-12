<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'name' => 'required|string|min:3',
            'role' => 'required|in:admin,kasir',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $imagePath = $image->hashName();

            $image->storeAs('public/user/image', $imagePath);
        }

        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name'=> $request->name,
            'role'=> $request->role,
            'image' => $imagePath
        ]);

        return redirect()->back()->with('success','Data berhasil ditambahkan');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
