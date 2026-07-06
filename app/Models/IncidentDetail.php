<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'description',
        'initial_action',
        'photo_before',
        'root_cause',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }
}
