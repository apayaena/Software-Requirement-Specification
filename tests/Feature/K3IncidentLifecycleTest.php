<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Location;
use App\Models\Department;
use App\Models\Incident;
use App\Models\ActionTask;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class K3IncidentLifecycleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the WhatsAppService to prevent external API calls during testing
        $mock = Mockery::mock(WhatsAppService::class);
        $mock->shouldReceive('sendNotification')->andReturn(true);
        $this->app->instance(WhatsAppService::class, $mock);

        // Seed initial data
        $this->seed(\Database\Seeders\K3Seeder::class);
    }

    public function test_complete_k3_incident_lifecycle()
    {
        $pekerja = User::where('email', 'pekerja.lapangan@safemine.com')->first();
        $location = Location::first();
        $department = Department::where('dept_code', 'ENG')->first();

        // 1. Fase 1: Identifikasi & Pelaporan (Pekerja Lapangan)
        $reportData = [
            'location_id' => $location->id,
            'category' => 'KTA',
            'description' => 'Jalan tambang di area Pit A mengalami longsor ringan akibat hujan deras semalam.',
            'initial_action' => 'Memasang safety cone dan garis pembatas di sekitar jalan yang longsor.',
            'photo_before' => 'uploads/incidents/before_pit_a_slide.jpg',
            'incident_date' => now()->format('Y-m-d H:i:s'),
        ];

        $response = $this->actingAs($pekerja)->postJson('/api/incidents', $reportData);

        $response->assertStatus(210);
        $this->assertDatabaseHas('incidents', [
            'category' => 'KTA',
            'status' => 'Open',
        ]);
        $this->assertDatabaseHas('incident_details', [
            'photo_before' => 'uploads/incidents/before_pit_a_slide.jpg',
        ]);

        $incidentId = $response->json('data.id');

        // Verify that audit log was NOT created yet since status is still 'Open'
        // (The observer only triggers on status update, not creation, which matches spec)
        $this->assertDatabaseMissing('audit_logs', [
            'incident_id' => $incidentId,
        ]);

        // 2. Fase 2: Tinjauan Awal & Klasifikasi (HSE Officer)
        $reviewData = [
            'severity' => 'High',
        ];

        $response = $this->putJson("/api/incidents/{$incidentId}/review", $reviewData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'In Review',
            'severity' => 'High',
        ]);

        // Verify that the observer logged the status change to In Review
        $this->assertDatabaseHas('audit_logs', [
            'incident_id' => $incidentId,
            'action' => 'Status/Severity Changed',
        ]);

        // 3. Fase 3: Investigasi & Akar Masalah (HSE Supervisor)
        $investigateData = [
            'root_cause' => 'Tingginya curah hujan mempercepat erosi tebing jalan tambang yang tidak memiliki dinding penahan.',
        ];

        $response = $this->putJson("/api/incidents/{$incidentId}/investigate", $investigateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'Investigating',
        ]);
        $this->assertDatabaseHas('incident_details', [
            'incident_id' => $incidentId,
            'root_cause' => 'Tingginya curah hujan mempercepat erosi tebing jalan tambang yang tidak memiliki dinding penahan.',
        ]);

        // 4. Fase 4: Penugasan CAPA ke Departemen Terkait (Engineering)
        $capaData = [
            'assigned_department_id' => $department->id,
            'task_description' => 'Melakukan pembersihan material longsor dan membuat dinding semen penahan tebing jalan.',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ];

        $response = $this->postJson("/api/incidents/{$incidentId}/capa", $capaData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'CAPA Progress',
        ]);
        $this->assertDatabaseHas('action_tasks', [
            'incident_id' => $incidentId,
            'assigned_department_id' => $department->id,
            'status' => 'Pending',
        ]);

        $taskId = $response->json('data.id');

        // 5. Fase 4 Part 2: Penyelesaian CAPA oleh Departemen
        $completionData = [
            'photo_after' => 'uploads/incidents/after_pit_a_slide_fixed.jpg',
            'completion_notes' => 'Material longsor telah dibersihkan, tebing telah disemen dan diperkokoh dengan batu kali.',
        ];

        $response = $this->putJson("/api/incidents/capa/{$taskId}/complete", $completionData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('action_tasks', [
            'id' => $taskId,
            'status' => 'Completed',
            'photo_after' => 'uploads/incidents/after_pit_a_slide_fixed.jpg',
        ]);
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'Verifying',
        ]);

        // 6. Fase 5: Verifikasi Akhir & Penutupan (HSE Manager)
        $closeData = [
            'action' => 'approve',
        ];

        $response = $this->putJson("/api/incidents/{$incidentId}/close", $closeData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('incidents', [
            'id' => $incidentId,
            'status' => 'Closed',
        ]);
    }
}
