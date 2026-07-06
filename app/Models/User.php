<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'department_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reportedIncidents()
    {
        return $this->hasMany(Incident::class, 'reporter_id');
    }

    public function actionTasks()
    {
        return $this->hasMany(ActionTask::class, 'assigned_user_id');
    }

    /**
     * Resolve the simulated user ID based on request headers.
     *
     * @return int|null
     */
    public static function resolveSimulatedUserId()
    {
        $role = request()->header('X-Simulated-Role');
        if ($role) {
            $email = match ($role) {
                'manager' => 'manager.hse@safemine.com',
                'officer', 'supervisor' => 'officer.hse@safemine.com',
                'pekerja', 'pic' => 'pekerja.lapangan@safemine.com',
                default => null,
            };
            if ($email) {
                $user = self::where('email', $email)->first();
                if ($user) {
                    return $user->id;
                }
            }
        }

        // Fallback for backward compatibility
        $simulatedId = request()->header('X-Simulated-User-Id');
        if ($simulatedId && self::where('id', $simulatedId)->exists()) {
            return $simulatedId;
        }

        return auth()->id();
    }
}

