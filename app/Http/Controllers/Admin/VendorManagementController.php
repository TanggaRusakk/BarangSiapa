<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;

class VendorManagementController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = Vendor::with('user')->paginate(25);
        return view('admin.vendors', compact('vendors'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = User::where('role', 'vendor')->get();
        return view('admin.vendors-create', compact('users'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vendor_name' => 'required|string|max:255',
            'vendor_description' => 'nullable|string',
        ]);
        
        Vendor::create($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor created');
    }

    public function edit(Vendor $vendor)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = User::where('role', 'vendor')->get();
        return view('admin.vendors-edit', compact('vendor', 'users'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'vendor_name' => 'required|string|max:255',
            'vendor_description' => 'nullable|string',
        ]);
        
        $vendor->update($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor updated');
    }

    public function destroy(Vendor $vendor)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendor->delete();
        return redirect()->route('admin.vendors')->with('success', 'Vendor deleted');
    }
}
