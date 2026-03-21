<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SubdivMoneyTrack') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-primary: #1F3B5C;
            --brand-secondary: #2E5B8A;
            --brand-accent: #3B82F6;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            background-color: var(--bg-light);
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--brand-primary);
            background: linear-gradient(135deg, rgba(31, 59, 92, 0.95) 0%, rgba(15, 42, 68, 0.85) 100%),
                        url("{{ asset('bg.png') }}") no-repeat center center;
            background-size: cover;
            padding: 2rem;
        }

        .hero-content {
            max-width: 1000px;
            width: 100%;
            text-align: center;
            z-index: 10;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .main-logo {
            width: 100px;
            height: 100px;
            border-radius: 24px;
            background: white;
            padding: 10px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            object-fit: contain;
        }

        .hero-title {
            color: white;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .hero-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: clamp(1rem, 2vw, 1.25rem);
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Portal Cards */
        .portals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .portal-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            padding: 2.5rem;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
        }

        .portal-card:hover {
            background: rgba(255, 255, 255, 0.98);
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        .portal-card:hover .portal-icon {
            background: var(--brand-primary);
            color: white;
            transform: scale(1.1);
        }

        .portal-card:hover .portal-title {
            color: var(--brand-primary);
        }

        .portal-card:hover .portal-desc {
            color: var(--text-muted);
        }

        .portal-card:hover .portal-btn {
            background: var(--brand-primary);
            color: white;
        }

        .portal-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .portal-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .portal-desc {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .portal-btn {
            margin-top: auto;
            width: 100%;
            padding: 0.875rem;
            border-radius: 14px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 2rem;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
        }

        @media (max-width: 640px) {
            .hero {
                padding: 1rem;
            }
            .portal-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <section class="hero">
        <div class="hero-content">
            <div class="logo-container">
                <img src="{{ asset('Cdlogo.jpg') }}" alt="SubdivMoneyTrack Logo" class="main-logo">
            </div>
            
            <h1 class="hero-title">SubdivMoneyTrack</h1>
            <p class="hero-subtitle">The modern management solution for smarter subdivisions. Choose your portal to continue.</p>

            <div class="portals-grid">
                <!-- Resident Portal -->
                <a href="{{ route('resident.login') }}" class="portal-card">
                    <div class="portal-icon">
                        <i class="bi bi-house-heart-fill"></i>
                    </div>
                    <h2 class="portal-title">Resident Portal</h2>
                    <p class="portal-desc">Manage your dues, amenities, and community updates in one place.</p>
                    <div class="portal-btn">Login as Resident</div>
                </a>

                <!-- Admin Portal -->
                <a href="{{ route('admin.login') }}" class="portal-card">
                    <div class="portal-icon">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h2 class="portal-title">Admin Portal</h2>
                    <p class="portal-desc">Authorized personnel access for subdivision oversight and financial tracking.</p>
                    <div class="portal-btn">Login as Admin</div>
                </a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} SubdivMoneyTrack. All rights reserved.
        </div>
    </section>

</body>
</html>
