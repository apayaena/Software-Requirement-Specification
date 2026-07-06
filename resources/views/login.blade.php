<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SafeMine HSE</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --bg-main: #0b0c10;
            --bg-card: #151821;
            --border-color: rgba(255, 255, 255, 0.08);
            --color-primary: #ff5a1f;
            --color-primary-hover: #e04e17;
            --text-primary: #f3f4f6;
            --text-secondary: #9ca3af;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #1a233a, var(--bg-main) 60%);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            background: rgba(21, 24, 33, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 100px rgba(255, 90, 31, 0.05);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-badge {
            background: var(--color-primary);
            color: white;
            font-weight: 800;
            padding: 0.5rem 0.8rem;
            border-radius: 0.375rem;
            display: inline-block;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 12px rgba(255, 90, 31, 0.3);
        }

        .logo-title {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .logo-subtitle {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            color: white;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(255, 90, 31, 0.2);
        }

        .btn-submit {
            width: 100%;
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(255, 90, 31, 0.2);
        }

        .btn-submit:hover {
            background: var(--color-primary-hover);
            transform: translateY(-1px);
        }

        .demo-section {
            margin-top: 2rem;
            border-top: 1px solid var(--border-color);
            padding-top: 1.5rem;
        }

        .demo-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .demo-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }

        .btn-demo-role {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            text-align: left;
            transition: all 0.2s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-demo-role:hover {
            background: rgba(255, 90, 31, 0.08);
            border-color: var(--color-primary);
        }

        .demo-badge {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-secondary);
            font-size: 0.7rem;
            padding: 0.15rem 0.4rem;
            border-radius: 0.25rem;
            font-family: monospace;
        }

        .btn-demo-role:hover .demo-badge {
            background: var(--color-primary);
            color: white;
        }

        /* Error box styling */
        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #ef4444;
            color: #f87171;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            display: none;
        }

        /* ==================== RESPONSIVE LAYOUT ADJUSTMENTS ==================== */
        @media (max-width: 480px) {
            .login-container {
                padding: 2rem 1.5rem;
            }
            .logo-title {
                font-size: 1.25rem;
            }
            .logo-subtitle {
                font-size: 0.75rem;
            }
        }
    </style>

</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo-badge">SM</div>
            <div class="logo-title">SafeMine-HSE</div>
            <div class="logo-subtitle">Sistem Manajemen Pelaporan K3 Tambang</div>
        </div>

        <div class="error-box" id="error-container"></div>

        <form id="login-form">
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" class="form-control" placeholder="nama@safemine.com" required>
            </div>
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-submit">Masuk Sistem</button>
        </form>

        </div>

    <script>
                document.getElementById('login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorBox = document.getElementById('error-container');

            errorBox.style.display = 'none';

            fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email, password })
            })
            .then(res => {
                if (res.ok) {
                    window.location.href = '/dashboard';
                } else {
                    return res.json().then(data => {
                        throw new Error(data.errors?.email?.[0] || 'Login gagal.');
                    });
                }
            })
            .catch(err => {
                errorBox.innerText = err.message;
                errorBox.style.display = 'block';
            });
        });
    </script>
</body>
</html>
