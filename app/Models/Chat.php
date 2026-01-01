<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_user_id',
        'last_message_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendorUser()
    {
        return $this->belongsTo(User::class, 'vendor_user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
