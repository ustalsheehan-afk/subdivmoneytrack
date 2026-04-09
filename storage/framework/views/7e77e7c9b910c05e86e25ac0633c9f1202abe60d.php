<?php $__env->startSection('content'); ?>

<style>
/* 
   STRIPE & LINEAR INSPIRED LOGIN 
   Design System: Cinematic Dark Green 
*/
:root {
    --bg-dark: #0D1F1C;
    --bg-darker: #081412;
    --brand-accent: #B6FF5C; /* Electric Green */
    --brand-accent-glow: rgba(182, 255, 92, 0.25);
    --text-main: #FFFFFF;
    --text-muted: #94A3B8;
    --glass-bg: rgba(255, 255, 255, 0.02);
    --glass-border: rgba(255, 255, 255, 0.08);
    --transition-smooth: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    --grain-opacity: 0.04;
}

body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background-color: var(--bg-darker);
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
    overflow: hidden;
    position: relative;
}

/* Cinematic Grain Overlay */
body::before {
    content: "";
    position: fixed;
    inset: 0;
    z-index: 9999;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3%3Ffilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
    opacity: var(--grain-opacity);
    mix-blend-mode: overlay;
}

.login-wrapper {
    display: flex;
    min-height: 100vh;
    width: 100%;
    position: relative;
}

/* LEFT SIDE: HERO (60%) */
.login-hero {
    flex: 1.5;
    position: relative;
    overflow: hidden;
    display: none; /* Mobile first */
}

@media (min-width: 1024px) {
    .login-hero { display: block; }
}

.hero-video {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    transform: translate(-50%, -50%);
    object-fit: cover;
    filter: brightness(0.35) contrast(1.1);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: 
        radial-gradient(circle at 50% 50%, rgba(182, 255, 92, 0.08) 0%, transparent 60%),
        linear-gradient(135deg, rgba(8, 20, 18, 0.2) 0%, rgba(8, 20, 18, 0.9) 100%);
    z-index: 1;
}

/* ORGANIC CURVED DIVIDER */
.curved-divider {
    position: absolute;
    top: 0;
    right: -1px;
    height: 100%;
    width: 250px;
    z-index: 5;
    pointer-events: none;
    color: var(--bg-darker);
    fill: currentColor;
}



/* FLOATING GLASS PANELS */
.floating-panel {
    position: absolute;
    z-index: 10;
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 1.5rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

.panel-1 { top: 15%; left: 10%; width: 280px; animation-delay: 0s; }
.panel-2 { bottom: 20%; left: 15%; width: 320px; animation-delay: 1s; }
.panel-3 { top: 45%; left: 40%; width: 240px; animation-delay: 2s; }

.panel-title {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--brand-accent);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.stat-label { font-size: 0.875rem; color: var(--text-muted); }
.stat-value { font-size: 0.875rem; font-weight: 700; color: var(--text-main); }

/* HERO CONTENT */
.hero-text-content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
    padding: 0 10%;
    text-align: center;
}

.hero-title {
    font-size: 4.5rem;
    font-weight: 900;
    letter-spacing: -0.04em;
    line-height: 0.95;
    margin-bottom: 1.5rem;
    text-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), 0 0 20px rgba(182, 255, 92, 0.1);
}

.hero-subtitle {
    font-size: 1.125rem;
    color: var(--text-muted);
    line-height: 1.6;
    max-width: 500px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

/* RIGHT SIDE: FORM (40%) */
.login-form-section {
    flex: 1;
    background-color: var(--bg-darker);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    z-index: 20;
}

.form-container {
    width: 100%;
    max-width: 420px;
    position: relative;
}

.brand-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3.5rem;
    text-align: center;
}

.brand-logo {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, var(--brand-accent), #8AC941);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px var(--brand-accent-glow);
}

.brand-logo i { color: var(--bg-darker); font-size: 1.25rem; }

.brand-name { font-weight: 800; font-size: 1.25rem; letter-spacing: -0.02em; }
.brand-name span { color: var(--brand-accent); }

.form-title { 
    font-size: 2.25rem; 
    font-weight: 800; 
    margin-bottom: 0.5rem; 
    letter-spacing: -0.02em;
    text-align: center;
}

.form-subtitle { 
    color: var(--text-muted); 
    margin-bottom: 2.5rem;
    text-align: center;
}

/* INPUT STYLES */
.form-group { margin-bottom: 1.5rem; }
.label-custom { display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-muted); margin-bottom: 0.75rem; }

.input-wrapper { position: relative; }

.input-custom {
    width: 100%;
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--glass-border);
    border-radius: 14px;
    padding: 1rem 1.25rem 1rem 3.25rem;
    color: var(--text-main);
    font-size: 1rem;
    transition: var(--transition-smooth);
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-custom:focus {
    outline: none;
    border-color: var(--brand-accent);
    background: rgba(255, 255, 255, 0.04);
    box-shadow: 0 0 0 4px var(--brand-accent-glow), inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.input-icon {
    position: absolute;
    left: 1.125rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 1.1rem;
    transition: var(--transition-smooth);
}

.input-custom:focus + .input-icon { color: var(--brand-accent); }

.password-toggle {
    position: absolute;
    right: 1rem; /* move closer to edge */
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    cursor: pointer;
    z-index: 2;
}

/* BUTTON */
.btn-login {
    width: auto; /* Change to auto for centering */
    min-width: 200px;
    margin-left: auto;
    margin-right: auto;
    background: var(--brand-accent);
    color: var(--bg-darker);
    font-weight: 800;
    font-size: 1rem;
    padding: 1.125rem 2.5rem;
    border-radius: 14px;
    border: none;
    margin-top: 2.5rem;
    cursor: pointer;
    transition: var(--transition-smooth);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    box-shadow: 0 10px 20px -5px var(--brand-accent-glow), 0 0 15px rgba(182, 255, 92, 0.1);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -5px var(--brand-accent-glow), 0 0 20px rgba(182, 255, 92, 0.2);
    background: #c7ff80;
}

.form-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
}

