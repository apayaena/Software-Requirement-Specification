<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'reporter_id',
        'location_id',
        'category',
        'status',
        'severity',
        'incident_date',
    ];

    protected $casts = [
        'incident_date' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function details()
    {
        return $this->hasOne(IncidentDetail::class);
    }

    public function actionTasks()
    {
        return $this->hasMany(ActionTask::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class)->orderBy('created_at', 'desc');
    }
}
