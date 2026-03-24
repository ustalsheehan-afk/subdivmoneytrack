<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Subdivision Dues System' }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background-color: #ffffff;
            color: #1A202C;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border: 1px solid #E2E8F0;
        }
        .btn-primary {
            background-color: #0069d9;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0053b3;
        }
        .navbar-brand {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="/">Subdivision Dues System</a>

            <!-- Right side links -->
            <div class="d-flex align-items-center">
                @php
                    // Determine which guard is logged in
                    $guard = Auth::guard('admin')->check() ? 'admin' :
                             (Auth::guard('resident')->check() ? 'resident' : null);
                    $user = $guard ? Auth::guard($guard)->user() : null;
                @endphp

                @if($user)
                    <span class="text-light me-3">
                        {{ ucfirst($guard) }}: {{ $user->name }}
                    </span>

                    <!-- Universal Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light btn-sm" type="submit">
                            Logout
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="container py-5">
        @yield('content')
    </div>
</body>
</html>
