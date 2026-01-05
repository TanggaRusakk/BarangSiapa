<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\Vendor;
use Illuminate\Http\Request;

class ItemManagementController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $items = Item::with('vendor')->paginate(25);
        return view('admin.items', compact('items'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = Vendor::all();
        return view('admin.items-create', compact('vendors'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:buy,rent',
            'item_status' => 'nullable|in:available,unavailable',
            'item_stock' => 'required|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
        ]);

        $item = Item::create($data);

        // Handle uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/products'), $filename);
                ItemGallery::create([
                    'item_id' => $item->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item created');
    }

    public function edit(Item $item)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $vendors = Vendor::all();
        return view('admin.items-edit', compact('item', 'vendors'));
    }

    public function update(Request $request, Item $item)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:buy,rent',
            'item_status' => 'nullable|in:available,unavailable',
            'item_stock' => 'required|integer|min:0',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
        ]);

        $item->update(collect($data)->only(['item_name','item_description','item_price','item_type','item_status','item_stock','vendor_id'])->toArray());

        // Handle new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '_' . uniqid() . '.' . $ext;
                $file->move(public_path('images/products'), $filename);
                ItemGallery::create([
                    'item_id' => $item->id,
                    'image_path' => $filename,
                ]);
            }
        }

        return redirect()->route('admin.items')->with('success', 'Item updated');
    }

    public function destroy(Item $item)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $item->delete();
        return redirect()->route('admin.items')->with('success', 'Item deleted');
    }
}
