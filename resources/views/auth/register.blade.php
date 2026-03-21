@extends('layouts.app')

@section('content')

<style>

:root{
    --primary:#1F3B5C;
    --primary-dark:#162b44;
    --accent:#2E5B8A;
}

body{
    margin:0;
    padding:0;
    height:100vh;
    background:
        linear-gradient(rgba(15,42,68,0.65), rgba(15,42,68,0.65)),
        url('{{ asset('bg.png') }}') center/cover no-repeat fixed;
    font-family:'Poppins', sans-serif;
}

.register-card{
    width:420px;
    background:rgba(255,255,255,0.95);
    backdrop-filter:blur(10px);
    border-radius:18px;
    box-shadow:0 15px 35px rgba(0,0,0,0.25);
    padding:2.4rem 2.2rem;
    animation:fadeUp .6s ease;
}

.register-logo{
    width:70px;
    height:70px;
    border-radius:50%;
    object-fit:cover;
    box-shadow:0 0 18px rgba(31,59,92,0.45);
}

.form-control{
    border-radius:12px;
    height:44px;
    font-size:0.9rem;
    border:1px solid #e0e6ed;
}

.form-control:focus{
    border-color:var(--primary);
    box-shadow:0 0 0 0.2rem rgba(31,59,92,0.15);
}

.input-group-text{
    background:transparent;
    border-left:0;
    cursor:pointer;
}

.password-wrapper{
    position:relative;
}

.password-toggle{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    color:#6c757d;
}

.btn-register{
    background:var(--primary);
    border:none;
    border-radius:12px;
    padding:10px;
    font-weight:600;
    transition:.25s;
}

.btn-register:hover{
    background:var(--primary-dark);
    transform:translateY(-2px);
    box-shadow:0 8px 18px rgba(0,0,0,0.25);
}

.link-login{
    color:var(--primary);
    font-weight:600;
}

.link-login:hover{
    color:var(--accent);
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@media(max-width:450px){
    .register-card{
        width:92%;
    }
}

</style>


<div class="d-flex justify-content-center align-items-center min-vh-100">

    <div class="register-card">

        <div class="text-center mb-4">
            <img src="{{ asset('Cdlogo.jpg') }}" class="register-logo mb-3">
            <h4 class="fw-bold" style="color:var(--primary)">Create Account</h4>
            <p class="text-muted small">Set your password to activate your account</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger small text-center py-2">
                {{ $errors->first() }}
            </div>
        @endif


        <form method="POST" action="/register">
            @csrf

            <!-- NAME -->
            <div class="mb-3">
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Full Name"
                       value="{{ old('name') }}"
                       required>
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Email Address"
                       value="{{ old('email') }}"
                       required>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3 password-wrapper">
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control"
                       placeholder="Password"
                       required>

                <i class="bi bi-eye password-toggle"
                   onclick="togglePassword('password', this)"></i>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-4 password-wrapper">
                <input type="password"
                       name="password_confirmation"
                       id="confirm_password"
                       class="form-control"
                       placeholder="Confirm Password"
                       required>

                <i class="bi bi-eye password-toggle"
                   onclick="togglePassword('confirm_password', this)"></i>
            </div>


            <button class="btn btn-register w-100 text-white">
                <i class="bi bi-person-plus me-1"></i>
                Create Account
            </button>

        </form>


        <div class="text-center mt-4">
            <small class="text-muted">
                Already have an account?
            </small>
            <br>
            <a href="/login" class="link-login text-decoration-none">
                Login here
            </a>
        </div>

    </div>

</div>


<script>

function togglePassword(fieldId, icon){

    const field = document.getElementById(fieldId);

    if(field.type === "password"){
        field.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }else{
        field.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    }

}

</script>

@endsection