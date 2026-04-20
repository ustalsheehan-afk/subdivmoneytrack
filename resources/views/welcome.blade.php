<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

        .section-shell {
            padding: 7rem 4rem;
            position: relative;
        }

        .section-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: radial-gradient(circle at 20% 10%, rgba(182, 255, 92, 0.08), transparent 45%);
        }

        .section-head {
            max-width: 820px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .section-eyebrow {
            display: inline-flex;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            border: 1px solid rgba(182, 255, 92, 0.45);
            color: var(--brand-accent);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.72rem;
            font-weight: 700;
            margin-bottom: 1rem;
            background: rgba(182, 255, 92, 0.08);
        }

        .section-title {
            font-size: clamp(2rem, 4.8vw, 3.1rem);
            line-height: 1.15;
            letter-spacing: -0.04em;
            margin-bottom: 1rem;
        }

        .section-copy {
            color: var(--text-muted);
            font-size: 1.03rem;
        }

        .ops-grid {
            margin-top: 3rem;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1rem;
            position: relative;
            z-index: 2;
        }

        .ops-card {
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.35rem;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.015));
            transition: var(--transition);
            min-height: 155px;
        }

        .ops-card:hover {
            transform: translateY(-6px);
            border-color: rgba(182, 255, 92, 0.65);
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.28);
        }

        .ops-card i {
            color: var(--brand-accent);
            font-size: 1.35rem;
            display: inline-flex;
            margin-bottom: 0.85rem;
        }

        .ops-card h4 {
            font-size: 1.02rem;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }

        .ops-card p {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .modules-section {
            padding: 8rem 4rem;
            background: var(--bg-darker);
            position: relative;
        }

        .audience-toggle {
            margin: 2.2rem auto 0;
            display: inline-flex;
            padding: 0.3rem;
            border-radius: 999px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.03);
            gap: 0.35rem;
        }

        .toggle-chip {
            border: 0;
            background: transparent;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.86rem;
            padding: 0.6rem 1.1rem;
            border-radius: 999px;
            cursor: pointer;
            transition: var(--transition);
        }

        .toggle-chip.active {
            background: var(--brand-accent);
            color: var(--bg-dark);
            box-shadow: 0 8px 22px rgba(182, 255, 92, 0.3);
        }

        .modules-grid {
            margin-top: 2.8rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.2rem;
        }

        .module-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 1.4rem;
            transition: var(--transition);
        }

        .module-card.hidden-by-filter {
            display: none;
        }

        .module-card:hover {
            transform: translateY(-8px);
            border-color: rgba(182, 255, 92, 0.55);
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.35);
        }

        .module-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            gap: 0.8rem;
        }

        .module-top i {
            color: var(--brand-accent);
            font-size: 1.3rem;
        }

        .badge-role {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border: 1px solid var(--glass-border);
            border-radius: 999px;
            color: var(--text-muted);
            padding: 0.3rem 0.6rem;
        }

        .module-card h4 {
            font-size: 1.04rem;
            margin-bottom: 0.45rem;
        }

        .module-card p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .module-list {
            list-style: none;
            display: grid;
            gap: 0.45rem;
            color: #d5dde9;
            font-size: 0.84rem;
        }

        .module-list li {
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .module-list li i {
            color: var(--brand-accent);
            font-size: 0.8rem;
        }

        .workflow {
            padding: 7rem 4rem;
            background: var(--bg-dark);
        }

        .workflow-grid {
            margin-top: 2.8rem;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .workflow-step {
            border-radius: 22px;
            border: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.025);
            padding: 1.2rem;
        }

        .workflow-step span {
            display: inline-flex;
            width: 32px;
            height: 32px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(182, 255, 92, 0.12);
            color: var(--brand-accent);
            font-weight: 800;
            margin-bottom: 0.8rem;
        }

        .workflow-step h4 {
            font-size: 1rem;
            margin-bottom: 0.35rem;
        }

        .workflow-step p {
            color: var(--text-muted);
            font-size: 0.88rem;
        }

        .support-section {
            padding: 7rem 4rem 6rem;
            background: var(--bg-darker);
        }

        .support-panel {
            max-width: 980px;
            margin: 0 auto;
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 2.2rem;
            background: linear-gradient(130deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.01));
        }

        .support-links {
            margin-top: 1.3rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
        }

        .support-links a {
            text-decoration: none;
            color: var(--text-main);
            border: 1px solid var(--glass-border);
            border-radius: 999px;
            padding: 0.68rem 1.1rem;
            font-size: 0.86rem;
            transition: var(--transition);
        }

        .support-links a:hover {
            border-color: rgba(182, 255, 92, 0.65);
            color: var(--brand-accent);
            transform: translateY(-2px);
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
            .section-shell,
            .modules-section,
            .workflow,
            .support-section { padding: 5rem 2rem; }
            .ops-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .modules-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .workflow-grid { grid-template-columns: 1fr; }
            
            /* Mobile Optimization: Hide video and show background image */
            .hero-video-wrapper video { display: none; }
            .hero {
                background: linear-gradient(rgba(13, 31, 28, 0.8), rgba(13, 31, 28, 0.8)), 
                            url('{{ asset('images/hero-fallback.jpg') }}') center center no-repeat !important;
                background-size: cover !important;
            }
        }

        @media (max-width: 640px) {
            .ops-grid,
            .modules-grid { grid-template-columns: 1fr; }
            .support-panel { padding: 1.4rem; }
            .support-links a { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav id="mainNav">
        <a href="/" class="nav-logo">
            <img src="{{ asset('images/subdivision-icon.svg') }}" alt="Subdivision icon">
            <span>SubdivMoneyTrack</span>
        </a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#stats">Performance</a>
            <a href="#contact">Support</a>
            <a href="{{ route('login') }}" class="btn-login-nav">Sign In</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-video-wrapper">
            <video autoplay muted loop playsinline id="heroVideo">
                <source src="{{ asset('images/landing-page.mp4') }}" type="video/mp4">
            </video>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-headline">Manage Your<br><span style="color: var(--brand-accent)">Community</span> Smarter</h1>
            <p class="hero-subtext">
                The high-performance platform for subdivision dues collection, resident management, and community security.
            </p>
            <div class="hero-actions">
                <a href="{{ route('login') }}" class="btn-hero btn-primary">
                    <i class="bi bi-rocket-takeoff"></i> Get Started
                </a>
            </div>
        </div>
    </section>

    <section class="section-shell" id="stats">
        <div class="section-head reveal">
            <span class="section-eyebrow">Operations Overview</span>
            <h2 class="section-title">Built around your actual admin and resident workflows</h2>
            <p class="section-copy">Everything below maps to routes currently implemented in your system: dues, payments, reservations, messaging, announcements, penalties, and reporting.</p>
        </div>

        <div class="ops-grid">
            <article class="ops-card reveal" style="transition-delay: 0.05s;">
                <i class="bi bi-cash-coin"></i>
                <h4>Dues Management</h4>
                <p>Batch due generation, payment marking, archive actions, and statements.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.1s;">
                <i class="bi bi-receipt"></i>
                <h4>Payment Review</h4>
                <p>Review proof submissions, approve or reject, and generate receipts.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.15s;">
                <i class="bi bi-chat-left-dots"></i>
                <h4>Support Messaging</h4>
                <p>Threaded resident-admin messaging with replies, status updates, and actions.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.2s;">
                <i class="bi bi-calendar2-check"></i>
                <h4>Amenity Reservations</h4>
                <p>Bookings, unavailable slot checks, rescheduling, and payment verification.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.25s;">
                <i class="bi bi-person-lines-fill"></i>
                <h4>Resident Records</h4>
                <p>Resident profiles, invitation workflow, account toggles, and exports.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.3s;">
                <i class="bi bi-exclamation-octagon"></i>
                <h4>Penalties and Requests</h4>
                <p>Manage violations, notices, and resident-submitted service requests.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.35s;">
                <i class="bi bi-megaphone"></i>
                <h4>Announcements</h4>
                <p>Publish, archive, pin, and track announcement visibility for residents.</p>
            </article>
            <article class="ops-card reveal" style="transition-delay: 0.4s;">
                <i class="bi bi-graph-up-arrow"></i>
                <h4>Reports and Logs</h4>
                <p>Operational reports plus activity log views and exports for auditing.</p>
            </article>
        </div>
    </section>

    <section class="modules-section" id="features">
        <div class="section-head reveal">
            <span class="section-eyebrow">Platform Capabilities</span>
            <h2 class="section-title">Operational modules by user role</h2>
            <p class="section-copy">Switch between Admin and Resident views to inspect how each side of the platform is delivered today.</p>
            <div class="audience-toggle" role="tablist" aria-label="Module Audience">
                <button class="toggle-chip active" type="button" data-view="all">All</button>
                <button class="toggle-chip" type="button" data-view="admin">Admin</button>
                <button class="toggle-chip" type="button" data-view="resident">Resident</button>
            </div>
        </div>

        <div class="modules-grid">
            <article class="module-card reveal" data-audience="admin" style="transition-delay: 0.05s;">
                <div class="module-top">
                    <i class="bi bi-layout-text-window-reverse"></i>
                    <span class="badge-role">Admin</span>
                </div>
                <h4>Collections Console</h4>
                <p>Control the full dues lifecycle from creation to payment confirmation.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> Dues batch dashboard</li>
                    <li><i class="bi bi-check2-circle"></i> Reminder dispatch actions</li>
                    <li><i class="bi bi-check2-circle"></i> Receipt and status updates</li>
                </ul>
            </article>

            <article class="module-card reveal" data-audience="admin" style="transition-delay: 0.1s;">
                <div class="module-top">
                    <i class="bi bi-people"></i>
                    <span class="badge-role">Admin</span>
                </div>
                <h4>Resident Administration</h4>
                <p>Manage records and onboarding from one workspace.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> Resident resource management</li>
                    <li><i class="bi bi-check2-circle"></i> Invitation and renewal flow</li>
                    <li><i class="bi bi-check2-circle"></i> Account toggles and resets</li>
                </ul>
            </article>

            <article class="module-card reveal" data-audience="admin" style="transition-delay: 0.15s;">
                <div class="module-top">
                    <i class="bi bi-building"></i>
                    <span class="badge-role">Admin</span>
                </div>
                <h4>Amenity Operations</h4>
                <p>Handle amenity bookings with full operational controls.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> Reservation lifecycle actions</li>
                    <li><i class="bi bi-check2-circle"></i> Maintenance mode toggles</li>
                    <li><i class="bi bi-check2-circle"></i> Payment verification and receipts</li>
                </ul>
            </article>

            <article class="module-card reveal" data-audience="resident" style="transition-delay: 0.2s;">
                <div class="module-top">
                    <i class="bi bi-wallet2"></i>
                    <span class="badge-role">Resident</span>
                </div>
                <h4>Dues and Payments</h4>
                <p>Residents can view balances, process payments, and access receipts.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> Dues list and statement download</li>
                    <li><i class="bi bi-check2-circle"></i> Payment flow with proof upload</li>
                    <li><i class="bi bi-check2-circle"></i> Resident receipt viewing</li>
                </ul>
            </article>

            <article class="module-card reveal" data-audience="resident" style="transition-delay: 0.25s;">
                <div class="module-top">
                    <i class="bi bi-chat-square-text"></i>
                    <span class="badge-role">Resident</span>
                </div>
                <h4>Resident Support Center</h4>
                <p>Create message threads, reply to support, and track updates.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> New message with categories</li>
                    <li><i class="bi bi-check2-circle"></i> Thread replies and attachments</li>
                    <li><i class="bi bi-check2-circle"></i> Notification-linked access</li>
                </ul>
            </article>

            <article class="module-card reveal" data-audience="resident" style="transition-delay: 0.3s;">
                <div class="module-top">
                    <i class="bi bi-bell"></i>
                    <span class="badge-role">Resident</span>
                </div>
                <h4>Community Engagement</h4>
                <p>Residents stay informed through announcements and notifications.</p>
                <ul class="module-list">
                    <li><i class="bi bi-check2-circle"></i> Announcement browsing</li>
                    <li><i class="bi bi-check2-circle"></i> Read tracking actions</li>
                    <li><i class="bi bi-check2-circle"></i> Notification center routes</li>
                </ul>
            </article>
        </div>
    </section>

    <section class="workflow">
        <div class="section-head reveal">
            <span class="section-eyebrow">End-to-End Flow</span>
            <h2 class="section-title">From resident request to admin resolution</h2>
            <p class="section-copy">The platform connects financial operations, communications, and amenities in one cohesive cycle.</p>
        </div>

        <div class="workflow-grid">
            <article class="workflow-step reveal" style="transition-delay: 0.05s;">
                <span>1</span>
                <h4>Resident Submits</h4>
                <p>Residents submit payments, support messages, reservation requests, or service concerns.</p>
            </article>
            <article class="workflow-step reveal" style="transition-delay: 0.1s;">
                <span>2</span>
                <h4>Admin Reviews</h4>
                <p>Administrators review transactions, update statuses, assign actions, and verify records.</p>
            </article>
            <article class="workflow-step reveal" style="transition-delay: 0.15s;">
                <span>3</span>
                <h4>System Notifies</h4>
                <p>Both sides receive updates through portal notifications and message thread progress.</p>
            </article>
        </div>
    </section>

    <section class="support-section" id="contact">
        <div class="support-panel reveal">
            <span class="section-eyebrow">Access and Support</span>
            <h2 class="section-title" style="font-size: clamp(1.6rem, 4vw, 2.4rem);">Need access or account help?</h2>
            <p class="section-copy">Use your available public routes to sign in, recover passwords, or start invitation-based onboarding.</p>
            <div class="support-links">
                <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Sign In</a>
                <a href="{{ route('password.request') }}"><i class="bi bi-shield-lock"></i> Forgot Password</a>
                <a href="{{ route('register.invitation') }}"><i class="bi bi-envelope-open"></i> Invitation Registration</a>
                <a href="{{ route('announcements.public') }}"><i class="bi bi-megaphone"></i> Public Announcements</a>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} SubdivMoneyTrack. Subdivision operations platform for admins and residents.</p>
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
            if (heroVideo) {
                heroVideo.style.transform = `scale(1.1) translateY(${scroll * 0.4}px)`;
            }
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

        // Interactive module audience filter
        const chips = document.querySelectorAll('.toggle-chip');
        const moduleCards = document.querySelectorAll('.module-card');

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach(btn => btn.classList.remove('active'));
                chip.classList.add('active');

                const view = chip.dataset.view;
                moduleCards.forEach((card) => {
                    const audience = card.dataset.audience;
                    const shouldShow = view === 'all' || audience === view;
                    card.classList.toggle('hidden-by-filter', !shouldShow);
                });
            });
        });
    </script>

</body>
</html>
