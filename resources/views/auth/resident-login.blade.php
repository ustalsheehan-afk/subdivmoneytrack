@extends('layouts.app')

@section('content')

<style>
/* SaaS Design System */
:root {
    --brand-primary: #1F3B5C;
    --brand-accent: #2E5B8A;
    --brand-bg-overlay: rgba(15, 42, 68, 0.75);
    --input-border: #e5e7eb;
    --input-focus: rgba(31, 59, 92, 0.15);
}

body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    background: linear-gradient(var(--brand-bg-overlay), var(--brand-bg-overlay)),
                url('{{ asset('bg.png') }}') no-repeat center center fixed;
    background-size: cover;
    font-family: 'Poppins', 'Inter', sans-serif;
}

/* Navigation Bar */
.navbar-brand-custom {
    padding: 1.5rem 2rem;
    width: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.navbar-logo-text {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: -0.02em;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

/* Centered Container */
.login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

/* Glassmorphism Card */
.glass-card {
    width: 440px;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.35);
    padding: 3rem 2.5rem;
    animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Branding */
.card-logo {
    width: 72px;
    height: 72px;
    border-radius: 16px;
    object-fit: cover;
    margin-bottom: 1.5rem;
}

.page-title {
    color: var(--brand-primary);
    font-weight: 800;
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
}

.page-subtitle {
    color: #64748b;
    font-size: 0.95rem;
    margin-bottom: 2.5rem;
}

/* Form Styling */
.form-group-custom {
    position: relative;
    margin-bottom: 1.25rem;
}

.form-group-custom i.input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    z-index: 10;
}

.form-control-custom {
    width: 100%;
    height: 48px;
    background: #ffffff;
    border: 1px solid var(--input-border);
    border-radius: 12px;
    padding: 0 1.25rem 0 3.25rem;
    font-size: 0.95rem;
    color: #1e293b;
    transition: all 0.25s ease;
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px var(--input-focus);
}

/* Password Toggle */
.password-toggle-icon {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #94a3b8;
    font-size: 1.1rem;
    z-index: 10;
    transition: color 0.2s ease;
}

.password-toggle-icon:hover {
    color: var(--brand-primary);
}

/* Button */
.btn-brand {
    width: 100%;
    height: 48px;
    background: var(--brand-primary);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.25s ease;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-brand:hover {
    background: #162b44;
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(31, 59, 92, 0.3);
}

.btn-brand:active {
    transform: translateY(0);
}

/* Utility */
.form-check-input:checked {
    background-color: var(--brand-primary);
    border-color: var(--brand-primary);
}

.forgot-link {
    color: var(--brand-primary);
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: color 0.2s ease;
}

.forgot-link:hover {
    color: var(--brand-accent);
}

/* Animations */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 480px) {
    .glass-card {
        width: 92%;
        padding: 2.5rem 1.75rem;
    }
    .navbar-brand-custom {
        padding: 1.25rem 1.5rem;
    }
    .page-title {
        font-size: 1.5rem;
    }
}
</style>



<div class="login-container">
    <div class="glass-card text-center">

        <!-- Branding -->
        <img src="{{ asset('Cdlogo.jpg') }}" alt="Logo" class="card-logo shadow-sm">
        <h1 class="page-title">Resident Login</h1>
        <p class="page-subtitle">Welcome back! Please sign in to your portal.</p>

        <!-- Error Handling -->
        @if ($errors->any())
            <div class="alert alert-danger border-0 small text-center rounded-3 mb-4 py-2 bg-red-50 text-red-600">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('resident.login.submit') }}">
            @csrf

            <!-- Email -->
            <div class="form-group-custom">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" id="resident-email"
                       class="form-control-custom @error('email') is-invalid @enderror"
                       placeholder="Email address"
                       value="{{ old('email') }}"
                       required autofocus>
            </div>

            <!-- Password -->
            <div class="form-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" id="resident-password"
                       class="form-control-custom @error('password') is-invalid @enderror"
                       placeholder="Password"
                       required>
                <i class="bi bi-eye password-toggle-icon" id="togglePassword"></i>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="resident-remember">
                    <label class="form-check-label small text-muted" for="resident-remember">Remember me</label>
                </div>
                @if (Route::has('resident.password.request'))
                    <a class="forgot-link" href="{{ route('resident.password.request') }}">
                        Forgot Password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-brand">
                <span>Sign In</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('resident-password');
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    this.classList.toggle('bi-eye');
    this.classList.toggle('bi-eye-slash');
});
</script>

@endsection
