<?php

namespace App\Observers;

use App\Models\Incident;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class IncidentObserver
{
    /**
     * Handle the Incident "updated" event.
     */
    public function updated(Incident $incident): void
    {
        if ($incident->isDirty('status') || $incident->isDirty('severity')) {
            $userId = \App\Models\User::resolveSimulatedUserId() ?? 1;
            AuditLog::create([
                'incident_id' => $incident->id,
                'user_id'     => $userId,
                'action'      => 'Status/Severity Changed',
                'old_values'  => array_intersect_key($incident->getOriginal(), $incident->getDirty()),
                'new_values'  => $incident->getDirty(),
                'ip_address'  => Request::ip(),
                'user_agent'  => Request::userAgent(),
            ]);
        }
    }
}
