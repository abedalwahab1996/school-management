<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'School Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: .5px;
        }
        .navbar-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            box-shadow: 0 2px 12px rgba(0,0,0,.15);
        }
        .nav-link {
            font-weight: 500;
            padding: .5rem 1rem !important;
            border-radius: .375rem;
            transition: all .2s;
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,.12);
        }
        .page-wrapper {
            flex: 1;
        }
        .page-header {
            background: #fff;
            border-radius: .75rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .card-custom {
            border: none;
            border-radius: .75rem;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            transition: box-shadow .2s;
        }
        .card-custom:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,.1);
        }
        .table-custom {
            border-radius: .75rem;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
        }
        .table-custom thead th {
            background: #1e293b;
            color: #fff;
            font-weight: 600;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            border-bottom: none;
        }
        .table-custom tbody tr {
            transition: background .15s;
        }
        .table-custom tbody tr:hover {
            background: #f1f5f9;
        }
        .stat-card {
            border: none;
            border-radius: .75rem;
            padding: 1.25rem;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,.1);
        }
        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: .85;
        }
        .stat-card .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
        }
        .stat-card .stat-label {
            font-size: .85rem;
            opacity: .9;
        }
        .bg-stat-teachers { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
        .bg-stat-students { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .bg-stat-subjects { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .btn-action {
            padding: .25rem .6rem;
            font-size: .8rem;
            border-radius: .375rem;
        }
        .footer-custom {
            background: #fff;
            border-top: 1px solid #e2e8f0;
            padding: 1rem 0;
            margin-top: auto;
            font-size: .85rem;
            color: #64748b;
        }
        .form-control, .form-select {
            border-radius: .5rem;
            border: 1.5px solid #e2e8f0;
            padding: .55rem .9rem;
            transition: border .2s, box-shadow .2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        .btn-rounded {
            border-radius: .5rem;
            font-weight: 600;
            padding: .5rem 1.25rem;
        }
        .empty-state {
            padding: 3rem 1rem;
            color: #94a3b8;
        }
        .empty-state i {
            font-size: 3rem;
            display: block;
            margin-bottom: .75rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-gradient sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>School Management
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
                            <i class="bi bi-people-fill me-1"></i>Teachers
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">
                            <i class="bi bi-person-vcard-fill me-1"></i>Students
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}">
                            <i class="bi bi-book-fill me-1"></i>Subjects
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/students/pdf') }}" target="_blank">
                            <i class="bi bi-filetype-pdf me-1"></i>PDF Report
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="page-wrapper">
        <div class="container py-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 d-inline-block ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <footer class="footer-custom text-center">
        &copy; {{ date('Y') }} School Management System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
