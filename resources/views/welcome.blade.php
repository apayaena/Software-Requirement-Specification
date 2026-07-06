<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeMine-HSE - Sistem Pelaporan K3 Tambang</title>
    <!-- Google Fonts: Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-main: #07080d;
            --bg-card: #0f111a;
            --bg-card-hover: #141724;
            --border-color: rgba(255, 255, 255, 0.06);
            --border-color-glow: rgba(255, 90, 31, 0.15);
            --color-primary: #ff5a1f;
            --color-primary-hover: #e04e17;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
            --font-outfit: 'Outfit', sans-serif;
            --font-inter: 'Inter', sans-serif;
            --shadow-premium: 0 20px 40px rgba(0, 0, 0, 0.6);
            --shadow-glow: 0 0 30px rgba(255, 90, 31, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-inter);
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(255, 90, 31, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.03) 0%, transparent 40%);
            color: var(--text-primary);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header Navigation */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(7, 8, 13, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            transition: all 0.3s;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: white;
            z-index: 1001;
        }

        .logo-icon-svg {
            width: 2.5rem;
            height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            filter: drop-shadow(0 4px 12px rgba(255, 90, 31, 0.35));
        }

        .logo-text h1 {
            font-family: var(--font-outfit);
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1;
        }

        .logo-text span {
            font-size: 0.65rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Desktop Navigation Links */
        nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            text-decoration: none;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
            cursor: pointer;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-primary);
            transition: width 0.2s;
            border-radius: 2px;
        }

        .nav-link.active::after, .nav-link:hover::after {
            width: 100%;
        }

        .btn-cta {
            background: var(--color-primary);
            color: white;
            text-decoration: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid transparent;
            box-shadow: 0 4px 12px rgba(255, 90, 31, 0.25);
            font-family: var(--font-outfit);
        }

        .btn-cta:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(255, 90, 31, 0.4);
        }

        /* Mobile Hamburger Menu Trigger */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            z-index: 1001;
        }

        /* Main Content Container */
        main {
            max-width: 1200px;
            margin: 7rem auto 4rem auto;
            padding: 0 2rem;
        }

        /* Pages / Tab Panels */
        .page-panel {
            display: none;
            animation: fadeIn 0.4s ease-out forwards;
        }

        .page-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==================== PAGE: BERANDA ==================== */
        .hero-section {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 4rem;
            align-items: center;
            padding: 2rem 0 4rem 0;
        }

        .hero-content h2 {
            font-family: var(--font-outfit);
            font-size: 3.25rem;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 40%, #ff5a1f 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-content p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.03);
            color: white;
            border: 1px solid var(--border-color);
            padding: 0.65rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }

        /* Hero Image - Rounded Glow Frame */
        .hero-image-frame {
            position: relative;
            border-radius: 1rem;
            padding: 0.5rem;
            background: linear-gradient(135deg, var(--border-color), rgba(255, 90, 31, 0.1));
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-premium), var(--shadow-glow);
            overflow: hidden;
            aspect-ratio: 4 / 3;
        }

        .hero-image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.75rem;
            filter: brightness(0.95) contrast(1.05);
        }

        .hero-image-badge {
            position: absolute;
            bottom: 1.5rem;
            left: 1.5rem;
            background: rgba(15, 17, 26, 0.85);
            backdrop-filter: blur(8px);
            border: 1px solid var(--border-color);
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        }

        .badge-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            background: var(--color-success);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 0.6; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.6; }
        }

        .badge-text {
            font-size: 0.75rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Statistics Banner */
        .stats-banner {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 5rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.75rem 1.5rem;
            text-align: center;
            transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(255, 90, 31, 0.2);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .stat-value {
            font-family: var(--font-outfit);
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            line-height: 1.1;
            margin-bottom: 0.35rem;
        }

        .stat-card:nth-child(1) .stat-value { color: var(--color-success); }
        .stat-card:nth-child(2) .stat-value { color: #3b82f6; }
        .stat-card:nth-child(3) .stat-value { color: var(--color-warning); }
        .stat-card:nth-child(4) .stat-value { color: #a855f7; }

        .stat-label {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        /* ==================== SECTION: COMMITMENT ==================== */
        .commitment-section {
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            gap: 4rem;
            align-items: center;
            padding: 4rem 0;
            border-top: 1px solid var(--border-color);
        }

        .commitment-content h3 {
            font-family: var(--font-outfit);
            font-size: 2.25rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
            color: white;
        }

        .commitment-content p {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .commitment-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .commitment-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .commitment-item svg {
            color: var(--color-success);
            flex-shrink: 0;
            margin-top: 0.15rem;
        }

        /* ==================== PAGE: FITUR ==================== */
        .features-intro {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem auto;
        }

        .features-intro h2 {
            font-family: var(--font-outfit);
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
        }

        .features-intro p {
            color: var(--text-secondary);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2.25rem 2rem;
            transition: all 0.25s ease;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--color-primary);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 90, 31, 0.25);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), var(--shadow-glow);
            background: var(--bg-card-hover);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        /* Custom Mining Icon Styling */
        .mining-icon {
            width: 3.5rem;
            height: 3.5rem;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .mining-icon {
            transform: scale(1.1) rotate(2deg);
        }

        .feature-card h3 {
            font-family: var(--font-outfit);
            font-size: 1.35rem;
            font-weight: 700;
            color: white;
            margin-top: 0.5rem;
        }

        .feature-card p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        /* ==================== PAGE: STATISTIK ==================== */
        .stats-intro {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 3rem auto;
        }

        .stats-intro h2 {
            font-family: var(--font-outfit);
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: white;
        }

        .stats-intro p {
            color: var(--text-secondary);
        }

        .charts-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
        }

        .chart-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .chart-card h3 {
            font-family: var(--font-outfit);
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 0.75rem;
        }

        /* Bar Chart */
        .bar-chart {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .bar-row {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.85rem;
        }

        .bar-label {
            width: 80px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .bar-container {
            flex-grow: 1;
            background: rgba(0, 0, 0, 0.25);
            height: 1.6rem;
            border-radius: 0.25rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .bar-fill {
            height: 100%;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 0.75rem;
            color: white;
            font-weight: 700;
            font-size: 0.75rem;
            animation: growBar 1s ease-out forwards;
            width: 0%;
        }

        .bar-fill-kta { background: #3b82f6; --w: 48%; }
        .bar-fill-tta { background: var(--color-warning); --w: 32%; }
        .bar-fill-near { background: var(--color-success); --w: 15%; }
        .bar-fill-acc { background: var(--color-danger); --w: 5%; }

        /* Gauge Progress */
        .gauge-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            padding: 1rem 0;
        }

        .gauge-box {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .gauge-svg {
            transform: rotate(-90deg);
        }

        .gauge-bg {
            fill: none;
            stroke: rgba(255, 255, 255, 0.05);
            stroke-width: 8;
        }

        .gauge-val {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-dasharray: 251.2;
            stroke-dashoffset: 251.2;
            animation: fillGauge 1.2s ease-out forwards;
        }

        .gauge-text {
            font-family: var(--font-outfit);
            font-weight: 800;
            font-size: 1.25rem;
            fill: white;
        }

        .gauge-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* ==================== PAGE: KONTAK ==================== */
        .contact-section {
            display: grid;
            grid-template-columns: 0.85fr 1.15fr;
            gap: 3rem;
            padding: 1rem 0;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            gap: 1.25rem;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .contact-icon {
            background: rgba(255, 90, 31, 0.08);
            color: var(--color-primary);
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .contact-details h4 {
            font-family: var(--font-outfit);
            font-size: 1rem;
            color: white;
            margin-bottom: 0.15rem;
        }

        .contact-details p {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Feedback Form */
        .feedback-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: var(--shadow-premium);
        }

        .feedback-card h3 {
            font-family: var(--font-outfit);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .form-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
        }

        .form-control {
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: white;
            font-size: 0.9rem;
            font-family: var(--font-inter);
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(255, 90, 31, 0.2);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        .btn-submit {
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 2.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(255, 90, 31, 0.25);
            font-family: var(--font-outfit);
            display: inline-block;
        }

        .btn-submit:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--border-color);
            padding: 2.5rem 0;
            margin-top: 6rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* ==================== RESPONSIVE LAYOUT ADJUSTMENTS ==================== */
        
        @media (max-width: 968px) {
            header {
                padding: 1rem 1.5rem;
            }

            /* Responsive Mobile Nav Menu Overlay */
            nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                height: 100vh;
                background: #0f111a;
                border-left: 1px solid var(--border-color);
                flex-direction: column;
                align-items: flex-start;
                padding: 6rem 2rem 2rem 2rem;
                gap: 1.5rem;
                transition: right 0.3s ease;
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.5);
                z-index: 1000;
            }

            nav.mobile-active {
                right: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .nav-link {
                width: 100%;
                font-size: 1rem;
                padding: 0.5rem 0;
            }

            .btn-cta {
                width: 100%;
                text-align: center;
                margin-top: 1rem;
            }

            .hero-section {
                grid-template-columns: 1fr;
                gap: 3rem;
                text-align: center;
            }

            .hero-content h2 {
                font-size: 2.75rem;
            }

            .hero-content p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-image-frame {
                max-width: 500px;
                margin: 0 auto;
            }

            .commitment-section {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .commitment-section .hero-image-frame {
                order: -1;
                max-width: 500px;
                margin: 0 auto;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .contact-section {
                grid-template-columns: 1fr;
                gap: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .stats-banner {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .hero-content h2 {
                font-size: 2.25rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .stats-banner {
                grid-template-columns: 1fr;
            }
            
            .feedback-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header / Navigation Bar -->
    <header>
        <div class="nav-container">
            <a href="#" class="logo-container" onclick="showPage('beranda', event)">
                <!-- Crossed Pickaxe and Shovel SVG Logo -->
                <svg class="logo-icon-svg" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="64" height="64" rx="12" fill="var(--color-primary)"/>
                    <path d="M18 46L46 18" stroke="white" stroke-width="4.5" stroke-linecap="round"/>
                    <path d="M46 46L18 18" stroke="white" stroke-width="4.5" stroke-linecap="round"/>
                    <circle cx="32" cy="32" r="5" fill="var(--bg-main)" stroke="white" stroke-width="2.5"/>
                </svg>
                <div class="logo-text">
                    <h1>SafeMine-HSE</h1>
                    <span>K3 Tambang Portal</span>
                </div>
            </a>
            
            <!-- Mobile Menu Toggle Button -->
            <button class="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Buka Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" id="menu-icon"><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
            </button>
            
            <nav id="navbar-menu">
                <a class="nav-link active" id="nav-beranda" onclick="showPage('beranda')">Beranda</a>
                <a class="nav-link" id="nav-fitur" onclick="showPage('fitur')">Fitur</a>
                <a class="nav-link" id="nav-statistik" onclick="showPage('statistik')">Statistik K3</a>
                <a class="nav-link" id="nav-kontak" onclick="showPage('kontak')">Hubungi Kami</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-cta">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-cta">Masuk Sistem</a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content Area -->
    <main>
        
        <!-- PANEL: BERANDA -->
        <div class="page-panel active" id="panel-beranda">
            <div class="hero-section">
                <div class="hero-content">
                    <h2>Membangun Budaya K3 Tambang yang Proaktif</h2>
                    <p>SafeMine-HSE menyediakan sistem pelaporan K3 digital tambang yang andal untuk mengidentifikasi hazard, memproses investigasi akar penyebab (RCA 5 Whys), menetapkan CAPA, dan memantau KPI keselamatan kerja demi target zero-accident.</p>
                    <div class="hero-actions">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-cta" style="padding: 0.65rem 1.5rem; font-size: 0.9rem;">Masuk ke Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-cta" style="padding: 0.65rem 1.5rem; font-size: 0.9rem;">Mulai Pelaporan K3</a>
                        @endauth
                        <a href="#fitur" class="btn-outline" onclick="showPage('fitur', event)">Pelajari Fitur</a>
                    </div>
                </div>
                
                <div class="hero-image-frame">
                    <img src="/images/mining_hero.png" alt="Kegiatan K3 Pertambangan SafeMine">
                    <div class="hero-image-badge">
                        <div class="badge-dot"></div>
                        <span class="badge-text">Operasi Aman 2026</span>
                    </div>
                </div>
            </div>

            <!-- Statistics Banner -->
            <div class="stats-banner">
                <div class="stat-card">
                    <div class="stat-value">365+</div>
                    <div class="stat-label">Hari Tanpa Kecelakaan (LTI)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">1.2M+</div>
                    <div class="stat-label">Jam Kerja Aman</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">98.4%</div>
                    <div class="stat-label">Laporan K3 Diselesaikan</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">&lt; 2 Jam</div>
                    <div class="stat-label">Rata-Rata Respons CAPA</div>
                </div>
            </div>

            <!-- Commitment Split Section -->
            <div class="commitment-section">
                <div class="hero-image-frame">
                    <img src="/images/safety_inspection.png" alt="Safety Inspection Equipment">
                </div>
                <div class="commitment-content">
                    <h3>Komitmen Keselamatan Kerja</h3>
                    <p>Bagi kami di SafeMine, keselamatan kerja bukanlah sekadar opsi, melainkan pondasi utama di setiap detak aktivitas pertambangan. Platform ini memfasilitasi koordinasi cepat antara pekerja lapangan, tim pengawas HSE, dan manajemen untuk menuntaskan temuan bahaya secara tuntas.</p>
                    <ul class="commitment-list">
                        <li class="commitment-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Deteksi dini temuan kondisi tidak aman (KTA) dan tindakan tidak aman (TTA).</span>
                        </li>
                        <li class="commitment-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Standarisasi investigasi insiden menggunakan metode RCA 5 Whys.</span>
                        </li>
                        <li class="commitment-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Transparansi alur kerja CAPA dengan notifikasi otomatis ke departemen terkait.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- PANEL: FITUR -->
        <div class="page-panel" id="panel-fitur">
            <div class="features-intro">
                <h2>Fitur Unggulan SafeMine-HSE</h2>
                <p>Kami merancang ekosistem pelaporan K3 dengan alur kerja (workflow) transparan demi mewujudkan zero-harm di lingkungan kerja tambang Anda.</p>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1: Pelaporan (Mining Helmet Icon) -->
                <div class="feature-card">
                    <!-- Helm Tambang dengan Sinar Senter K3 -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M32 12c-12 0-21 9-21 21v5h42v-5c0-12-9-21-21-21z" fill="rgba(255, 90, 31, 0.15)" stroke="var(--color-primary)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M7 38h50v4a3 3 0 0 1-3 3H10a3 3 0 0 1-3-3v-4z" fill="var(--bg-main)" stroke="var(--color-primary)" stroke-width="3"/>
                        <rect x="25" y="19" width="14" height="10" rx="2" fill="var(--color-primary)" stroke="white" stroke-width="2"/>
                        <path d="M32 24l-9-15m9 15l9-15m-9 15V8" stroke="var(--color-warning)" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <h3>1. Pelaporan & Gambar</h3>
                    <p>Pelapor lapangan dapat melampirkan foto hazard sebelum perbaikan langsung dari smartphone dengan kompresi gambar otomatis agar loading instan.</p>
                </div>
                
                <!-- Feature 2: Klasifikasi Severity (Safety Shield Icon) -->
                <div class="feature-card">
                    <!-- Perisai Keselamatan K3 -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M32 4L10 12v16c0 14.5 10 27 22 32 12-5 22-17.5 22-32V12L32 4z" fill="rgba(16, 185, 129, 0.1)" stroke="var(--color-success)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M22 29l7 7 13-13" stroke="var(--color-success)" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3>2. Klasifikasi Severity</h3>
                    <p>HSE Officer menganalisis laporan awal dan menetapkan tingkat bahaya (Low, Medium, High, Critical) secara terstandar dan obyektif.</p>
                </div>
                
                <!-- Feature 3: Investigasi RCA (Crossed Pickaxe & Shovel Icon) -->
                <div class="feature-card">
                    <!-- Beliung & Sekop Tambang (RCA) -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Shovel -->
                        <path d="M14 50L50 14" stroke="var(--text-secondary)" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M46 10l8 8-4 4-8-8 4-4z" fill="var(--text-secondary)"/>
                        <!-- Pickaxe -->
                        <path d="M50 50L14 14" stroke="var(--color-primary)" stroke-width="3.5" stroke-linecap="round"/>
                        <path d="M7 13c1-4 7-6 13 0s4 12 0 13-11-2-13-13z" fill="rgba(255, 90, 31, 0.15)" stroke="var(--color-primary)" stroke-width="3"/>
                        <circle cx="32" cy="32" r="4.5" fill="white" stroke="var(--bg-main)" stroke-width="2"/>
                    </svg>
                    <h3>3. Analisis RCA 5 Whys</h3>
                    <p>HSE Supervisor melakukan investigasi mendalam untuk mencari akar penyebab masalah melalui framework terstruktur "5 Whys" di dashboard.</p>
                </div>
                
                <!-- Feature 4: Penugasan CAPA (Mining Dump Truck Icon) -->
                <div class="feature-card">
                    <!-- Truk Tambang / Dump Truck Raksasa -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 24h18l8 12h24v12H6V24z" fill="rgba(255, 90, 31, 0.1)" stroke="var(--color-primary)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M12 24L6 10h28l-8 14H12z" fill="var(--color-primary)" stroke="white" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M48 26h8v10h-8V26z" fill="var(--bg-main)" stroke="var(--text-primary)" stroke-width="2"/>
                        <circle cx="18" cy="46" r="8" fill="var(--bg-card)" stroke="var(--color-primary)" stroke-width="3.5"/>
                        <circle cx="18" cy="46" r="2.5" fill="white"/>
                        <circle cx="44" cy="46" r="8" fill="var(--bg-card)" stroke="var(--color-primary)" stroke-width="3.5"/>
                        <circle cx="44" cy="46" r="2.5" fill="white"/>
                    </svg>
                    <h3>4. Penugasan CAPA & SLA</h3>
                    <p>Tindakan Perbaikan & Pencegahan (CAPA) langsung didelegasikan ke departemen penanggung jawab (PIC) lengkap dengan tenggat waktu SLA otomatis.</p>
                </div>
                
                <!-- Feature 5: Verifikasi (Loaded Mine Cart Icon) -->
                <div class="feature-card">
                    <!-- Lori / Kereta Tambang (Mine Cart) -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 18h52l-6 22H12L6 18z" fill="rgba(16, 185, 129, 0.1)" stroke="var(--color-success)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M14 18c2-4 6-6 10-4s6 4 10 0 8 2 10 6" fill="var(--color-success)"/>
                        <circle cx="20" cy="48" r="6.5" fill="var(--bg-main)" stroke="var(--color-success)" stroke-width="3.5"/>
                        <circle cx="44" cy="48" r="6.5" fill="var(--bg-main)" stroke="var(--color-success)" stroke-width="3.5"/>
                        <path d="M2 56h60" stroke="var(--text-secondary)" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    <h3>5. Verifikasi & Penutupan</h3>
                    <p>Departemen PIC mengunggah bukti penyelesaian kerja (foto setelah perbaikan), yang kemudian diperiksa dan disahkan oleh HSE Manager sebelum ditutup.</p>
                </div>
                
                <!-- Feature 6: Ekspor Excel & PDF (Mining Map & Document Icon) -->
                <div class="feature-card">
                    <!-- Peta Geologi Tambang / Dokumen Ekspor -->
                    <svg class="mining-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 4h26l14 14v40H14V4z" fill="rgba(255, 255, 255, 0.02)" stroke="var(--text-primary)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M40 4v14h14" stroke="var(--text-primary)" stroke-width="3" stroke-linejoin="round"/>
                        <path d="M22 28h20m-20 8h20m-20 8h12" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round"/>
                        <path d="M46 42l-8 8" stroke="var(--text-secondary)" stroke-width="2"/>
                        <path d="M44 48c2 2 4 1 5 0s0-3-2-5" stroke="var(--text-secondary)" stroke-width="2"/>
                    </svg>
                    <h3>6. Ekspor Excel & PDF</h3>
                    <p>HSE Manager dapat mengekspor laporan rekap bulanan dengan satu klik untuk keperluan audit eksternal maupun presentasi manajemen.</p>
                </div>
            </div>
        </div>

        <!-- PANEL: STATISTIK -->
        <div class="page-panel" id="panel-statistik">
            <div class="stats-intro">
                <h2>Statistik Keselamatan Tambang</h2>
                <p>Data real-time penyelesaian insiden dan klasifikasi bahaya di seluruh area pertambangan SafeMine.</p>
            </div>
            
            <div class="charts-container">
                <!-- Chart 1: Kategori Insiden -->
                <div class="chart-card">
                    <h3>Jumlah Insiden Berdasarkan Kategori (Tahun Berjalan)</h3>
                    <div class="bar-chart">
                        <div class="bar-row">
                            <span class="bar-label">KTA</span>
                            <div class="bar-container">
                                <div class="bar-fill bar-fill-kta">48 Laporan</div>
                            </div>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">TTA</span>
                            <div class="bar-container">
                                <div class="bar-fill bar-fill-tta">32 Laporan</div>
                            </div>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Near Miss</span>
                            <div class="bar-container">
                                <div class="bar-fill bar-fill-near">15 Laporan</div>
                            </div>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Accident</span>
                            <div class="bar-container">
                                <div class="bar-fill bar-fill-acc">2 Laporan</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Chart 2: KPI & SLA -->
                <div class="chart-card">
                    <h3>Indikator Kinerja Utama (KPI) K3</h3>
                    <div class="gauge-container">
                        <div class="gauge-box">
                            <svg class="gauge-svg" width="100" height="100">
                                <circle class="gauge-bg" cx="50" cy="50" r="40"></circle>
                                <circle class="gauge-val" cx="50" cy="50" r="40" stroke="var(--color-success)" style="--offset: 12.5;"></circle>
                                <text class="gauge-text" x="50" y="55" text-anchor="middle">95%</text>
                            </svg>
                            <span class="gauge-label">SLA Tepat Waktu</span>
                        </div>
                        
                        <div class="gauge-box">
                            <svg class="gauge-svg" width="100" height="100">
                                <circle class="gauge-bg" cx="50" cy="50" r="40"></circle>
                                <circle class="gauge-val" cx="50" cy="50" r="40" stroke="var(--color-primary)" style="--offset: 4.8;"></circle>
                                <text class="gauge-text" x="50" y="55" text-anchor="middle">98%</text>
                            </svg>
                            <span class="gauge-label">Resolusi Hazard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL: KONTAK -->
        <div class="page-panel" id="panel-kontak">
            <div class="contact-section">
                <div class="contact-info">
                    <div style="margin-bottom: 1rem;">
                        <h2 style="font-family: var(--font-outfit); font-size: 2.25rem; font-weight: 800; margin-bottom: 0.5rem; color: white;">Hubungi HSE Dept</h2>
                        <p style="color: var(--text-secondary);">Gunakan kontak di bawah ini untuk keadaan darurat tambang atau pertanyaan administratif sistem.</p>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Emergency Mine Hotline</h4>
                            <p style="color: var(--color-danger); font-weight: bold; font-size: 1.15rem;">(021) 555-9111</p>
                        </div>
                    </div>
                    
                    <div class="contact-card">
                        <div class="contact-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div class="contact-details">
                            <h4>Email HSE Administration</h4>
                            <p>admin.hse@safemine.com</p>
                        </div>
                    </div>
                </div>
                
                <!-- Feedback/Inquiry Form -->
                <div class="feedback-card">
                    <h3>Kirim Feedback / Laporan Non-Darurat</h3>
                    <form id="contact-form" onsubmit="submitContactForm(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name">Nama Lengkap</label>
                                <input type="text" id="contact-name" class="form-control" placeholder="Nama Anda" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email">Alamat Email</label>
                                <input type="email" id="contact-email" class="form-control" placeholder="email@safemine.com" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject">Subyek Laporan</label>
                            <input type="text" id="contact-subject" class="form-control" placeholder="Contoh: Usulan Perbaikan Rambatan Lereng" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-message">Detail Masukan</label>
                            <textarea id="contact-message" class="form-control" placeholder="Tuliskan masukan atau laporan K3 non-darurat Anda..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 SafeMine-HSE. Hak Cipta Dilindungi Undang-Undang.</p>
    </footer>

    <!-- Interactive Javascript Tab Switching & Mobile Navigation Toggle -->
    <script>
        const showPage = (pageName, event = null) => {
            if (event) {
                event.preventDefault();
            }
            
            // Close mobile menu if active
            const navMenu = document.getElementById('navbar-menu');
            if (navMenu.classList.contains('mobile-active')) {
                toggleMobileMenu();
            }
            
            // Hide all panels
            document.querySelectorAll('.page-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Deactivate all nav links
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Show target panel and activate link
            document.getElementById(`panel-${pageName}`).classList.add('active');
            const navLink = document.getElementById(`nav-${pageName}`);
            if (navLink) {
                navLink.classList.add('active');
            }

            // Scroll window to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const toggleMobileMenu = () => {
            const navMenu = document.getElementById('navbar-menu');
            const icon = document.getElementById('menu-icon');
            navMenu.classList.toggle('mobile-active');
            
            if (navMenu.classList.contains('mobile-active')) {
                // Change menu icon to "X"
                icon.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
            } else {
                // Change back to burger icon
                icon.innerHTML = '<line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/>';
            }
        };

        const submitContactForm = (e) => {
            e.preventDefault();
            alert('Terima kasih! Feedback Anda telah terkirim ke Departemen HSE dan akan segera ditinjau.');
            document.getElementById('contact-form').reset();
        };
    </script>
</body>
</html>
