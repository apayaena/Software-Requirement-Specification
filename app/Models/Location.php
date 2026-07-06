<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'area_type',
        'latitude',
        'longitude',
    ];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
