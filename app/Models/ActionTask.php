<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'assigned_department_id',
        'assigned_user_id',
        'task_description',
        'due_date',
        'status',
        'photo_after',
        'completion_notes',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'assigned_department_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
