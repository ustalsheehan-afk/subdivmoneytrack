<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SubdivMoneyTrack | Premium Subdivision Management</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-dark: #0D1F1C;
            --bg-darker: #081412;
            --brand-accent: #B6FF5C; /* Electric Green */
            --brand-accent-glow: rgba(182, 255, 92, 0.3);
            --text-main: #FFFFFF;
            --text-muted: #A0AEC0;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Navbar Scroll Effect */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 1.5rem 4rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
        }

        nav.scrolled {
            padding: 1rem 4rem;
            background: rgba(13, 31, 28, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--glass-border);
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .nav-logo img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px var(--brand-accent-glow);
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition);
        }

        .nav-links a:hover { color: var(--brand-accent); }

        .btn-login-nav {
            background: var(--brand-accent);
            color: var(--bg-dark) !important;
            padding: 0.75rem 2.2rem;
            border-radius: 50px;
            font-weight: 700;
            box-shadow: 0 10px 30px var(--brand-accent-glow);
        }

        .btn-login-nav:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(182, 255, 92, 0.5);
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            overflow: hidden;
            background-color: var(--bg-dark); /* Fallback */
        }

        .hero-video-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-video-wrapper video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.35) blur(2px); /* Slight blur and brightness reduction */
            transform: scale(1.1);
            transition: transform 0.1s ease-out;
        }

        /* Dark Overlay for Readability */
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                to bottom,
                rgba(13, 31, 28, 0.7) 0%,
                rgba(13, 31, 28, 0.4) 50%,
                rgba(13, 31, 28, 0.9) 100%
            );
            z-index: 1;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 900px;
            padding: 0 2rem;
        }

        .hero-headline {
            font-size: clamp(2.5rem, 8vw, 5rem);
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -2px;
            opacity: 0;
            transform: translateY(40px);
            animation: heroReveal 1s forwards 0.2s;
        }

        .hero-subtext {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: var(--text-muted);
            margin-bottom: 3.5rem;
            opacity: 0;
            transform: translateY(30px);
            animation: heroReveal 1s forwards 0.5s;
        }

        .hero-actions {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            opacity: 0;
            transform: scale(0.9);
            animation: heroRevealScale 1s forwards 0.8s;
        }

        @keyframes heroReveal {
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes heroRevealScale {
            to { opacity: 1; transform: scale(1); }
        }

        .btn-hero {
            padding: 1.1rem 2.8rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-primary {
            background: var(--brand-accent);
            color: var(--bg-dark);
            box-shadow: 0 20px 40px var(--brand-accent-glow);
        }

        .btn-outline {
            border: 2px solid var(--brand-accent);
            color: var(--brand-accent);
        }

        .btn-hero:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 30px 60px var(--brand-accent-glow);
        }

        /* Scroll Reveal Utility */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Category Row */
        .category-row {
            padding: 6rem 4rem;
            display: flex;
            gap: 2rem;
            justify-content: center;
            background: var(--bg-darker);
            flex-wrap: wrap;
        }

        .category-card {
            background: var(--glass-bg);
            padding: 2.5rem;
            border-radius: 24px;
            text-align: center;
            flex: 1;
            min-width: 200px;
            max-width: 240px;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
        }

        .category-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--brand-accent);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .category-card i {
            font-size: 2.5rem;
            color: var(--brand-accent);
            margin-bottom: 1.5rem;
            display: block;
            transition: var(--transition);
        }

        .category-card:hover i {
            transform: scale(1.2);
            filter: drop-shadow(0 0 10px var(--brand-accent));
        }

        /* Stats Section */
        .stats-section {
            padding: 10rem 4rem;
            display: flex;
            align-items: center;
            gap: 6rem;
            background: var(--bg-dark);
        }

        .stat-item h3 {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--brand-accent);
            margin-bottom: 0.5rem;
        }

        /* Core Features */
        .features-section {
            padding: 10rem 4rem;
            text-align: center;
            background: var(--bg-darker);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin-top: 5rem;
        }

        .feature-card {
            background: var(--glass-bg);
            padding: 4rem 3rem;
            border-radius: 32px;
            text-align: left;
            border: 1px solid var(--glass-border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-15px);
            border-color: var(--brand-accent);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
        }

        .feature-card i {
            font-size: 2.2rem;
            color: var(--brand-accent);
            margin-bottom: 2rem;
            display: block;
        }

        /* Floating Animation */
        .floating {
            animation: floating 4s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Footer */
        footer {
            padding: 6rem 4rem;
            text-align: center;
            border-top: 1px solid var(--glass-border);
            color: var(--text-muted);
        }

        @media (max-width: 1024px) {
            nav { padding: 1.5rem 2rem; }
            .nav-links { display: none; }
            .stats-section { flex-direction: column; text-align: center; gap: 4rem; }
            .category-row { padding: 4rem 2rem; }
            
            /* Mobile Optimization: Hide video and show background image */
            .hero-video-wrapper video { display: none; }
            .hero {
                background: linear-gradient(rgba(13, 31, 28, 0.8), rgba(13, 31, 28, 0.8)), 
                            url('/images/hero-fallback.jpg') center center no-repeat;
                background-size: cover;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav id="mainNav">
        <a href="/" class="nav-logo">
            <img src="<?php echo e(asset('Cdlogo.jpg')); ?>" alt="SubdivMoneyTrack">
            <span>SubdivMoneyTrack</span>
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#stats">Performance</a>
            <a href="#contact">Support</a>
            <a href="<?php echo e(route('login')); ?>" class="btn-login-nav">Sign In</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-video-wrapper">
            <video autoplay muted loop playsinline id="heroVideo">
                <source src="/images/landing-page.mp4" type="video/mp4">
            </video>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-headline">Manage Your<br><span style="color: var(--brand-accent)">Community</span> Smarter</h1>
            <p class="hero-subtext">
                The high-performance platform for subdivision dues collection, resident management, and community security.
            </p>
            <div class="hero-actions">
                <a href="<?php echo e(route('login')); ?>" class="btn-hero btn-primary">
                    <i class="bi bi-rocket-takeoff"></i> Get Started
                </a>
                <a href="#features" class="btn-hero btn-outline">
                    Explore Features
                </a>
            </div>
        </div>
    </section>

    <!-- Category Row -->
    <div class="category-row">
        <div class="category-card reveal" style="transition-delay: 0.1s;">
            <i class="bi bi-wallet2 floating"></i>
            <h3>Dues & Billing</h3>
        </div>
        <div class="category-card reveal" style="transition-delay: 0.2s;">
            <i class="bi bi-people floating" style="animation-delay: 0.5s;"></i>
            <h3>Residents</h3>
        </div>
        <div class="category-card reveal" style="transition-delay: 0.3s;">
            <i class="bi bi-shield-lock floating" style="animation-delay: 1s;"></i>
            <h3>Security</h3>
        </div>
        <div class="category-card reveal" style="transition-delay: 0.4s;">
            <i class="bi bi-graph-up-arrow floating" style="animation-delay: 1.5s;"></i>
            <h3>Analytics</h3>
        </div>
    </div>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="stats-info reveal">
            <h2 style="font-size: 3rem; margin-bottom: 2rem;">Unmatched<br>Collection Accuracy</h2>
            <p class="hero-subtext" style="text-align: left;">Our system eliminates manual errors and provides real-time financial oversight for your entire subdivision.</p>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 4rem;">
                <div class="stat-item">
                    <h3>99.9%</h3>
                    <p style="color: var(--text-muted)">Uptime Guarantee</p>
                </div>
                <div class="stat-item">
                    <h3>24/7</h3>
                    <p style="color: var(--text-muted)">Security Sync</p>
                </div>
            </div>
        </div>
        <div class="stats-preview reveal" style="transition-delay: 0.3s;">
            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Interface" style="width: 100%; border-radius: 32px; border: 1px solid var(--glass-border);">
        </div>
    </section>

    <!-- Core Features -->
    <section class="features-section" id="features">
        <h2 class="reveal" style="font-size: 3rem;">Core SaaS Modules</h2>
        <div class="features-grid">
            <div class="feature-card reveal" style="transition-delay: 0.1s;">
                <i class="bi bi-lightning-charge"></i>
                <h4 style="font-size: 1.5rem; margin-bottom: 1rem;">Automated Dues</h4>
                <p style="color: var(--text-muted)">Automated invoicing, reminders, and online payment processing for all residents.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.2s;">
                <i class="bi bi-person-check"></i>
                <h4 style="font-size: 1.5rem; margin-bottom: 1rem;">Smart Directory</h4>
                <p style="color: var(--text-muted)">Comprehensive resident records with detailed history and document management.</p>
            </div>
            <div class="feature-card reveal" style="transition-delay: 0.3s;">
                <i class="bi bi-broadcast"></i>
                <h4 style="font-size: 1.5rem; margin-bottom: 1rem;">Instant Alerts</h4>
                <p style="color: var(--text-muted)">Broadcast community updates and security alerts instantly to all homeowner portals.</p>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; <?php echo e(date('Y')); ?> SubdivMoneyTrack. High-End Subdivision Management SaaS.</p>
    </footer>

    <!-- JavaScript for Animations -->
    <script>
        // Navbar Scroll Effect
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Hero Parallax Effect
        const heroVideo = document.getElementById('heroVideo');
        window.addEventListener('scroll', () => {
            const scroll = window.pageYOffset;
            heroVideo.style.transform = `scale(1.1) translateY(${scroll * 0.4}px)`;
        });

        // Intersection Observer for Scroll Reveal
        const observerOptions = {
            threshold: 0.15
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>
</html>
<?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/welcome.blade.php ENDPATH**/ ?>