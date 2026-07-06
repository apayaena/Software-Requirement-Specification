<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\IncidentDetail;
use App\Models\ActionTask;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\IncidentMonthlyExport;

class IncidentController extends Controller
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Fase 1: Identifikasi & Pelaporan (Pekerja Lapangan)
     * Status Awal: Open
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
            'category' => 'required|in:KTA,TTA,Near Miss,Accident',
            'description' => 'required|string|min:10',
            'initial_action' => 'nullable|string',
            'photo_before' => 'required|string', // Path to the uploaded photo
            'incident_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $result = DB::transaction(function () use ($request) {
                // Generate Ticket Number: INC/YYYYMMDD/XXXX
                $dateStr = Carbon::parse($request->incident_date)->format('Ymd');
                $countToday = Incident::whereDate('created_at', Carbon::today())->count() + 1;
                $ticketNumber = 'INC/' . $dateStr . '/' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

                // Create Incident
                $incident = Incident::create([
                    'ticket_number' => $ticketNumber,
                    'reporter_id' => User::resolveSimulatedUserId() ?? 3,
                    'location_id' => $request->location_id,
                    'category' => $request->category,
                    'status' => 'Open',
                    'incident_date' => $request->incident_date,
                ]);

                // Decode photo if base64
                $photoBeforePath = $request->photo_before;
                if (str_starts_with($photoBeforePath, 'data:image')) {
                    $data = explode(',', $photoBeforePath);
                    $decoded = base64_decode($data[1]);
                    $extension = str_contains($data[0], 'png') ? 'png' : 'jpg';
                    $fileName = 'compressed_' . time() . '_' . uniqid() . '.' . $extension;
                    
                    $dir = public_path('uploads/incidents');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    
                    file_put_contents($dir . '/' . $fileName, $decoded);
                    $photoBeforePath = 'uploads/incidents/' . $fileName;
                }

                // Create Incident Detail
                IncidentDetail::create([
                    'incident_id' => $incident->id,
                    'description' => $request->description,
                    'initial_action' => $request->initial_action,
                    'photo_before' => $photoBeforePath,
                ]);

                return $incident;
            });

            // Send notification to HSE Officers
            $hseOfficers = User::whereHas('department', function ($query) {
                $query->where('dept_code', 'HSE');
            })->get();

            $message = "🚨 *Laporan K3 Baru* 🚨\n\nNo. Tiket: {$result->ticket_number}\nKategori: {$result->category}\nStatus: Open\n\nHarap segera lakukan tinjauan awal di dashboard sistem.";
            
            foreach ($hseOfficers as $officer) {
                if ($officer->phone_number) {
                    $this->whatsAppService->sendNotification($officer->phone_number, $message);
                }
            }

            return response()->json([
                'message' => 'Laporan insiden berhasil dikirim.',
                'data' => $result->load('details', 'location')
            ], 210);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memproses laporan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fase 2: Tinjauan Awal & Klasifikasi (HSE Officer)
     * Status: In Review
     */
    public function review(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'severity' => 'required|in:Low,Medium,High,Critical',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $incident->update([
            'severity' => $request->severity,
            'status' => 'In Review'
        ]);

        // Escalation notification for Critical severity
        if ($request->severity === 'Critical') {
            $hseManager = User::where('email', 'manager.hse@safemine.com')->first();
            if ($hseManager && $hseManager->phone_number) {
                $alertMessage = "⚠️ *ALARM INSIDEN CRITICAL* ⚠️\n\nTiket: {$incident->ticket_number}\nKategori: {$incident->category}\nKeparahan: *CRITICAL*\n\nInvestigasi lapangan harus segera dilaksanakan dalam waktu 1x24 jam.";
                $this->whatsAppService->sendNotification($hseManager->phone_number, $alertMessage);
            }
        }

        return response()->json([
            'message' => 'Klasifikasi tingkat keparahan berhasil disimpan.',
            'data' => $incident
        ]);
    }

    /**
     * Fase 3: Investigasi & Analisis Akar Masalah (HSE Supervisor)
     * Status: Investigating
     */
    public function investigate(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $details = IncidentDetail::where('incident_id', $incident->id)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'root_cause' => 'required|string|min:10', // 5 Whys / SCAT Analysis text
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($incident, $details, $request) {
            $details->update([
                'root_cause' => $request->root_cause
            ]);

            $incident->update([
                'status' => 'Investigating'
            ]);
        });

        return response()->json([
            'message' => 'Hasil investigasi akar masalah berhasil dicatat.',
            'data' => $incident->load('details')
        ]);
    }

    /**
     * Fase 4: Penugasan Tindakan Perbaikan (CAPA)
     * Status: CAPA Progress
     */
    public function assignCapa(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'assigned_department_id' => 'required|exists:departments,id',
            'task_description' => 'required|string|min:5',
            'due_date' => 'required|date|after:today',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = DB::transaction(function () use ($incident, $request) {
            $task = ActionTask::create([
                'incident_id' => $incident->id,
                'assigned_department_id' => $request->assigned_department_id,
                'task_description' => $request->task_description,
                'due_date' => $request->due_date,
                'status' => 'Pending',
            ]);

            $incident->update([
                'status' => 'CAPA Progress'
            ]);

            return $task;
        });

        // Notify department head or staff via WhatsApp (Mock notification)
        $dept = $task->department;
        $message = "📋 *Penugasan CAPA K3 Baru* 📋\n\nNo. Tiket: {$incident->ticket_number}\nDepartemen: {$dept->department_name}\nTugas: {$task->task_description}\nBatas Waktu (SLA): {$task->due_date->format('d-m-Y')}\n\nHarap segera menindaklanjuti perbaikan di lapangan.";
        
        // Find users in the assigned department to notify
        $deptUsers = User::where('department_id', $request->assigned_department_id)->get();
        foreach ($deptUsers as $user) {
            if ($user->phone_number) {
                $this->whatsAppService->sendNotification($user->phone_number, $message);
            }
        }

        return response()->json([
            'message' => 'Tindakan perbaikan (CAPA) berhasil ditugaskan.',
            'data' => $task
        ]);
    }

    /**
     * Fase 4 (Part 2): Penyelesaian Tindakan Perbaikan oleh Departemen
     * Status: Verifying
     */
    public function completeCapa(Request $request, $taskId)
    {
        $task = ActionTask::findOrFail($taskId);
        $incident = $task->incident;

        $validator = Validator::make($request->all(), [
            'photo_after' => 'required|string', // Path to the proof photo
            'completion_notes' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::transaction(function () use ($task, $incident, $request) {
            $photoAfterPath = $request->photo_after;
            if (str_starts_with($photoAfterPath, 'data:image')) {
                $data = explode(',', $photoAfterPath);
                $decoded = base64_decode($data[1]);
                $extension = str_contains($data[0], 'png') ? 'png' : 'jpg';
                $fileName = 'capa_after_' . time() . '_' . uniqid() . '.' . $extension;
                
                $dir = public_path('uploads/incidents');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                file_put_contents($dir . '/' . $fileName, $decoded);
                $photoAfterPath = 'uploads/incidents/' . $fileName;
            }

            $task->update([
                'photo_after' => $photoAfterPath,
                'completion_notes' => $request->completion_notes,
                'status' => 'Completed',
                'completed_at' => Carbon::now()
            ]);

            // Update incident status to Verifying so HSE Manager can review
            $incident->update([
                'status' => 'Verifying'
            ]);
        });

        // Notify HSE Manager
        $hseManager = User::where('email', 'manager.hse@safemine.com')->first();
        if ($hseManager && $hseManager->phone_number) {
            $message = "✅ *Bukti Perbaikan CAPA Selesai* ✅\n\nTiket: {$incident->ticket_number}\nDepartemen: {$task->department->department_name}\nCatatan: {$task->completion_notes}\n\nHarap segera lakukan verifikasi akhir di dashboard.";
            $this->whatsAppService->sendNotification($hseManager->phone_number, $message);
        }

        return response()->json([
            'message' => 'Laporan perbaikan berhasil dikirim, menunggu verifikasi HSE Manager.',
            'data' => $task
        ]);
    }

    /**
     * Fase 5: Verifikasi Akhir & Penutupan (HSE Manager)
     * Status: Closed
     */
    public function close(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'rejection_notes' => 'required_if:action,reject|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->action === 'approve') {
            $incident->update([
                'status' => 'Closed'
            ]);

            // Notify reporter that the ticket has been closed
            $reporter = $incident->reporter;
            if ($reporter && $reporter->phone_number) {
                $message = "🎉 *Tiket Pelaporan K3 Anda Ditutup* 🎉\n\nNo. Tiket: {$incident->ticket_number}\nStatus: CLOSED\n\nTerima kasih telah berkontribusi menjaga keselamatan di area tambang.";
                $this->whatsAppService->sendNotification($reporter->phone_number, $message);
            }

            return response()->json([
                'message' => 'Tiket laporan K3 telah berhasil diverifikasi dan DITUTUP.',
                'data' => $incident
            ]);
        } else {
            // Reject - return status to CAPA Progress
            $incident->update([
                'status' => 'CAPA Progress'
            ]);

            // Mark the last task back to Pending/In Progress
            $lastTask = ActionTask::where('incident_id', $incident->id)->latest()->first();
            if ($lastTask) {
                $lastTask->update([
                    'status' => 'Pending',
                ]);
            }

            // Notify the department about rejection
            $deptUsers = User::where('department_id', $lastTask->assigned_department_id)->get();
            $message = "❌ *Perbaikan CAPA Ditolak* ❌\n\nNo. Tiket: {$incident->ticket_number}\nAlasan Penolakan: {$request->rejection_notes}\n\nHarap lakukan perbaikan ulang sesuai instruksi.";
            
            foreach ($deptUsers as $user) {
                if ($user->phone_number) {
                    $this->whatsAppService->sendNotification($user->phone_number, $message);
                }
            }

            return response()->json([
                'message' => 'Bukti perbaikan ditolak. Tiket dikembalikan ke departemen terkait.',
                'data' => $incident
            ]);
        }
    }

    /**
     * Get list of all incidents with basic relationships.
     */
    public function index()
    {
        $incidents = Incident::with(['reporter', 'location', 'actionTasks.department'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($incidents);
    }

    /**
     * Get detail of a specific incident with details, CAPA tasks, and audit trail timeline.
     */
    public function show($id)
    {
        $incident = Incident::with([
            'reporter.department', 
            'location', 
            'details', 
            'actionTasks.department', 
            'actionTasks.assignedUser', 
            'auditLogs.user.department'
        ])->findOrFail($id);

        return response()->json($incident);
    }

    /**
     * Export incidents to Excel for a specific month and year.
     */
    public function exportExcel(Request $request)
    {
        $month = (int) $request->query('month', Carbon::now()->month);
        $year = (int) $request->query('year', Carbon::now()->year);

        return Excel::download(new IncidentMonthlyExport($month, $year), "laporan_insiden_k3_{$year}_{$month}.xlsx");
    }

    /**
     * Export incidents to PDF for a specific month and year.
     */
    public function exportPdf(Request $request)
    {
        $month = (int) $request->query('month', Carbon::now()->month);
        $year = (int) $request->query('year', Carbon::now()->year);

        $incidents = Incident::with(['reporter.department', 'location', 'details', 'actionTasks.department'])
            ->whereMonth('incident_date', $month)
            ->whereYear('incident_date', $year)
            ->get();

        $monthName = Carbon::create()->month($month)->locale('id')->monthName;

        $pdf = Pdf::loadView('exports.incidents_pdf', compact('incidents', 'month', 'year', 'monthName'));
        
        return $pdf->download("laporan_insiden_k3_{$year}_{$month}.pdf");
    }
}