.remember-me { display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-size: 0.875rem; cursor: pointer; }
.remember-me input { width: 16px; height: 16px; border-radius: 4px; border: 1px solid var(--glass-border); background: transparent; cursor: pointer; }

.forgot-link { color: var(--brand-accent); font-size: 0.875rem; font-weight: 600; text-decoration: none; transition: var(--transition-smooth); }
.forgot-link:hover { opacity: 0.8; }

.signup-footer { text-align: center; margin-top: 3rem; color: var(--text-muted); font-size: 0.875rem; }
.signup-footer a { color: var(--brand-accent); text-decoration: none; font-weight: 700; margin-left: 0.25rem; }

/* ANIMATIONS */
@keyframes revealUp {
    from { 
        opacity: 0; 
        transform: translateY(20px) scale(0.98); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1); 
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-reveal { 
    animation: revealUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
    opacity: 0; 
    will-change: transform, opacity;
}

.animate-fade {
    animation: fadeIn 1s ease forwards;
    opacity: 0;
}

.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }
.delay-5 { animation-delay: 0.5s; }
.delay-6 { animation-delay: 0.6s; }
.delay-7 { animation-delay: 0.7s; }

/* RESPONSIVE */
@media (max-width: 1023px) {
    .login-wrapper { flex-direction: column; overflow: auto; }
    .login-form-section { min-height: 100vh; padding: 4rem 1.5rem; }
    .form-container { max-width: 100%; }
}

@media (max-width: 768px) {
    body { overflow: auto; }
    
    .login-wrapper {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
    }

    .login-form-section {
        padding: 3rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-container {
        max-width: 100%;
        padding: 0;
    }

    /* Scale Down Branding & Titles */
    .brand-header {
        margin-bottom: 2rem;
        justify-content: center;
    }

    .form-title {
        font-size: 1.75rem;
        text-align: center;
    }

    .form-subtitle {
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 2rem;
    }

    /* Compact Form Elements */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .input-custom {
        padding: 0.875rem 1rem 0.875rem 3rem;
        font-size: 0.95rem;
    }

    .btn-login {
        padding: 1rem;
        margin-top: 2rem;
        font-size: 0.95rem;
    }

    /* Stack Footer Elements */
    .form-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
        margin-top: 1.25rem;
    }

    .forgot-link {
        font-size: 0.875rem;
    }

    .signup-footer {
        margin-top: 2.5rem;
    }

    /* Back Button Positioning */
    .back-btn {
        top: 1.5rem;
        left: 1.5rem;
    }
}

/* Autofill Hack */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus {
    -webkit-text-fill-color: var(--text-main) !important;
    -webkit-box-shadow: 0 0 0px 1000px var(--bg-darker) inset !important;
    transition: background-color 5000s ease-in-out 0s;
}
</style>

<div class="login-wrapper animate-fade">
    <!-- LEFT SIDE -->
    <div class="login-hero">
        <video class="hero-video" autoplay muted loop playsinline preload="auto">
            <source src="<?php echo e(asset('images/landing-page.mp4')); ?>" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>

        <!-- Organic Curved Divider -->
        <svg class="curved-divider" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M100 0 C 40 30, 60 70, 100 100 Z"></path>
        </svg>

     

        <div class="hero-text-content">
            <h1 class="hero-title animate-reveal delay-1">Welcome<br>Back</h1>
            <p class="hero-subtitle animate-reveal delay-2">Access your subdivision management dashboard and stay connected with your community.</p>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="login-form-section">
        <div class="form-container">
            <div class="brand-header animate-reveal delay-1">
                <div class="brand-logo">
                    <i class="bi bi-buildings-fill"></i>
                </div>
                <div class="brand-name">Subdiv<span>Management</span></div>
            </div>

            <h2 class="form-title animate-reveal delay-2">Login</h2>
            <p class="form-subtitle animate-reveal delay-3">Enter your credentials to continue</p>

            <form action="<?php echo e(route('login')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="form-group animate-reveal delay-4">
                    <label class="label-custom">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" name="email" class="input-custom" placeholder="Enter your email" required autofocus>
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-[11px] mt-2 font-bold"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group animate-reveal delay-5">
                    <label class="label-custom">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="input-custom" placeholder="Enter your password" required>
                        <i class="bi bi-lock input-icon"></i>
                        <i class="bi bi-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-footer animate-reveal delay-6">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="<?php echo e(route('password.request')); ?>" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login animate-reveal delay-7">
                    Sign In <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <div class="signup-footer animate-reveal delay-7">
                Don't have an account? <a href="#">Contact Admin</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Sheehan\subdivision-dues-system-final\resources\views/auth/login.blade.php ENDPATH**/ ?>