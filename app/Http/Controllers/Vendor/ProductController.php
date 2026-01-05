<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create()
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        $categories = Category::all();
        return view('vendor.products-create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|string',
            'item_stock' => 'required|integer|min:0',
            'rental_duration_value' => 'nullable|integer|min:1',
            'rental_duration_unit' => 'nullable|in:day,week,month',
            'item_status' => 'nullable|in:available,unavailable',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        // Get vendor for the authenticated user
        $vendor = auth()->user()->vendor;
        if (!$vendor) {
            return back()->with('error', 'Vendor profile not found');
        }

        $data['vendor_id'] = $vendor->id;
        $data['item_status'] = $data['item_status'] ?? 'available';

        $item = Item::create($data);

        // Attach categories
        if ($request->has('categories')) {
            $item->categories()->attach($request->categories);
        }

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

        return redirect()->route('vendor.products.list')->with('success', 'Product created successfully');
    }

    public function list()
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor) {
            return back()->with('error', 'Vendor profile not found');
        }

        $items = $vendor->items()->with('galleries')->latest()->paginate(12);
        return view('vendor.products-list', compact('items'));
    }

    public function edit(Item $item)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) {
            abort(403);
        }

        $categories = Category::all();
        $item->load('galleries', 'categories');
        return view('vendor.products-edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) {
            abort(403);
        }

        $data = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_price' => 'required|numeric',
            'item_type' => 'required|in:buy,rent',
            'item_status' => 'required|in:available,unavailable',
            'item_stock' => 'required|integer|min:0',
            'rental_duration_value' => 'nullable|integer|min:1',
            'rental_duration_unit' => 'nullable|in:day,week,month',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $item->update(collect($data)->only(['item_name','item_description','item_price','item_type','item_status','item_stock','rental_duration_value','rental_duration_unit'])->toArray());

        // Sync categories
        if ($request->has('categories')) {
            $item->categories()->sync($request->categories);
        } else {
            $item->categories()->detach();
        }

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

        return redirect()->route('vendor.products.list')->with('success', 'Product updated successfully');
    }

    public function destroy(Request $request, Item $item)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor || $item->vendor_id !== $vendor->id) {
            abort(403);
        }

        $item->delete();
        return redirect()->route('vendor.products.list')->with('success', 'Product deleted');
    }

    public function deleteGallery(ItemGallery $gallery)
    {
        if (auth()->user()->role !== 'vendor') abort(403);
        
        $vendor = auth()->user()->vendor;
        if (!$vendor || $gallery->item->vendor_id !== $vendor->id) {
            abort(403);
        }

        $itemId = $gallery->item_id;
        $imagePath = public_path('images/products/' . $gallery->image_path);
        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        $gallery->delete();

        return redirect()->route('vendor.products.edit', $itemId)->with('success', 'Image deleted');
    }
}
