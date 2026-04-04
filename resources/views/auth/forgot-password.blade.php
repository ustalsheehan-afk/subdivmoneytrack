@extends('layouts.guest')

@section('content')
<style>
:root {
    --bg-dark: #0D1F1C;
    --bg-darker: #081412;
    --brand-accent: #B6FF5C;
    --text-main: #FFFFFF;
    --text-muted: #94A3B8;
    --glass-border: rgba(255, 255, 255, 0.08);
    --glass-bg: rgba(255, 255, 255, 0.03);
}

body {
    margin: 0;
    min-height: 100vh;
    background: radial-gradient(circle at top, rgba(182, 255, 92, 0.08), transparent 35%), var(--bg-darker);
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
}

.reset-shell {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
}

.reset-card {
    width: 100%;
    max-width: 460px;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
    backdrop-filter: blur(18px);
}

.eyebrow {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: var(--brand-accent);
}

.title {
    font-size: 2rem;
    font-weight: 800;
    margin: 0.75rem 0 0.5rem;
}

.subtitle {
    color: var(--text-muted);
    line-height: 1.6;
    margin-bottom: 1.75rem;
}

.field {
    margin-bottom: 1rem;
}

.label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--text-muted);
}

.input {
    width: 100%;
    padding: 0.95rem 1rem;
    border-radius: 14px;
    border: 1px solid var(--glass-border);
    background: rgba(255, 255, 255, 0.03);
    color: var(--text-main);
}

.input:focus {
    outline: none;
    border-color: var(--brand-accent);
    box-shadow: 0 0 0 4px rgba(182, 255, 92, 0.15);
}

.alert {
    border-radius: 14px;
    padding: 0.9rem 1rem;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.alert-success {
    background: rgba(34, 197, 94, 0.12);
    border: 1px solid rgba(34, 197, 94, 0.28);
    color: #dcfce7;
}

.alert-error {
    background: rgba(239, 68, 68, 0.12);
    border: 1px solid rgba(239, 68, 68, 0.28);
    color: #fee2e2;
}

.btn {
    width: 100%;
    border: 0;
    border-radius: 14px;
    padding: 1rem 1.25rem;
    font-weight: 800;
    background: var(--brand-accent);
    color: #081412;
    cursor: pointer;
    margin-top: 0.5rem;
}

.helper-link {
    display: inline-block;
    margin-top: 1rem;
    color: var(--brand-accent);
    text-decoration: none;
    font-weight: 700;
}
</style>

<div class="reset-shell">
    <div class="reset-card">
        <div class="eyebrow">Account Recovery</div>
        <h1 class="title">Forgot your password?</h1>
        <p class="subtitle">Enter the email address tied to your account and we’ll send a secure reset link if it exists in our system.</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label class="label" for="email">Email address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="input"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    autocapitalize="off"
                    autocorrect="off"
                    spellcheck="false"
                    inputmode="email"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn">Send Reset Link</button>
        </form>

        <a href="{{ route('login') }}" class="helper-link">Back to login</a>
    </div>
</div>
@endsection
