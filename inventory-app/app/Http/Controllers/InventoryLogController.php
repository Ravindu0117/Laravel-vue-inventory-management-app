<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryLogController extends Controller
{
    // Show item history
    public function index(Item $item)
    {
        $logs = InventoryLog::where('item_id', $item->id)
            ->latest()
            ->get();

        return Inertia::render('InventoryLogs/Index', [
            'item' => $item,
            'logs' => $logs
        ]);
    }

        public function all(Request $request)
    {
        $search = $request->q;

        // Get all items with their logs
        $items = Item::with('logs')
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->get();

        return Inertia::render('InventoryLogs/All', [
            'items' => $items,
            'filters' => [
            'q' => $search,
            ],
        ]);
    }


    // Deduct or add stock
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'change_type' => 'required|in:addition,deduction',
            'quantity' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($request->change_type === 'deduction') {
            if ($item->quantity < $request->quantity) {
                return redirect()->back()
                    ->withErrors(['quantity' => 'Not enough stock']);
            }

            $item->quantity -= $request->quantity;
        } else {
            $item->quantity += $request->quantity;
        }

        $item->save();

        InventoryLog::create([
            'item_id' => $item->id,
            'change_type' => $request->change_type,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'Inventory updated successfully');
    }
}
