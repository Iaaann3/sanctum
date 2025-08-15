<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'jumlah',
        'nama_pemesan',
        'email',
        'no_hp',
        'total_harga',
        'status',
    ];

    public function tickets()
    {
        return $this->belongsTo(Ticket::class);
    }
}
