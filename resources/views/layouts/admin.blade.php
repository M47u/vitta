<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Vitta Perfumes</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0A0A0A;
            color: #F8F5F0;
        }

        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: #1A1A1A;
            border-right: 1px solid rgba(212, 175, 55, 0.2);
            overflow-y: auto;
            z-index: 100;
        }

        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .admin-header {
            background: rgba(26, 26, 26, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            padding: 20px 32px;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .admin-content {
            padding: 32px;
        }

        .sidebar-logo {
            padding: 24px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            text-align: center;
        }

        .sidebar-menu {
            padding: 24px 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: #F8F5F0;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: rgba(212, 175, 55, 0.05);
            border-left-color: #D4AF37;
        }

        .sidebar-item.active {
            background: rgba(212, 175, 55, 0.1);
            border-left-color: #D4AF37;
            color: #D4AF37;
        }

        .sidebar-item i {
            font-size: 18px;
            margin-right: 12px;
            width: 20px;
        }

        .stat-card {
            background: #1A1A1A;
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            padding: 24px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: #D4AF37;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.15);
        }

        .btn-primary {
            background: linear-gradient(135deg, #D4AF37, #B8941F);
            color: #0A0A0A;
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #D4AF37;
            border: 2px solid #D4AF37;
            padding: 10px 22px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: rgba(212, 175, 55, 0.1);
        }

        .table-vitta {
            width: 100%;
            background: #1A1A1A;
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-collapse: collapse;
        }

        .table-vitta th {
            background: rgba(212, 175, 55, 0.1);
            color: #D4AF37;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.3);
        }

        .table-vitta td {
            padding: 16px;
            border-bottom: 1px solid rgba(212, 175, 55, 0.1);
            color: #F8F5F0;
        }

        .table-vitta tr:hover {
            background: rgba(212, 175, 55, 0.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid #22c55e;
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.2);
            color: #fbbf24;
            border: 1px solid #fbbf24;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .badge-info {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
            border: 1px solid #3b82f6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #D4AF37;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: #0A0A0A;
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: #F8F5F0;
            border-radius: 4px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .card-admin {
            background: #1A1A1A;
            border: 1px solid rgba(212, 175, 55, 0.2);
            border-radius: 8px;
            padding: 24px;
        }

        .card-header {
            border-bottom: 1px solid rgba(212, 175, 55, 0.2);
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 20px;
            color: #D4AF37;
            font-weight: 600;
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-logo">
            <h2 style="color: #D4AF37; font-size: 24px; letter-spacing: 2px;">VITTA</h2>
            <p style="color: rgba(212, 175, 55, 0.7); font-size: 11px; letter-spacing: 2px;">ADMIN PANEL</p>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Productos</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i>
                <span>Pedidos</span>
            </a>

            <div style="border-top: 1px solid rgba(212, 175, 55, 0.2); margin: 16px 0;"></div>

            <a href="{{ route('home') }}" class="sidebar-item">
                <i class="bi bi-globe"></i>
                <span>Ver Tienda</span>
            </a>

            <form action="{{ route('home') }}" method="GET">
                <button type="submit" class="sidebar-item"
                    style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">

        <!-- Header -->
        <header class="admin-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 style="font-size: 24px; color: #F8F5F0; font-weight: 600;">@yield('header-title', 'Dashboard')
                    </h1>
                    <p style="color: rgba(248, 245, 240, 0.6); font-size: 14px; margin-top: 4px;">
                        @yield('header-subtitle', 'Bienvenido al panel de administración')</p>
                </div>

                <div style="display: flex; align-items: center; gap: 24px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 40px; height: 40px; background: rgba(212, 175, 55, 0.2); border: 2px solid #D4AF37; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person" style="color: #D4AF37; font-size: 18px;"></i>
                        </div>
                        <div>
                            <p style="font-weight: 600; font-size: 14px;">{{ auth()->user()->name }}</p>
                            <p style="font-size: 12px; opacity: 0.7;">Administrador</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">

            @if(session('success'))
                <div
                    style="background: rgba(34, 197, 94, 0.1); border: 1px solid #22c55e; border-radius: 6px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                    <i class="bi bi-check-circle" style="color: #22c55e; font-size: 20px;"></i>
                    <span style="color: #22c55e;">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div
                    style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: 6px; padding: 16px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                    <i class="bi bi-exclamation-circle" style="color: #ef4444; font-size: 20px;"></i>
                    <span style="color: #ef4444;">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>

    </main>

    @stack('scripts')

</body>

</html>