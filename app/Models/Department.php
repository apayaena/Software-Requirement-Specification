<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'dept_code',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function actionTasks()
    {
        return $this->hasMany(ActionTask::class, 'assigned_department_id');
    }
}
