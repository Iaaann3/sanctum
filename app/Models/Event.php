<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ticket; 

class Event extends Model
{
    

    use HasFactory;

    protected $fillable = [
        'nama_event',
        'deskripsi',
        'tanggal_event',
        'lokasi',
        'banner'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}


