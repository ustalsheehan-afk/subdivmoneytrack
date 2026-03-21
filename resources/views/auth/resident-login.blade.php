@extends('layouts.guest')

@section('content')

<style>
/* SaaS Design System */
:root {
    --brand-primary: #1F3B5C;
    --brand-accent: #3B82F6;
    --brand-bg-overlay: rgba(15, 42, 68, 0.85);
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
    font-family: 'Poppins', sans-serif;
}

.back-to-landing {
    position: absolute;
    top: 2rem;
    left: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: white;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    padding: 0.625rem 1.25rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    z-index: 100;
}

.back-to-landing:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateX(-5px);
    color: white;
}

.login-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
}

.glass-card {
    width: 100%;
    max-width: 440px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-radius: 28px;
    box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.4);
    padding: 3.5rem 2.5rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.card-logo {
    width: 90px;
    height: 90px;
    border-radius: 24px;
    margin-bottom: 2rem;
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
}

.page-title {
    color: var(--brand-primary);
    font-weight: 800;
    font-size: 1.85rem;
    margin-bottom: 0.75rem;
    letter-spacing: -0.02em;
}

.page-subtitle {
    color: #64748b;
    font-size: 0.95rem;
    margin-bottom: 3rem;
}

.form-group-custom {
    position: relative;
    margin-bottom: 1.5rem;
    text-align: left;
}

.input-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1.1rem;
}

.form-control-custom {
    width: 100%;
    height: 56px;
    background: #f1f5f9;
    border: 2px solid transparent;
    border-radius: 16px;
    padding: 0 1.25rem 0 3.5rem;
    font-size: 1rem;
    color: #1e293b;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-control-custom:focus {
    outline: none;
    border-color: var(--brand-primary);
    background: white;
    box-shadow: 0 0 0 4px rgba(31, 59, 92, 0.1);
}

.btn-brand {
    width: 100%;
    height: 56px;
    background: var(--brand-primary);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 1rem;
    cursor: pointer;
    box-shadow: 0 10px 20px -5px rgba(31, 59, 92, 0.3);
}

.btn-brand:hover {
    background: #162b44;
    transform: translateY(-3px);
    box-shadow: 0 15px 25px -5px rgba(31, 59, 92, 0.4);
}

.btn-brand:active {
    transform: translateY(-1px);
}

.auth-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 0 0.5rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.9rem;
    cursor: pointer;
}

.remember-me input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.forgot-link {
    color: var(--brand-primary);
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: color 0.2s;
}

.forgot-link:hover {
    color: var(--brand-accent);
    text-decoration: underline;
}
</style>

<div class="login-container">
    <a href="{{ url('/') }}" class="back-to-landing">
        <i class="bi bi-arrow-left"></i>
        <span>Back to Home</span>
    </a>

    <div class="glass-card text-center">
        <img src="{{ asset('Cdlogo.jpg') }}" alt="Logo" class="card-logo">
        <h1 class="page-title">Resident Portal</h1>
        <p class="page-subtitle">Sign in to manage your subdivision dues and more.</p>

        @if ($errors->any())
            <div class="alert alert-danger border-0 small rounded-3 mb-4 py-3 bg-red-50 text-red-600 text-start">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('resident.login.submit') }}">
            @csrf
            <div class="form-group-custom">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" name="email" class="form-control-custom" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group-custom">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" name="password" class="form-control-custom" placeholder="Enter your password" required>
            </div>

            <div class="auth-footer">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('resident.password.request') }}" class="forgot-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-brand">Sign In</button>
        </form>
    </div>
</div>
@endsection
