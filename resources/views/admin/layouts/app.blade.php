<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @if ($settings?->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $settings->favicon_path) }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f1f5f9;
        }

        .sidebar {
            min-height: 100vh;
            background: #0f172a;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            padding: 10px 12px;
            border-radius: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1e293b;
            color: #fff;
        }

        .content {
            min-height: 100vh;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            @include('admin.partials.sidebar')
            <div class="col-lg-10 content p-0">
                @include('admin.partials.topbar')
                <div class="p-4">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
