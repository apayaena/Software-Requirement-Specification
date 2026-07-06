<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeMine HSE - K3 Incident Management System</title>
    <link rel="manifest" href="/manifest.json">
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('/service-worker.js');
        });
      }
    </script>
    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ff5a1f">
    
    <style>
        :root {
            --bg-main: #0b0c10;
            --bg-card: #151821;
            --bg-card-hover: #1e2230;
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --text-muted: #6b7280;
            
            /* Status Colors */
            --color-open: #3b82f6;
            --color-in-review: #a855f7;
            --color-investigating: #f97316;
            --color-capa: #eab308;
            --color-verifying: #06b6d4;
            --color-closed: #22c55e;
            
            /* Severity Colors */
            --color-low: #10b981;
            --color-medium: #eab308;
            --color-high: #f97316;
            --color-critical: #ef4444;
            
            --font-main: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --shadow-glow: 0 0 20px rgba(59, 130, 246, 0.15);
            --transition-speed: 0.25s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-primary);
            font-family: var(--font-main);
            line-height: 1.5;
            overflow-x: hidden;
        }

        /* Layout Structure */
        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Glassmorphism Header */
        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(21, 24, 33, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-icon {
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
            font-weight: 800;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);
        }

        .logo-text h1 {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            background: linear-gradient(to right, #f3f4f6, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo-text span {
            font-size: 0.75rem;
            color: var(--color-investigating);
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Role Switcher Widget */
        .role-switcher-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
        }

        .role-switcher-box label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        .role-select {
            background: #1e2230;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            outline: none;
        }

        .role-select:focus {
            border-color: var(--color-open);
        }

        /* Dashboard Grid Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.2fr 1.8fr;
            gap: 1.5rem;
            padding: 1.5rem 2rem;
            flex-grow: 1;
            height: calc(100vh - 4.5rem);
            overflow: hidden;
        }

        /* Scrollable Panels */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: 100%;
        }

        .panel-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.01);
        }

        .panel-title {
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .panel-body {
            padding: 1.5rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        /* Right panel grid: Split list and detail */
        .right-panel-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            height: 100%;
            overflow: hidden;
        }

        .ticket-list-sec {
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }

        .ticket-detail-sec {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.15);
        }

        /* Buttons & Forms styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.6rem;
            letter-spacing: 0.02em;
        }

        .form-control, select, textarea {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-family: var(--font-main);
            font-size: 0.9rem;
            outline: none;
            transition: all var(--transition-speed) ease;
        }

        .form-control:focus, select:focus, textarea:focus {
            border-color: var(--color-open);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
            background: #1a1d29;
        }

        .btn {
            background: linear-gradient(135deg, var(--color-open), #2563eb);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed) ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);
        }

        .btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 6px 12px rgba(37, 99, 235, 0.3);
        }

        .btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-secondary {
            background: #27272a;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--color-critical), #dc2626);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--color-closed), #16a34a);
        }

        /* Ticket Cards */
        .ticket-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all var(--transition-speed) ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .ticket-card:hover {
            background: var(--bg-card-hover);
            border-color: var(--color-open);
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .ticket-card.active {
            background: rgba(59, 130, 246, 0.08);
            border-color: var(--color-open);
            box-shadow: var(--shadow-glow), 0 0 0 1px var(--color-open);
        }

        .ticket-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .ticket-id {
            font-family: var(--font-mono);
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--color-open);
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        /* Severity Badges */
        .badge-low { background: rgba(16, 185, 129, 0.15); color: var(--color-low); border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-medium { background: rgba(234, 179, 8, 0.15); color: var(--color-medium); border: 1px solid rgba(234, 179, 8, 0.3); }
        .badge-high { background: rgba(249, 115, 22, 0.15); color: var(--color-high); border: 1px solid rgba(249, 115, 22, 0.3); }
        .badge-critical { background: rgba(239, 68, 68, 0.15); color: var(--color-critical); border: 1px solid rgba(239, 68, 68, 0.3); }

        /* Status Badges */
        .badge-open { background: rgba(59, 130, 246, 0.15); color: var(--color-open); border: 1px solid rgba(59, 130, 246, 0.3); }
        .badge-inreview { background: rgba(168, 85, 247, 0.15); color: var(--color-in-review); border: 1px solid rgba(168, 85, 247, 0.3); }
        .badge-investigating { background: rgba(249, 115, 22, 0.15); color: var(--color-investigating); border: 1px solid rgba(249, 115, 22, 0.3); }
        .badge-capa { background: rgba(234, 179, 8, 0.15); color: var(--color-capa); border: 1px solid rgba(234, 179, 8, 0.3); }
        .badge-verifying { background: rgba(6, 182, 212, 0.15); color: var(--color-verifying); border: 1px solid rgba(6, 182, 212, 0.3); }
        .badge-closed { background: rgba(34, 197, 94, 0.15); color: var(--color-closed); border: 1px solid rgba(34, 197, 94, 0.3); }

        .ticket-info {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.75rem;
        }

        .ticket-desc-preview {
            font-size: 0.85rem;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-top: 0.25rem;
        }

        /* Detail View Styles */
        .detail-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            text-align: center;
            padding: 2rem;
        }

        .detail-placeholder svg {
            width: 3.5rem;
            height: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.4;
        }

        .detail-wrapper {
            padding: 1.5rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        .detail-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .detail-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
        }

        .meta-item {
            font-size: 0.8rem;
        }

        .meta-label {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .meta-val {
            font-weight: 500;
            color: var(--text-primary);
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .section-header {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-secondary);
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
            padding-bottom: 0.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .image-container {
            width: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 16 / 9;
            margin-top: 0.5rem;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Timeline Audit Log */
        .timeline {
            position: relative;
            padding-left: 1.5rem;
            margin-top: 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.25rem;
            top: 0.25rem;
            bottom: 0.25rem;
            width: 2px;
            background: var(--border-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.45rem;
            top: 0.35rem;
            width: 0.6rem;
            height: 0.6rem;
            border-radius: 50%;
            background: var(--color-open);
            border: 2px solid var(--bg-card);
        }

        .timeline-item.status-change::before {
            background: var(--color-investigating);
        }

        .timeline-time {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-family: var(--font-mono);
        }

        .timeline-title {
            font-size: 0.8rem;
            font-weight: 700;
        }

        .timeline-desc {
            font-size: 0.75rem;
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.01);
            padding: 0.375rem 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.25rem;
            font-family: var(--font-mono);
            word-break: break-all;
        }

        /* Custom Toast notification */
        .toast-container {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1000;
        }

        .toast {
            background: #1a1d29;
            border-left: 4px solid var(--color-open);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            transform: translateY(50px);
            opacity: 0;
            animation: slideIn 0.3s forwards;
            border: 1px solid var(--border-color);
        }

        .toast.success { border-left-color: var(--color-closed); }
        .toast.error { border-left-color: var(--color-critical); }
        .toast.warning { border-left-color: var(--color-medium); }

        @keyframes slideIn {
            to { transform: translateY(0); opacity: 1; }
        }

        /* Analytics panel (Manager) */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            padding: 1rem;
            border-radius: 0.75rem;
            text-align: center;
        }

        .stat-num {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            font-family: var(--font-mono);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        /* Form sections wrapper for transitions */
        .action-pane {
            display: none;
        }

        .action-pane.active {
            display: block;
            animation: fadeIn 0.25s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Photo before/after selection simulator */
        .photo-picker {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .photo-option {
            border: 2px solid transparent;
            border-radius: 0.375rem;
            overflow: hidden;
            aspect-ratio: 4 / 3;
            cursor: pointer;
            position: relative;
            background: #111;
        }

        .photo-option img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-option.selected {
            border-color: var(--color-open);
        }

        .photo-option.selected::after {
            content: '✓';
            position: absolute;
            top: 0.25rem;
            right: 0.25rem;
            background: var(--color-open);
            color: white;
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: bold;
        }

        /* 5 Whys items */
        .why-container {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .why-item {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .why-num {
            font-family: var(--font-mono);
            font-weight: bold;
            color: var(--color-investigating);
            width: 1.5rem;
        }

        .why-input {
            flex-grow: 1;
        }

        /* ==================== RESPONSIVE LAYOUT ADJUSTMENTS ==================== */
        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }
            .sidebar {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
            .ticket-list {
                max-height: 400px;
            }
            .main-content {
                height: auto;
                min-height: 50vh;
            }
        }

        @media (max-width: 768px) {
            .filter-row {
                flex-direction: column;
            }
            .filter-row select {
                width: 100%;
            }
            header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1rem;
            }
            header > div:last-child {
                flex-direction: column;
                border-left: none;
                padding-left: 0;
                width: 100%;
            }
            header > div:last-child > div:first-child {
                border-right: none;
                padding-right: 0;
                text-align: center;
                margin-bottom: 0.5rem;
            }
        }
    </style>

</head>
<body>
    
    <div class="app-container">
        
        <!-- Header -->
        <header>
            <div class="logo-container">
                <div class="logo-icon">SM</div>
                <div class="logo-text">
                    <h1>SafeMine-HSE</h1>
                    <span>Sistem Pelaporan K3 Tambang</span>
                </div>
            </div>
            
            <!-- Dynamic Role Switcher Widget & User Profile -->
            <div class="role-switcher-box" style="display: flex; align-items: center; gap: 1rem;">
                <div style="text-align: right; font-size: 0.8rem; border-right: 1px solid var(--border-color); padding-right: 1rem; line-height: 1.3;">
                    <div style="font-weight: 700; color: white;">{{ auth()->user()->name }}</div>
                    <div style="color: var(--text-secondary); font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" class="btn" style="padding: 0.35rem 0.6rem; font-size: 0.75rem; background: var(--color-critical); border-radius: 0.375rem; border: none; color: white; cursor: pointer;">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Workspace -->
        <div class="dashboard-grid">
            
            <!-- Left Panel: Action Forms -->
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <span id="action-panel-title">Aksi Pekerja Lapangan</span>
                    </div>
                    <span id="role-badge" class="badge badge-open">Pekerja</span>
                </div>
                
                <div class="panel-body">
                    
                    <!-- 1. Form Pekerja Lapangan (Report Incident) -->
                    <div id="pane-pekerja" class="action-pane active">
                        <form id="form-report" onsubmit="submitReport(event)">
                            <div class="form-group">
                                <label for="report-location">Lokasi Area Tambang</label>
                                <select id="report-location" required>
                                    <option value="">Pilih Lokasi...</option>
                                    <!-- Seeded locations will load here -->
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="report-category">Kategori Laporan</label>
                                <select id="report-category" required>
                                    <option value="KTA">Kondisi Tidak Aman (KTA)</option>
                                    <option value="TTA">Tindakan Tidak Aman (TTA)</option>
                                    <option value="Near Miss">Hampir Celaka (Near Miss)</option>
                                    <option value="Accident">Kecelakaan (Accident)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="report-desc">Deskripsi Kronologi Kejadian</label>
                                <textarea id="report-desc" rows="4" placeholder="Jelaskan secara detail potensi bahaya yang ditemukan..." required minlength="10"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="report-action">Tindakan Langsung / Penanganan Awal (Opsional)</label>
                                <textarea id="report-action" rows="2" placeholder="Tindakan sementara yang sudah dilakukan di lapangan..."></textarea>
                            </div>

                            <div class="form-group">
                                <label>Unggah Foto Bukti Lapangan</label>
                                <div class="photo-picker" id="report-photo-picker" style="margin-bottom: 0.5rem;">
                                    <div class="photo-option selected" data-photo="uploads/incidents/before_road_landslide.jpg">
                                        <img src="https://images.unsplash.com/photo-1580901368919-7738efb4f072?w=150&auto=format&fit=crop&q=60" alt="Jalan longsor">
                                    </div>
                                    <div class="photo-option" data-photo="uploads/incidents/before_electrical_cable.jpg">
                                        <img src="https://images.unsplash.com/photo-1544724569-5f546fd6f2b5?w=150&auto=format&fit=crop&q=60" alt="Kabel terkelupas">
                                    </div>
                                    <div class="photo-option" data-photo="uploads/incidents/before_oil_spill.jpg">
                                        <img src="https://images.unsplash.com/photo-1605647540924-852290f6b0d5?w=150&auto=format&fit=crop&q=60" alt="Ceceran oli">
                                    </div>
                                </div>
                                <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('report-file-input').click()" style="width: 100%; font-size: 0.8rem; padding: 0.4rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary);">
                                        📷 Unggah & Kompres Foto Kamera/HP
                                    </button>
                                    <input type="file" id="report-file-input" accept="image/*" style="display: none;" onchange="handleImageUpload(this, 'before')">
                                    <div id="compressed-preview-container" style="display: none; background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 0.375rem; border: 1px dashed var(--border-color); text-align: center;">
                                        <img id="compressed-preview-img" style="max-height: 100px; border-radius: 0.25rem;" alt="Preview">
                                        <div id="compression-stats" style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;"></div>
                                    </div>
                                    <div id="location-status" style="font-size: 0.75rem; color: var(--text-secondary); font-style: italic;">
                                        📍 GPS: Koordinat area akan otomatis terdeteksi saat unggah foto.
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn" style="width: 100%;">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                                Kirim Laporan Insiden
                            </button>
                        </form>
                    </div>

                    <!-- 2. Form HSE Officer (Tinjauan Awal & Klasifikasi Severity) -->
                    <div id="pane-officer" class="action-pane">
                        <div id="officer-select-prompt" style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6M9 11h6"/></svg>
                            <p>Pilih tiket dengan status <strong>"Open"</strong> di daftar sebelah kanan untuk melakukan klasifikasi tingkat keparahan (severity).</p>
                        </div>
                        
                        <form id="form-classify" onsubmit="submitClassification(event)" style="display: none;">
                            <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; border: 1px solid var(--border-color);">
                                <p style="font-size: 0.8rem; color: var(--text-muted);">TIKET TERPILIH</p>
                                <p id="classify-ticket-label" style="font-family: var(--font-mono); font-weight: bold; color: var(--color-open);"></p>
                                <p id="classify-ticket-desc" style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;"></p>
                            </div>

                            <div class="form-group">
                                <label for="classify-severity">Klasifikasi Tingkat Keparahan (Severity)</label>
                                <select id="classify-severity" required>
                                    <option value="Low">Low (Risiko Rendah / Penanganan Cepat)</option>
                                    <option value="Medium">Medium (Risiko Sedang)</option>
                                    <option value="High">High (Risiko Tinggi)</option>
                                    <option value="Critical">Critical (Kritis - Membutuhkan Tindakan Darurat & Escalation)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn" style="width: 100%;">
                                Simpan Klasifikasi & Ubah ke In Review
                            </button>
                        </form>
                    </div>

                    <!-- 3. Form HSE Supervisor (RCA 5 Whys & Penugasan CAPA) -->
                    <div id="pane-supervisor" class="action-pane">
                        <div id="supervisor-select-prompt" style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                            <p>Pilih tiket dengan status <strong>"In Review"</strong> atau <strong>"Investigating"</strong> untuk melakukan investigasi RCA & menugaskan tindakan perbaikan (CAPA).</p>
                        </div>

                        <div id="supervisor-workspace" style="display: none;">
                            <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; border: 1px solid var(--border-color);">
                                <p style="font-size: 0.8rem; color: var(--text-muted);">TIKET TERPILIH</p>
                                <p id="super-ticket-label" style="font-family: var(--font-mono); font-weight: bold; color: var(--color-open);"></p>
                                <p id="super-ticket-status" style="font-size: 0.8rem; margin-top: 0.25rem;"></p>
                            </div>

                            <!-- Step A: Root Cause Analysis -->
                            <div id="supervisor-rca-box" style="margin-bottom: 1.5rem;">
                                <h3 style="font-size: 0.95rem; margin-bottom: 0.75rem; color: var(--color-investigating);">Langkah 1: Analisis Akar Masalah (5 Whys)</h3>
                                <form id="form-rca" onsubmit="submitRca(event)">
                                    <div class="why-container">
                                        <div class="why-item">
                                            <span class="why-num">Why 1</span>
                                            <input type="text" class="form-control why-input" id="why1" placeholder="Mengapa insiden terjadi?" required>
                                        </div>
                                        <div class="why-item">
                                            <span class="why-num">Why 2</span>
                                            <input type="text" class="form-control why-input" id="why2" placeholder="Mengapa hal itu terjadi?" required>
                                        </div>
                                        <div class="why-item">
                                            <span class="why-num">Why 3</span>
                                            <input type="text" class="form-control why-input" id="why3" placeholder="Mengapa hal itu terjadi?" required>
                                        </div>
                                        <div class="why-item">
                                            <span class="why-num">Why 4</span>
                                            <input type="text" class="form-control why-input" id="why4" placeholder="Mengapa hal itu terjadi?" required>
                                        </div>
                                        <div class="why-item">
                                            <span class="why-num">Why 5</span>
                                            <input type="text" class="form-control why-input" id="why5" placeholder="Mengapa hal itu terjadi?" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-secondary" style="width: 100%; margin-top: 1rem;">
                                        Simpan Analisis RCA & Mulai Investigasi
                                    </button>
                                </form>
                            </div>

                            <!-- Step B: Assign CAPA Task -->
                            <div id="supervisor-capa-box" style="display: none;">
                                <h3 style="font-size: 0.95rem; margin-bottom: 0.75rem; color: var(--color-capa);">Langkah 2: Penugasan Tindakan Perbaikan (CAPA)</h3>
                                <form id="form-capa" onsubmit="submitCapa(event)">
                                    <div class="form-group">
                                        <label for="capa-dept">Departemen Penanggung Jawab</label>
                                        <select id="capa-dept" required>
                                            <option value="">Pilih Departemen...</option>
                                            <!-- Seeded departments will load here -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="capa-desc">Detail Tindakan Perbaikan (SLA Kepatuhan)</label>
                                        <textarea id="capa-desc" rows="3" placeholder="Deskripsikan pekerjaan perbaikan yang wajib dikerjakan oleh departemen terkait..." required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="capa-due">Batas Waktu Penyelesaian (Due Date)</label>
                                        <input type="date" id="capa-due" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn" style="width: 100%;">
                                        Tugaskan CAPA & Kirim WhatsApp Alert
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Form PIC Departemen (Selesaikan Tugas CAPA) -->
                    <div id="pane-pic" class="action-pane">
                        <div id="pic-select-prompt" style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><path d="M9 12l2 2 4-4"/></svg>
                            <p>Pilih tiket dengan status <strong>"CAPA Progress"</strong> untuk melaporkan bukti perbaikan lapangan.</p>
                        </div>

                        <form id="form-complete-capa" onsubmit="submitCapaCompletion(event)" style="display: none;">
                            <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; border: 1px solid var(--border-color);">
                                <p style="font-size: 0.8rem; color: var(--text-muted);">TUGAS CAPA TERKAIT</p>
                                <p id="pic-task-label" style="font-family: var(--font-mono); font-weight: bold; color: var(--color-capa);"></p>
                                <p id="pic-task-desc" style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;"></p>
                            </div>

                            <div class="form-group">
                                <label for="pic-notes">Catatan & Dokumentasi Penyelesaian</label>
                                <textarea id="pic-notes" rows="3" placeholder="Jelaskan tindakan perbaikan permanen yang telah selesai dilakukan..." required minlength="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Unggah Foto Bukti Lapangan</label>
                                <div class="photo-picker" id="pic-photo-picker" style="margin-bottom: 0.5rem;">
                                    <div class="photo-option selected" data-photo="uploads/incidents/after_road_landslide.jpg">
                                        <img src="https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=150&auto=format&fit=crop&q=60" alt="Jalan selesai diperbaiki">
                                    </div>
                                    <div class="photo-option" data-photo="uploads/incidents/after_electrical_cable.jpg">
                                        <img src="https://images.unsplash.com/photo-1498084393753-b411b2d26b34?w=150&auto=format&fit=crop&q=60" alt="Kabel selesai diperbaiki">
                                    </div>
                                    <div class="photo-option" data-photo="uploads/incidents/after_oil_spill.jpg">
                                        <img src="https://images.unsplash.com/photo-1518364538800-6bcb3f25da49?w=150&auto=format&fit=crop&q=60" alt="Area bersih ceceran oli">
                                    </div>
                                </div>
                                <div style="margin-top: 0.5rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('pic-file-input').click()" style="width: 100%; font-size: 0.8rem; padding: 0.4rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary);">
                                        📷 Unggah & Kompres Foto Hasil Perbaikan
                                    </button>
                                    <input type="file" id="pic-file-input" accept="image/*" style="display: none;" onchange="handleImageUpload(this, 'after')">
                                    <div id="pic-compressed-preview-container" style="display: none; background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 0.375rem; border: 1px dashed var(--border-color); text-align: center;">
                                        <img id="pic-compressed-preview-img" style="max-height: 100px; border-radius: 0.25rem;" alt="Preview">
                                        <div id="pic-compression-stats" style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;"></div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success" style="width: 100%;">
                                Kirim Bukti Perbaikan ke HSE Manager
                            </button>
                        </form>
                    </div>

                    <!-- 5. Form HSE Manager (Verifikasi Akhir & Penutupan Tiket) -->
                    <div id="pane-manager" class="action-pane">
                        <div class="stats-box" style="margin-bottom: 1.5rem;">
                            <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.75rem;">Indikator Keselamatan Tambang (K3)</h3>
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-num" id="stat-ltifr" style="color: var(--color-critical);">0.00</div>
                                    <div class="stat-label">LTIFR</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-num" id="stat-trir" style="color: var(--color-high);">0.00</div>
                                    <div class="stat-label">TRIR</div>
                                </div>
                                <div class="stat-card" style="grid-column: span 2;">
                                    <div class="stat-num" id="stat-capa-rate" style="color: var(--color-closed);">100%</div>
                                    <div class="stat-label">Rasio Penyelesaian CAPA (SLA)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Ekspor Laporan Bulanan (PDF & Excel) -->
                        <div class="stats-box" style="margin-bottom: 1.5rem;">
                            <h3 style="font-size: 0.85rem; font-weight: 700; text-transform: uppercase; color: var(--text-secondary); margin-bottom: 0.75rem;">Ekspor Rekap Laporan Bulanan</h3>
                            <div style="display: flex; gap: 0.5rem; align-items: center; justify-content: space-between;">
                                <div style="flex-grow: 1; display: flex; gap: 0.5rem;">
                                    <select id="export-month" class="role-select" style="padding: 0.35rem 0.5rem; font-size: 0.8rem; background: var(--bg-main); border: 1px solid var(--border-color); color: white; border-radius: 0.375rem; width: 100%;">
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7" selected>Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                    <select id="export-year" class="role-select" style="padding: 0.35rem 0.5rem; font-size: 0.8rem; background: var(--bg-main); border: 1px solid var(--border-color); color: white; border-radius: 0.375rem; width: 100%;">
                                        <option value="2026" selected>2026</option>
                                        <option value="2025">2025</option>
                                    </select>
                                </div>
                                <div style="display: flex; gap: 0.5rem;">
                                    <button onclick="triggerExport('csv')" class="btn" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; background: var(--color-warning); display: flex; align-items: center; gap: 0.25rem;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                        CSV
                                    </button>
                                    <button onclick="triggerExport('pdf')" class="btn" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; background: var(--color-critical); display: flex; align-items: center; gap: 0.25rem;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                        PDF
                                    </button>
                                    <button onclick="triggerExport('excel')" class="btn" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; background: var(--color-closed); display: flex; align-items: center; gap: 0.25rem;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                        Excel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="manager-select-prompt" style="color: var(--text-secondary); text-align: center; padding: 1rem 0;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><polygon points="12 2 2 22 22 22"/><path d="M12 9v4M12 17h.01"/></svg>
                            <p>Pilih tiket dengan status <strong>"Verifying"</strong> untuk melakukan review akhir bukti foto sebelum menutup kasus.</p>
                        </div>

                        <form id="form-verify-close" onsubmit="submitVerification(event)" style="display: none;">
                            <div style="background: rgba(255,255,255,0.02); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.25rem; border: 1px solid var(--border-color);">
                                <p style="font-size: 0.8rem; color: var(--text-muted);">TIKET SIAP VERIFIKASI</p>
                                <p id="manager-ticket-label" style="font-family: var(--font-mono); font-weight: bold; color: var(--color-verifying);"></p>
                                <p id="manager-completion-notes" style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem;"></p>
                            </div>

                            <div class="form-group">
                                <label for="manager-action">Keputusan Verifikasi</label>
                                <select id="manager-action" onchange="toggleRejectionBox(this.value)" required>
                                    <option value="approve">Setujui & Tutup Tiket Insiden (Closed)</option>
                                    <option value="reject">Tolak Bukti & Minta Perbaikan Ulang</option>
                                </select>
                            </div>

                            <div class="form-group" id="rejection-box" style="display: none;">
                                <label for="manager-rejection-notes">Alasan Penolakan / Catatan Perbaikan</label>
                                <textarea id="manager-rejection-notes" rows="3" placeholder="Jelaskan bagian mana dari perbaikan yang belum memenuhi standar keselamatan..."></textarea>
                            </div>

                            <button type="submit" class="btn" style="width: 100%;">
                                Kirim Keputusan
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- Right Panel: Combined Tickets List and Selected Details -->
            <div class="panel">
                <div class="right-panel-split">
                    
                    <!-- Tickets Feed Section -->
                    <div class="ticket-list-sec">
                        <div class="panel-header" style="border-bottom: 1px solid var(--border-color); flex-direction: column; align-items: flex-start; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                                <span style="font-weight: 700; font-size: 0.95rem;">Daftar Tiket Pelaporan K3</span>
                                <button class="btn btn-secondary" onclick="loadIncidents()" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 0.375rem;">Refresh</button>
                            </div>
                            <div style="display: flex; gap: 0.5rem; width: 100%;">
                                <select id="filter-status" class="role-select" onchange="renderIncidentsList()" style="width: 50%; font-size: 0.75rem; padding: 0.25rem;">
                                    <option value="">Semua Status</option>
                                    <option value="Open">Open</option>
                                    <option value="In Review">In Review</option>
                                    <option value="Investigating">Investigating</option>
                                    <option value="CAPA Progress">CAPA Progress</option>
                                    <option value="Verifying">Verifying</option>
                                    <option value="Closed">Closed</option>
                                </select>
                                <select id="filter-category" class="role-select" onchange="renderIncidentsList()" style="width: 50%; font-size: 0.75rem; padding: 0.25rem;">
                                    <option value="">Semua Kategori</option>
                                    <option value="KTA">KTA</option>
                                    <option value="TTA">TTA</option>
                                    <option value="Near Miss">Near Miss</option>
                                    <option value="Accident">Accident</option>
                                </select>
                            </div>
                        </div>
                        <div class="panel-body" id="incidents-list-container" style="padding: 1rem;">
                            <!-- Dynamic Incident Cards will render here -->
                            <div style="text-align: center; color: var(--text-muted); margin-top: 2rem;">Loading tickets...</div>
                        </div>
                    </div>

                    <!-- Selected Ticket Details Section -->
                    <div class="ticket-detail-sec" id="ticket-detail-container">
                        <div class="detail-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <h3>Detail Tiket Insiden</h3>
                            <p style="font-size: 0.8rem; margin-top: 0.25rem;">Pilih salah satu tiket di daftar sebelah kiri untuk melihat detail, analisis akar masalah (RCA), dan audit trail perubahannya.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- Custom Toasts container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        let selectedIncidentId = null;
        let selectedTaskId = null;
        let allIncidents = [];
        const loggedInRole = "{{ auth()->user()->role }}"; // "pekerja", "officer", "manager"
        let activeRole = loggedInRole; // Maps 1:1 if needed, or adjust below

        // For the sake of the existing demo UI logic that expects 5 phases:
        // We will map 'officer' to handle both officer & supervisor tasks if needed,
        // or just rely on the existing backend role logic for UI rendering.
        // The prompt asks to ensure logical flow. Let's map exactly based on role.

        // Set AJAX Headers with CSRF Token and Simulated Role ID
        const getHeaders = () => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            };
        };

        // App Initializer
        window.addEventListener('DOMContentLoaded', () => {
            loadDropdownData();
            loadIncidents();

            switchRole(activeRole);

            // Register PWA Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    console.log('SafeMine HSE Service Worker registered, scope:', reg.scope);
                }).catch(err => {
                    console.warn('SafeMine HSE Service Worker registration failed:', err);
                });
            }
        });

        // Show toast notification helper
        const showToast = (message, type = 'success') => {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <div style="font-size: 0.85rem; font-weight: 600;">${message}</div>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s reverse forwards';
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 4000);
        };

        // Switch role simulation
        const switchRole = (role) => {
            activeRole = role;
            
            // Highlight active role badge
            const badge = document.getElementById('role-badge');
            badge.innerText = role.toUpperCase();
            badge.className = 'badge';
            if (role === 'pekerja') badge.classList.add('badge-open');
            if (role === 'officer') badge.classList.add('badge-inreview');
            if (role === 'supervisor') badge.classList.add('badge-investigating');
            if (role === 'pic') badge.classList.add('badge-capa');
            if (role === 'manager') badge.classList.add('badge-verifying');

            // Switch Title
            const title = document.getElementById('action-panel-title');
            title.innerText = 'Aksi ' + {
                'pekerja': 'Pekerja Lapangan',
                'officer': 'HSE Officer',
                'supervisor': 'HSE Supervisor',
                'pic': 'Departemen PIC',
                'manager': 'HSE Manager'
            }[role];

            // Toggle Panes
            document.querySelectorAll('.action-pane').forEach(pane => pane.classList.remove('active'));
            document.getElementById(`pane-${role}`).classList.add('active');

            // Reset action prompts and form views based on selected ticket
            updateActionFormsFromSelection();
        };

        // Load locations & departments into select dropdowns
        const loadDropdownData = () => {
            fetch('/api/locations', { headers: getHeaders() })
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('report-location');
                    select.innerHTML = '<option value="">Pilih Lokasi...</option>';
                    data.forEach(loc => {
                        select.innerHTML += `<option value="${loc.id}">${loc.site_name} (${loc.area_type})</option>`;
                    });
                });

            fetch('/api/departments', { headers: getHeaders() })
                .then(res => res.json())
                .then(data => {
                    const select = document.getElementById('capa-dept');
                    select.innerHTML = '<option value="">Pilih Departemen...</option>';
                    data.forEach(dept => {
                        select.innerHTML += `<option value="${dept.id}">${dept.department_name} (${dept.dept_code})</option>`;
                    });
                });
        };

        // Load incidents list from API
        const loadIncidents = () => {
            fetch('/api/incidents', { headers: getHeaders() })
                .then(res => res.json())
                .then(data => {
                    allIncidents = data;
                    renderIncidentsList();
                    updateManagerStats();
                })
                .catch(err => {
                    showToast('Gagal memuat daftar insiden K3', 'error');
                });
        };

        // Render incident cards
        const renderIncidentsList = () => {
            const container = document.getElementById('incidents-list-container');
            container.innerHTML = '';

            const statusFilter = document.getElementById('filter-status').value;
            const categoryFilter = document.getElementById('filter-category').value;

            let filteredIncidents = allIncidents;

            if (statusFilter) {
                filteredIncidents = filteredIncidents.filter(inc => inc.status === statusFilter);
            }
            if (categoryFilter) {
                filteredIncidents = filteredIncidents.filter(inc => inc.category === categoryFilter);
            }

            if (filteredIncidents.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: var(--text-muted); padding: 2rem;">Belum ada laporan insiden K3 masuk.</div>';
                return;
            }

            filteredIncidents.forEach(inc => {
                const card = document.createElement('div');
                card.className = `ticket-card ${selectedIncidentId === inc.id ? 'active' : ''}`;
                card.onclick = () => selectIncident(inc.id);

                const severityBadge = inc.severity ? `<span class="badge badge-${inc.severity.toLowerCase()}">${inc.severity}</span>` : '';
                const dateText = new Date(inc.incident_date).toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });

                // Find description preview
                const desc = inc.details ? inc.details.description : 'Tidak ada keterangan.';

                card.innerHTML = `
                    <div class="ticket-card-header">
                        <span class="ticket-id">${inc.ticket_number}</span>
                        <span class="badge badge-${inc.status.replace(/\s+/g, '').toLowerCase()}">${inc.status}</span>
                    </div>
                    <div class="ticket-desc-preview">${desc}</div>
                    <div class="ticket-info">
                        <span>📍 ${inc.location.site_name}</span>
                        <span>⚠️ ${inc.category}</span>
                        ${severityBadge}
                        <span>📅 ${dateText}</span>
                    </div>
                `;
                container.appendChild(card);
            });
        };

        // Update analytics dashboard inside manager pane
        const updateManagerStats = () => {
            if (!allIncidents.length) {
                document.getElementById('stat-ltifr').innerText = '0.00';
                document.getElementById('stat-trir').innerText = '0.00';
                document.getElementById('stat-capa-rate').innerText = '100%';
                return;
            }

            // Calculate CAPA rate
            const closed = allIncidents.filter(inc => inc.status === 'Closed').length;
            const openCapa = allIncidents.filter(inc => ['CAPA Progress', 'Verifying', 'Closed'].includes(inc.status)).length;
            const rateElement = document.getElementById('stat-capa-rate');
            if (openCapa > 0) {
                const rate = Math.round((closed / openCapa) * 100);
                rateElement.innerText = `${rate}%`;
            } else {
                rateElement.innerText = '100%';
            }

            // Calculate LTIFR and TRIR
            const manHours = 1250000;
            const lti = allIncidents.filter(inc => inc.category === 'Accident' && ['High', 'Critical'].includes(inc.severity)).length;
            const recordable = allIncidents.filter(inc => ['Accident', 'Near Miss'].includes(inc.category)).length;

            const ltifr = ((lti * 1000000) / manHours).toFixed(2);
            const trir = ((recordable * 1000000) / manHours).toFixed(2);

            document.getElementById('stat-ltifr').innerText = ltifr;
            document.getElementById('stat-trir').innerText = trir;
        };

        // Select an incident ticket to show details and active form contexts
        const selectIncident = (id) => {
            selectedIncidentId = id;
            renderIncidentsList();
            
            // Fetch detailed incident record
            fetch(`/api/incidents/${id}`, { headers: getHeaders() })
                .then(res => res.json())
                .then(inc => {
                    renderIncidentDetails(inc);
                    updateActionFormsFromSelection(inc);
                });
        };

        // Render incident detail view and timeline
        const renderIncidentDetails = (inc) => {
            const container = document.getElementById('ticket-detail-container');
            
            const severityBadge = inc.severity ? `<span class="badge badge-${inc.severity.toLowerCase()}" style="margin-left: 0.5rem;">${inc.severity}</span>` : '';
            const statusBadge = `<span class="badge badge-${inc.status.replace(/\s+/g, '').toLowerCase()}" style="margin-left: 0.5rem;">${inc.status}</span>`;
            
            // Format dates
            const dateStr = new Date(inc.incident_date).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });
            
            // Detail contents
            const description = inc.details ? inc.details.description : '-';
            const initialAction = inc.details && inc.details.initial_action ? inc.details.initial_action : '-';
            const rootCause = inc.details && inc.details.root_cause ? inc.details.root_cause : 'Belum dilakukan investigasi.';
            
            // Photo Before
            const photoBeforeImg = inc.details && inc.details.photo_before ? 
                `<div class="image-container"><img src="${getMockPhotoUrl(inc.details.photo_before)}" alt="Sebelum Perbaikan"></div>` : '-';
            
            // CAPA Tasks
            let capaSectionContent = '<p style="font-size: 0.85rem; color: var(--text-muted);">Belum ada penugasan CAPA.</p>';
            if (inc.action_tasks && inc.action_tasks.length > 0) {
                capaSectionContent = inc.action_tasks.map(task => {
                    const taskStatus = `<span class="badge badge-${task.status.toLowerCase()}" style="font-size: 0.65rem;">${task.status}</span>`;
                    const dateText = new Date(task.due_date).toLocaleDateString('id-ID', { dateStyle: 'short' });
                    const photoAfter = task.photo_after ? 
                        `<div class="image-container" style="margin-top: 0.5rem;"><img src="${getMockPhotoUrl(task.photo_after)}" alt="Bukti Perbaikan"></div>` : '';
                    
                    return `
                        <div style="background: rgba(255,255,255,0.02); padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 0.5rem; border: 1px solid var(--border-color);">
                            <div style="display: flex; justify-content: space-between; font-size: 0.8rem; font-weight: bold; margin-bottom: 0.25rem;">
                                <span>PIC: Dept ${task.department.dept_code}</span>
                                ${taskStatus}
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-secondary);">${task.task_description}</p>
                            <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem;">Deadline: ${dateText}</div>
                            ${task.completion_notes ? `<div style="font-size: 0.75rem; color: var(--color-closed); margin-top: 0.25rem;"><strong>Catatan PIC:</strong> ${task.completion_notes}</div>` : ''}
                            ${photoAfter}
                        </div>
                    `;
                }).join('');
            }

            // Timeline Audit Log
            let timelineContent = '<p style="font-size: 0.85rem; color: var(--text-muted);">Belum ada timeline audit trail.</p>';
            if (inc.audit_logs && inc.audit_logs.length > 0) {
                timelineContent = `<div class="timeline">` + inc.audit_logs.map(log => {
                    const logDate = new Date(log.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
                    const oldVals = log.old_values ? JSON.stringify(log.old_values) : '';
                    const newVals = log.new_values ? JSON.stringify(log.new_values) : '';
                    
                    return `
                        <div class="timeline-item status-change">
                            <div class="timeline-time">${logDate}</div>
                            <div class="timeline-title">${log.action} oleh ${log.user.name} (${log.user.department ? log.user.department.dept_code : 'SYS'})</div>
                            <div class="timeline-desc">
                                Status: ${log.old_values && log.old_values.status ? log.old_values.status : 'N/A'} ➜ ${log.new_values && log.new_values.status ? log.new_values.status : 'N/A'}
                                ${log.new_values && log.new_values.severity ? `<br>Severity: ${log.new_values.severity}` : ''}
                                <br><span style="color: var(--text-muted); font-size: 0.65rem;">IP: ${log.ip_address || 'N/A'}</span>
                            </div>
                        </div>
                    `;
                }).join('') + `</div>`;
            }

            container.innerHTML = `
                <div class="detail-wrapper">
                    <div class="detail-title">Detail Insiden K3</div>
                    <div style="font-family: var(--font-mono); font-size: 0.9rem; font-weight: bold; color: var(--color-open); margin-bottom: 1rem;">
                        ${inc.ticket_number}
                        ${severityBadge}
                        ${statusBadge}
                    </div>

                    <div class="detail-meta-grid">
                        <div class="meta-item">
                            <div class="meta-label">Area Lokasi</div>
                            <div class="meta-val">${inc.location.site_name} (${inc.location.area_type})</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Kategori K3</div>
                            <div class="meta-val">${inc.category}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Tanggal Pelaporan</div>
                            <div class="meta-val">${dateStr}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Pelapor</div>
                            <div class="meta-val">${inc.reporter.name} (${inc.reporter.department ? inc.reporter.department.dept_code : 'UMUM'})</div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Deskripsi Temuan Potensi Bahaya</div>
                        <p style="font-size: 0.85rem; color: var(--text-secondary); white-space: pre-wrap;">${description}</p>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Penanganan Awal (Mitigasi Cepat)</div>
                        <p style="font-size: 0.85rem; color: var(--text-secondary); white-space: pre-wrap;">${initialAction}</p>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Foto Sebelum Perbaikan</div>
                        ${photoBeforeImg}
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Analisis Akar Masalah (RCA 5 Whys)</div>
                        <p style="font-size: 0.85rem; color: var(--text-secondary); background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 0.5rem; border: 1px solid var(--border-color); font-style: italic;">
                            ${rootCause}
                        </p>
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Tindakan Perbaikan & Pencegahan (CAPA)</div>
                        ${capaSectionContent}
                    </div>

                    <div class="detail-section">
                        <div class="section-header">Audit Trail & Riwayat Tiket</div>
                        ${timelineContent}
                    </div>
                </div>
            `;
        };

        // Map mockup photos to real images from Unsplash so they look gorgeous
        const getMockPhotoUrl = (path) => {
            if (path.includes('before_road_landslide.jpg')) return 'https://images.unsplash.com/photo-1580901368919-7738efb4f072?w=500&auto=format&fit=crop&q=80';
            if (path.includes('before_electrical_cable.jpg')) return 'https://images.unsplash.com/photo-1544724569-5f546fd6f2b5?w=500&auto=format&fit=crop&q=80';
            if (path.includes('before_oil_spill.jpg')) return 'https://images.unsplash.com/photo-1605647540924-852290f6b0d5?w=500&auto=format&fit=crop&q=80';
            
            if (path.includes('after_road_landslide.jpg')) return 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=500&auto=format&fit=crop&q=80';
            if (path.includes('after_electrical_cable.jpg')) return 'https://images.unsplash.com/photo-1498084393753-b411b2d26b34?w=500&auto=format&fit=crop&q=80';
            if (path.includes('after_oil_spill.jpg')) return 'https://images.unsplash.com/photo-1518364538800-6bcb3f25da49?w=500&auto=format&fit=crop&q=80';
            
            return 'https://images.unsplash.com/photo-1590069261209-f8e9b8642343?w=500&auto=format&fit=crop&q=80';
        };

        // Photo Picker click listeners
        document.querySelectorAll('#report-photo-picker .photo-option').forEach(opt => {
            opt.addEventListener('click', () => {
                document.querySelectorAll('#report-photo-picker .photo-option').forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
            });
        });

        document.querySelectorAll('#pic-photo-picker .photo-option').forEach(opt => {
            opt.addEventListener('click', () => {
                document.querySelectorAll('#pic-photo-picker .photo-option').forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
            });
        });

        // Hide/Show action form elements depending on the selected incident status and active role
        const updateActionFormsFromSelection = (inc = null) => {
            if (!inc && selectedIncidentId) {
                inc = allIncidents.find(i => i.id === selectedIncidentId);
            }

            // Reset Forms
            document.getElementById('form-classify').style.display = 'none';
            document.getElementById('officer-select-prompt').style.display = 'block';
            
            document.getElementById('supervisor-workspace').style.display = 'none';
            document.getElementById('supervisor-select-prompt').style.display = 'block';
            
            document.getElementById('form-complete-capa').style.display = 'none';
            document.getElementById('pic-select-prompt').style.display = 'block';
            
            document.getElementById('form-verify-close').style.display = 'none';
            document.getElementById('manager-select-prompt').style.display = 'block';

            if (!inc) return;

            // Worker: Form is always open to create new incidents
            
            // HSE Officer (Classify severity)
            if (activeRole === 'officer') {
                if (inc.status === 'Open') {
                    document.getElementById('officer-select-prompt').style.display = 'none';
                    document.getElementById('form-classify').style.display = 'block';
                    document.getElementById('classify-ticket-label').innerText = inc.ticket_number;
                    document.getElementById('classify-ticket-desc').innerText = inc.details ? inc.details.description : '';
                } else {
                    document.getElementById('officer-select-prompt').innerHTML = `
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        <p>Tiket <strong>${inc.ticket_number}</strong> sudah melewati fase tinjauan awal (Status: ${inc.status}). Pilih tiket berstatus "Open" lainnya.</p>
                    `;
                }
            }

            // HSE Supervisor (RCA & Assign CAPA)
            if (activeRole === 'supervisor') {
                if (inc.status === 'In Review' || inc.status === 'Investigating') {
                    document.getElementById('supervisor-select-prompt').style.display = 'none';
                    document.getElementById('supervisor-workspace').style.display = 'block';
                    document.getElementById('super-ticket-label').innerText = inc.ticket_number;
                    
                    const statusText = inc.status === 'In Review' ? 'Fase: Menunggu Investigasi (RCA)' : 'Fase: Menunggu Tindakan CAPA';
                    document.getElementById('super-ticket-status').innerText = statusText;
                    document.getElementById('super-ticket-status').style.color = inc.status === 'In Review' ? 'var(--color-in-review)' : 'var(--color-investigating)';

                    if (inc.status === 'In Review') {
                        document.getElementById('supervisor-rca-box').style.display = 'block';
                        document.getElementById('supervisor-capa-box').style.display = 'none';
                    } else {
                        document.getElementById('supervisor-rca-box').style.display = 'none';
                        document.getElementById('supervisor-capa-box').style.display = 'block';
                    }
                } else {
                    document.getElementById('supervisor-select-prompt').innerHTML = `
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        <p>Tiket <strong>${inc.ticket_number}</strong> bermasalah untuk fase investigasi (Status: ${inc.status}).</p>
                    `;
                }
            }

            // PIC Department (Complete CAPA Task)
            if (activeRole === 'pic') {
                if (inc.status === 'CAPA Progress') {
                    // Find pending task
                    const pendingTask = inc.action_tasks ? inc.action_tasks.find(t => t.status === 'Pending') : null;
                    if (pendingTask) {
                        selectedTaskId = pendingTask.id;
                        document.getElementById('pic-select-prompt').style.display = 'none';
                        document.getElementById('form-complete-capa').style.display = 'block';
                        document.getElementById('pic-task-label').innerText = `Tugas SLA: Dept ${pendingTask.department.dept_code}`;
                        document.getElementById('pic-task-desc').innerText = pendingTask.task_description;
                    } else {
                        document.getElementById('pic-select-prompt').innerHTML = `
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                            <p>Tidak ada tugas pending aktif untuk tiket <strong>${inc.ticket_number}</strong>.</p>
                        `;
                    }
                } else {
                    document.getElementById('pic-select-prompt').innerHTML = `
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.3; margin-bottom: 1rem;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                        <p>Tiket <strong>${inc.ticket_number}</strong> sedang tidak dalam fase pengerjaan CAPA (Status: ${inc.status}).</p>
                    `;
                }
            }

            // HSE Manager (Verify & Close)
            if (activeRole === 'manager') {
                if (inc.status === 'Verifying') {
                    // Get latest completed task notes
                    const lastCapa = inc.action_tasks ? inc.action_tasks.filter(t => t.status === 'Completed').pop() : null;
                    const notes = lastCapa ? lastCapa.completion_notes : 'Tidak ada catatan.';
                    
                    document.getElementById('manager-select-prompt').style.display = 'none';
                    document.getElementById('form-verify-close').style.display = 'block';
                    document.getElementById('manager-ticket-label').innerText = inc.ticket_number;
                    document.getElementById('manager-completion-notes').innerText = `Catatan Perbaikan: "${notes}"`;
                } else {
                    document.getElementById('manager-select-prompt').style.display = 'block';
                }
            }
        };

        // Submit Phase 1: Report Incident (Worker)
        const submitReport = (e) => {
            e.preventDefault();
            
            const selectedPhotoOpt = document.querySelector('#report-photo-picker .photo-option.selected');
            let photoBefore = window.uploadedPhotoDataUrl;
            if (!photoBefore && selectedPhotoOpt) {
                photoBefore = selectedPhotoOpt.getAttribute('data-photo');
            }
            if (!photoBefore) {
                photoBefore = 'uploads/incidents/before_road_landslide.jpg';
            }

            let descriptionText = document.getElementById('report-desc').value;
            if (window.capturedCoordinates) {
                descriptionText += `\n\n[GPS Lokasi Pelapor: Lat ${window.capturedCoordinates.lat}, Long ${window.capturedCoordinates.lng}]`;
            }

            const data = {
                location_id: document.getElementById('report-location').value,
                category: document.getElementById('report-category').value,
                description: descriptionText,
                initial_action: document.getElementById('report-action').value,
                photo_before: photoBefore,
                incident_date: new Date().toISOString().slice(0, 19).replace('T', ' ')
            };

            if (!navigator.onLine) {
                // Save offline
                let offlineData = JSON.parse(localStorage.getItem('offline_incidents') || '[]');
                offlineData.push(data);
                localStorage.setItem('offline_incidents', JSON.stringify(offlineData));

                showToast('Anda sedang offline. Laporan disimpan dan akan dikirim saat koneksi pulih.', 'warning');
                document.getElementById('form-report').reset();
                document.getElementById('compressed-preview-container').style.display = 'none';
                window.uploadedPhotoDataUrl = null;
            } else {
                fetch('/api/incidents', {
                    method: 'POST',
                    headers: getHeaders(),
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.errors) {
                        showToast(Object.values(res.errors)[0][0], 'error');
                    } else {
                        showToast('Laporan K3 berhasil dikirim ke HSE Officer!');
                        document.getElementById('form-report').reset();
                        document.getElementById('compressed-preview-container').style.display = 'none';
                        document.getElementById('location-status').innerText = '📍 GPS: Koordinat area akan otomatis terdeteksi saat unggah foto.';
                        window.uploadedPhotoDataUrl = null;
                        window.capturedCoordinates = null;
                        loadIncidents();
                    }
                })
                .catch(err => {
                    showToast('Gagal mengirimkan laporan K3', 'error');
                });
            }
        };

        // Sync Offline Data
        window.addEventListener('online', () => {
            let offlineData = JSON.parse(localStorage.getItem('offline_incidents') || '[]');
            if (offlineData.length > 0) {
                showToast(`Menyinkronkan ${offlineData.length} laporan offline...`);
                Promise.all(offlineData.map(data => {
                    return fetch('/api/incidents', {
                        method: 'POST',
                        headers: getHeaders(),
                        body: JSON.stringify(data)
                    });
                })).then(() => {
                    localStorage.removeItem('offline_incidents');
                    showToast('Sinkronisasi selesai!');
                    loadIncidents();
                });
            }
        });

        // Submit Phase 2: Classify (HSE Officer)
        const submitClassification = (e) => {
            e.preventDefault();
            if (!selectedIncidentId) return;

            const severity = document.getElementById('classify-severity').value;
            fetch(`/api/incidents/${selectedIncidentId}/review`, {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify({ severity })
            })
            .then(res => res.json())
            .then(res => {
                if (res.errors) {
                    showToast(Object.values(res.errors)[0][0], 'error');
                } else {
                    showToast('Klasifikasi tingkat keparahan berhasil disimpan!');
                    loadIncidents();
                    setTimeout(() => selectIncident(selectedIncidentId), 200);
                }
            })
            .catch(err => {
                showToast('Gagal memproses klasifikasi', 'error');
            });
        };

        // Submit Phase 3: RCA 5 Whys (HSE Supervisor)
        const submitRca = (e) => {
            e.preventDefault();
            if (!selectedIncidentId) return;

            const why1 = document.getElementById('why1').value;
            const why2 = document.getElementById('why2').value;
            const why3 = document.getElementById('why3').value;
            const why4 = document.getElementById('why4').value;
            const why5 = document.getElementById('why5').value;

            const fullRca = `Why 1: ${why1}\nWhy 2: ${why2}\nWhy 3: ${why3}\nWhy 4: ${why4}\nWhy 5: ${why5}`;

            fetch(`/api/incidents/${selectedIncidentId}/investigate`, {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify({ root_cause: fullRca })
            })
            .then(res => res.json())
            .then(res => {
                if (res.errors) {
                    showToast(Object.values(res.errors)[0][0], 'error');
                } else {
                    showToast('Analisis RCA 5 Whys berhasil disimpan!');
                    document.getElementById('form-rca').reset();
                    loadIncidents();
                    setTimeout(() => selectIncident(selectedIncidentId), 200);
                }
            })
            .catch(err => {
                showToast('Gagal memproses investigasi RCA', 'error');
            });
        };

        // Submit Phase 4: Assign CAPA (HSE Supervisor)
        const submitCapa = (e) => {
            e.preventDefault();
            if (!selectedIncidentId) return;

            const data = {
                assigned_department_id: document.getElementById('capa-dept').value,
                task_description: document.getElementById('capa-desc').value,
                due_date: document.getElementById('capa-due').value
            };

            fetch(`/api/incidents/${selectedIncidentId}/capa`, {
                method: 'POST',
                headers: getHeaders(),
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.errors) {
                    showToast(Object.values(res.errors)[0][0], 'error');
                } else {
                    showToast('Tugas perbaikan CAPA berhasil ditugaskan & notifikasi dikirim!');
                    document.getElementById('form-capa').reset();
                    loadIncidents();
                    setTimeout(() => selectIncident(selectedIncidentId), 200);
                }
            })
            .catch(err => {
                showToast('Gagal menetapkan tugas CAPA', 'error');
            });
        };

        // Submit Phase 4 Part 2: Complete CAPA (PIC Department)
        const submitCapaCompletion = (e) => {
            e.preventDefault();
            if (!selectedTaskId) return;

            const selectedPhotoOpt = document.querySelector('#pic-photo-picker .photo-option.selected');
            let photoAfter = window.uploadedCapaPhotoDataUrl;
            if (!photoAfter && selectedPhotoOpt) {
                photoAfter = selectedPhotoOpt.getAttribute('data-photo');
            }
            if (!photoAfter) {
                photoAfter = 'uploads/incidents/after_road_landslide.jpg';
            }

            const data = {
                completion_notes: document.getElementById('pic-notes').value,
                photo_after: photoAfter
            };

            fetch(`/api/incidents/capa/${selectedTaskId}/complete`, {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.errors) {
                    showToast(Object.values(res.errors)[0][0], 'error');
                } else {
                    showToast('Bukti pekerjaan perbaikan berhasil diunggah ke HSE Manager!');
                    document.getElementById('form-complete-capa').reset();
                    loadIncidents();
                    setTimeout(() => selectIncident(selectedIncidentId), 200);
                }
            })
            .catch(err => {
                showToast('Gagal memproses penyelesaian tugas', 'error');
            });
        };

        // Toggle rejection notes box in HSE Manager view
        const toggleRejectionBox = (val) => {
            const box = document.getElementById('rejection-box');
            if (val === 'reject') {
                box.style.display = 'block';
                document.getElementById('manager-rejection-notes').setAttribute('required', 'required');
            } else {
                box.style.display = 'none';
                document.getElementById('manager-rejection-notes').removeAttribute('required');
            }
        };

        // Submit Phase 5: Verification (HSE Manager)
        const submitVerification = (e) => {
            e.preventDefault();
            if (!selectedIncidentId) return;

            const action = document.getElementById('manager-action').value;
            const notes = document.getElementById('manager-rejection-notes').value;

            const data = {
                action: action,
                rejection_notes: notes
            };

            fetch(`/api/incidents/${selectedIncidentId}/close`, {
                method: 'PUT',
                headers: getHeaders(),
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.errors) {
                    showToast(Object.values(res.errors)[0][0], 'error');
                } else {
                    if (action === 'approve') {
                        showToast('Tiket insiden K3 disetujui & resmi DITUTUP!');
                    } else {
                        showToast('Bukti ditolak. Tiket dikembalikan ke departemen terkait.', 'warning');
                    }
                    document.getElementById('form-verify-close').reset();
                    document.getElementById('rejection-box').style.display = 'none';
                    loadIncidents();
                    setTimeout(() => selectIncident(selectedIncidentId), 200);
                }
            })
            .catch(err => {
                showToast('Gagal memproses verifikasi akhir', 'error');
            });
        };

        // Export report handler
        const triggerExport = (type) => {
            const month = document.getElementById('export-month').value;
            const year = document.getElementById('export-year').value;
            window.open(`/export/${type}?month=${month}&year=${year}`, '_blank');
        };

        // Geolocation Capture
        const captureLocation = () => {
            const locStatus = document.getElementById('location-status');
            if (navigator.geolocation) {
                locStatus.innerText = 'Mengambil koordinat GPS...';
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude.toFixed(6);
                        const lng = position.coords.longitude.toFixed(6);
                        locStatus.innerHTML = `📍 GPS Koordinat: <span style="font-family: monospace; font-weight: bold; color: var(--color-open);">${lat}, ${lng}</span>`;
                        window.capturedCoordinates = { lat, lng };
                    },
                    (error) => {
                        locStatus.innerHTML = `<span style="color: var(--color-critical);">📍 GPS Gagal (Pastikan izin lokasi diberikan)</span>`;
                        window.capturedCoordinates = null;
                    }
                );
            }
        };

        // Client-side Image Compression using HTML5 Canvas
        const handleImageUpload = (input, target) => {
            const file = input.files[0];
            if (!file) return;

            const originalSize = (file.size / 1024).toFixed(1) + ' KB';
            const reader = new FileReader();

            reader.onload = (e) => {
                const img = new Image();
                img.src = e.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const max_width = 800;
                    const max_height = 600;
                    let width = img.width;
                    let height = img.height;

                    if (width > height) {
                        if (width > max_width) {
                            height *= max_width / width;
                            width = max_width;
                        }
                    } else {
                        if (height > max_height) {
                            width *= max_height / height;
                            height = max_height;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    // Compress to JPEG with 0.7 quality
                    const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);
                    const head = 'data:image/jpeg;base64,';
                    const compressedSizeB = Math.round((compressedDataUrl.length - head.length) * 3/4);
                    const compressedSize = (compressedSizeB / 1024).toFixed(1) + ' KB';
                    const savings = Math.round((1 - (compressedSizeB / file.size)) * 100);

                    if (target === 'before') {
                        document.getElementById('compressed-preview-container').style.display = 'block';
                        document.getElementById('compressed-preview-img').src = compressedDataUrl;
                        document.getElementById('compression-stats').innerHTML = `
                            <strong>Ukuran Asli:</strong> ${originalSize} | 
                            <strong>Terkompresi:</strong> ${compressedSize} (<span style="color: var(--color-closed); font-weight: bold;">Hemat ${savings}%</span>)
                        `;
                        window.uploadedPhotoDataUrl = compressedDataUrl;
                        captureLocation();
                    } else {
                        document.getElementById('pic-compressed-preview-container').style.display = 'block';
                        document.getElementById('pic-compressed-preview-img').src = compressedDataUrl;
                        document.getElementById('pic-compression-stats').innerHTML = `
                            <strong>Ukuran Asli:</strong> ${originalSize} | 
                            <strong>Terkompresi:</strong> ${compressedSize} (<span style="color: var(--color-closed); font-weight: bold;">Hemat ${savings}%</span>)
                        `;
                        window.uploadedCapaPhotoDataUrl = compressedDataUrl;
                    }
                };
            };
            reader.readAsDataURL(file);
        };
    </script>
</body>
</html>
