<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    // Show all items
    public function index()
    {
        $items = Item::latest()->get();

        return Inertia::render('Items/Index', [
            'items' => $items
        ]);
    }

    // Store new item(s)
    public function store(Request $request)
    {
        // Validate that we have an array of items
        $request->validate([
            'items' => 'required|array',
            'items.*.name' => 'required|string|max:255',
            'items.*.measurement_unit' => 'required|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        foreach ($request->items as $itemData) {
            // Create each item
            $item = Item::create([
                'name' => $itemData['name'],
                'measurement_unit' => $itemData['measurement_unit'],
                'quantity' => $itemData['quantity'],
            ]);

            // Log the addition
            InventoryLog::create([
                'item_id' => $item->id,
                'change_type' => 'addition',
                'quantity' => $itemData['quantity'],
                'description' => 'Initial stock added',
            ]);
        }

        return redirect()->back()->with('success', 'Items added successfully');
    }

}
