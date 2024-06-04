<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function addItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'stok' => 'required|integer',
            'desc' => 'nullable|string',
            'price' => 'required|numeric',
            'tag' => 'nullable|string',
        ]);

        // if username already same with the existing one, js add the stok
        $existcheck = Item::where('name', $request->name)->first();
        if ($existcheck) {
            $existcheck->stok += $request->stok;
            $existcheck->save();
            return response()->json([
                'message' => 'Item already exists, added stock',
                'data' => $existcheck,
            ], 200);
        }
        
        if ($request->hasFile('thumbnail')) {
            //delete the old file
            $media = $request->file('thumbnail')->store('thumbnail', 'public');
            
        }

        Item::create([
            'name' => $request->name,
            'thumbnail' => $media ?? null,
            'stok' => $request->stok,
            'desc' => $request->desc,
            'price' => $request->price,
            'tag' => $request->tag,
        ]);
        
        return response()->json([
            'message' => 'Item added successfully',
            'data' => $request->all(),
            // comment this out
            'imagetype' => $request->thumbnail,
            'tag' => $request->tag,
        ], 200);
    }

    public function getItems(Request $request)
    {
        $items = Item::where('id', $request->id)->get();
        return response()->json([
            'message' => 'Items fetched successfully',
            'data' => $items,
        ], 200);
    }


    public function fetchAllItems(Request $request)
    {
        $items = Item::all();

        return response()->json([
            'message' => 'Items fetched successfully',
            'data' => $items,
        ], 200);
    }

    public function editItem(Request $request){
        $request->validate([
            'id' => 'required',
            'name' => 'required|string',
            'desc' => 'nullable|string',
            'stok' => 'required|integer',
            'price' => 'required|numeric',
            'tag' => 'nullable|string',
        ]);

        $item = Item::find($request->id);

        // if tag is null, set $tag as the existing tag, else set as the new tag
        $tag = $request->tag ?? $item->tag;




        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'tag' => $tag,
            'price' => $request->price,
            'stok' => $request->stok,
        ];
        
        if ($request->hasFile('thumbnail')) {
            $media = $request->file('thumbnail')->store('thumbnail', 'public');
            $data['thumbnail'] = $media;
        } elseif ($request->thumbnail) {
            $data['thumbnail'] = null;
        }
        
        $item->update($data);
        
        return response()->json([
            'message' => 'Item updated successfully',
            'data' => $item,
        ], 200);
    }

    // delete

    public function deleteItems(Request $request)
    {
        $request->validate([
            'id' => 'required|array',
        ]);

        Item::whereIn('id', $request->id)->delete();

        return response()->json([
            'message' => 'Items deleted successfully',
        ], 200);
    }

    //delete single item

    public function deleteItem(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $item = Item::find($request->id);

        if (!$item) {
            return response()->json([
                'message' => 'Item not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Item deleted successfully',
        ], 200);
    }

    public function getItemsNamePrice(Request $request)
    {
        $items = Item::all();
        $data = [];
        foreach ($items as $item) {
            $data[] = [
                'name' => $item->name,
                'price' => $item->price,
                'id' => $item->id,
                'stok' => $item->stok,
                'qty' => 0,
            ];
        }

        return response()->json([
            'message' => 'Items fetched successfully',
            'data' => $data,
        ], 200);
    }
}
