<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Venue extends Model
{
    use HasFactory;
    protected $table ="venues";
    protected $primaryKey="venue_id";

    protected $fillable = [
        'venue_name',
        'address',
        'is_active',
    ];

}
