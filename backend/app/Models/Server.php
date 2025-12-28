<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pterodactyl_server_id',
        'name',
        'identifier',
        'node_id',
        'egg_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
