@extends('layouts.app')

@section('content')

<style>
/* Premium Modern Background */
body {
    margin: 0;
    padding: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(30, 41, 59, 0.8)),
                url('{{ asset('bg.png') }}') no-repeat center center fixed;
    background-size: cover;
    color: #fff;
    font-family: 'Inter', 'Poppins', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Glassmorphism Card */
.premium-card {
    width: 450px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 24px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    padding: 3rem 2.5rem;
    color: #1e293b;
    animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    margin: 2rem 0;
}

/* Typography */
.premium-title {
    font-size: 1.75rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    background: linear-gradient(to right, #1e40af, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 0.5rem;
}

.premium-subtitle {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 2rem;
}

/* Input Group with Icons */
.input-group-premium {
    position: relative;
    margin-bottom: 1.25rem;
}

.input-group-premium i.input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
    transition: color 0.3s ease;
    z-index: 10;
}

.form-control-premium {
    width: 100%;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.875rem 1.25rem 0.875rem 3.25rem;
    font-size: 0.95rem;
    color: #1e293b;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
}

.form-control-premium:focus {
    background-color: #fff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-control-premium:focus + i.input-icon {
    color: #3b82f6;
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
    transition: color 0.3s ease;
    z-index: 10;
}

.password-toggle-icon:hover {
    color: #64748b;
}

/* Button Premium */
.btn-premium {
    width: 100%;
    background: linear-gradient(135deg, #1e40af, #3b82f6);
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 0.875rem;
    font-weight: 600;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
    margin-top: 1rem;
}

.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.4);
    filter: brightness(110%);
}

/* Logo Shadow */
.premium-logo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    border: 4px solid #fff;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

/* Animations */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

@media (max-width: 480px) {
    .premium-card {
        width: 90%;
        padding: 2.5rem 1.5rem;
    }
}
</style>

<div class="premium-card">

    <!-- Logo & Header -->
    <div class="text-center">
        <img src="{{ asset('Cdlogo.jpg') }}" alt="Logo" class="premium-logo">
        <h1 class="premium-title text-center">Activate Account</h1>
        <p class="premium-subtitle">Complete your registration to get started</p>
    </div>

    <!-- Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 small text-center rounded-3 mb-4 py-2 bg-red-50 text-red-600">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('resident.register.submit') }}">
        @csrf

        <!-- Name -->
        <div class="input-group-premium">
            <i class="bi bi-person input-icon"></i>
            <input type="text" name="name" class="form-control-premium"
                   placeholder="Full Name" value="{{ old('name') }}" required>
        </div>

        <!-- Email -->
        <div class="input-group-premium">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" name="email" class="form-control-premium"
                   placeholder="Email Address" value="{{ old('email') }}" required>
        </div>

        <!-- Password -->
        <div class="input-group-premium">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" name="password" id="password"
                   class="form-control-premium" placeholder="Create Password" required>
            <i class="bi bi-eye password-toggle-icon" onclick="togglePass('password', this)"></i>
        </div>

        <!-- Confirm Password -->
        <div class="input-group-premium">
            <i class="bi bi-lock-check input-icon"></i>
            <input type="password" name="password_confirmation" id="confirm_password"
                   class="form-control-premium" placeholder="Confirm Password" required>
            <i class="bi bi-eye password-toggle-icon" onclick="togglePass('confirm_password', this)"></i>
        </div>

        <button type="submit" class="btn-premium">
            <span>Create Account</span>
            <i class="bi bi-person-check"></i>
        </button>
    </form>

    <div class="text-center mt-4">
        <p class="small text-slate-500 mb-0">
            Already have an account? 
            <a href="{{ route('resident.login') }}" class="font-semibold text-blue-600 hover:text-blue-700 text-decoration-none ms-1">
                Login here
            </a>
        </p>
    </div>

</div>

<script>
function togglePass(id, icon) {
    const input = document.getElementById(id);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
}
</script>

@endsection
