<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan Insiden K3 Tambang - SafeMine-HSE</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-text {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
        }
        .subtitle {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            color: #222;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 4px 0;
        }
        .meta-label {
            font-weight: bold;
            width: 15%;
        }
        .meta-value {
            width: 35%;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .main-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        /* Severity Colors */
        .badge-low { background-color: #e6f4ea; color: #137333; }
        .badge-medium { background-color: #fef7e0; color: #b06000; }
        .badge-high { background-color: #feefe3; color: #c26401; }
        .badge-critical { background-color: #fce8e6; color: #c5221f; }
        .badge-unclassified { background-color: #f1f3f4; color: #5f6368; }

        /* Status Colors */
        .badge-open { background-color: #e8f0fe; color: #1a73e8; }
        .badge-in_review { background-color: #f3e8fd; color: #9333ea; }
        .badge-investigating { background-color: #fef7e0; color: #d97706; }
        .badge-capa_progress { background-color: #fffbeb; color: #ca8a04; }
        .badge-verifying { background-color: #e6fffa; color: #0d9488; }
        .badge-closed { background-color: #e6f4ea; color: #16a34a; }

        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
            color: #0056b3;
        }
        .summary-grid {
            width: 100%;
        }
        .summary-grid td {
            width: 25%;
            text-align: center;
            border-right: 1px solid #ddd;
            padding: 5px 0;
        }
        .summary-grid td:last-child {
            border-right: none;
        }
        .summary-num {
            font-size: 16px;
            font-weight: bold;
            color: #222;
        }
        .summary-label {
            font-size: 8px;
            color: #777;
            text-transform: uppercase;
        }
        .footer-note {
            text-align: center;
            font-size: 9px;
            color: #999;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <span class="logo-text">SafeMine-HSE</span><br>
                    <span class="subtitle">Sistem Informasi Manajemen Insiden K3 Tambang</span>
                </td>
                <td style="text-align: right; vertical-align: bottom; font-size: 9px; color: #666;">
                    Regulasi: Kepmen ESDM No. 1827 K/30/MEM/2018
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">
        LAPORAN REKAPITULASI INSIDEN K3<br>
        PERIODE: {{ strtoupper($monthName) }} {{ $year }}
    </div>

    @php
        $totalIncidents = $incidents->count();
        $criticalCount = $incidents->where('severity', 'Critical')->count();
        $highCount = $incidents->where('severity', 'High')->count();
        $closedCount = $incidents->where('status', 'Closed')->count();
        $completionRate = $totalIncidents > 0 ? round(($closedCount / $totalIncidents) * 100) : 100;
    @endphp

    <div class="summary-box">
        <div class="summary-title">Dashboard Kinerja K3 Bulanan</div>
        <table class="summary-grid">
            <tr>
                <td>
                    <div class="summary-num">{{ $totalIncidents }}</div>
                    <div class="summary-label">Total Temuan</div>
                </td>
                <td>
                    <div class="summary-num" style="color: #c5221f;">{{ $criticalCount }}</div>
                    <div class="summary-label">Critical severity</div>
                </td>
                <td>
                    <div class="summary-num" style="color: #16a34a;">{{ $closedCount }}</div>
                    <div class="summary-label">Kasus Selesai</div>
                </td>
                <td>
                    <div class="summary-num" style="color: #0056b3;">{{ $completionRate }}%</div>
                    <div class="summary-label">Rasio CAPA Closure</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-table">
        <tr>
            <td class="meta-label">Tanggal Cetak:</td>
            <td class="meta-value">{{ \Carbon\Carbon::now()->format('d-m-Y H:i') }} WIB</td>
            <td class="meta-label">Dicetak Oleh:</td>
            <td class="meta-value">HSE System Administrator</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 15%;">No. Tiket</th>
                <th style="width: 15%;">Pelapor & Area</th>
                <th style="width: 10%;">Kategori</th>
                <th style="width: 10%;">Severity</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 40%;">Deskripsi Kejadian & CAPA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $inc)
                <tr>
                    <td style="font-family: monospace; font-weight: bold; font-size: 10px;">{{ $inc->ticket_number }}</td>
                    <td>
                        <strong>{{ $inc->reporter->name }}</strong><br>
                        Area: {{ $inc->location->site_name }}
                    </td>
                    <td>{{ $inc->category }}</td>
                    <td>
                        @if($inc->severity)
                            <span class="badge badge-{{ strtolower($inc->severity) }}">{{ $inc->severity }}</span>
                        @else
                            <span class="badge badge-unclassified">Belum</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-{{ strtolower(str_replace(' ', '_', $inc->status)) }}">{{ $inc->status }}</span>
                    </td>
                    <td>
                        <strong>Deskripsi:</strong> {{ $inc->details ? Str::limit($inc->details->description, 100) : '-' }}
                        @if($inc->actionTasks->count() > 0)
                            <br><br>
                            <strong>CAPA Tasks:</strong>
                            <ul style="margin: 3px 0 0 15px; padding: 0;">
                                @foreach($inc->actionTasks as $task)
                                    <li>[{{ $task->status }}] {{ $task->task_description }} (Dept: {{ $task->department->dept_code }})</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #666; font-style: italic;">
                        Tidak ada laporan insiden tercatat untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh SafeMine-HSE K3 Incident Management System.<br>
        HSE Department - SafeMine Mining Operations.
    </div>

</body>
</html>
