<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6fb;
            color: #1f2937;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.98);
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        }

        .navbar-brand {
            font-weight: 700;
        }

        .nav-link {
            color: #334155 !important;
        }

        .nav-link.active {
            color: #0d6efd !important;
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .card.shadow-sm {
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        }

        footer {
            background: #0b1324;
            color: #cbd5e1;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        footer a {
            color: #cbd5e1;
        }

        footer a:hover {
            color: #f39c12;
        }

        main {
            padding-top: 1.5rem;
            padding-bottom: 2rem;
        }
    </style>
    @stack('styles')
</head>

<body>
    @if (session('success') || session('error'))
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100;">
            <div class="toast align-items-center text-bg-{{ session('success') ? 'success' : 'danger' }} border-0 show"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') ?? session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    @include('user.partials.navbar')

    <main class="container">
        @yield('content')
    </main>

    @include('frontend.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
