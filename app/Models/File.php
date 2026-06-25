<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'user_id',
        'share_token',
        'share_token_expires_at',
    ];

    protected $casts = [
        'share_token_expires_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
