<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    protected $fillable = [
        'order_id', 'ticket_id', 'jumlah', 'harga_satuan'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

