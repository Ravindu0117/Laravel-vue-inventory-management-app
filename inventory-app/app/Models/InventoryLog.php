<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
   protected $fillable = [
        'item_id',
        'change_type',
        'quantity',
        'description',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
