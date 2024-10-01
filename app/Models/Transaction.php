<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'reference',
        'amount',
        'payment_link',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function transactionLog(){
        return $this->belongsTo(TransactionLog::class);
    }
}
