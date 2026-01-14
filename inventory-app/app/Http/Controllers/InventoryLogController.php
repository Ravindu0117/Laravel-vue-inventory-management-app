<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryLogController extends Controller
{
    /**
     * Show inventory history for ONE item
     * URL: /inventory/{item}
     */
    public function index(Item $item)
    {
        $item->load('logs');

        return Inertia::render('InventoryLogs/Index', [
            'item' => $item,
            'logs' => $item->logs,
        ]);
    }

    /**
     * Show ALL inventory items + logs (with search)
     * URL: /inventory
     */
    public function all(Request $request)
    {
        $search = $request->q;

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

    /**
     * Edit / Manage inventory for ONE item
     * URL: /inventory/{item}/edit
     */
    public function edit(Item $item)
    {
        $item->load('logs');

        return Inertia::render('InventoryLogs/Edit', [
            'item' => $item,
        ]);
    }

    /**
     * ADD or DEDUCT stock (used by forms)
     * URL: POST /inventory
     */
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
                return redirect()->back()->withErrors([
                    'quantity' => 'Not enough stock available',
                ]);
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

    /**
     * DEDUCT ONLY (clean endpoint for edit page)
     * URL: POST /inventory/deduct
     */
    public function deduct(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->quantity < $request->quantity) {
            return back()->withErrors([
                'quantity' => 'Not enough stock available',
            ]);
        }

        $item->decrement('quantity', $request->quantity);

        InventoryLog::create([
            'item_id' => $item->id,
            'change_type' => 'deduction',
            'quantity' => $request->quantity,
            'description' => $request->description ?? 'Stock deducted',
        ]);

        return redirect()->back()->with('success', 'Stock deducted successfully');
    }
}
