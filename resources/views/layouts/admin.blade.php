<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Kopi Ancua Harmoni</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        /* Custom Industrial Theme overriding Bootstrap */
        :root {
            --bs-primary: #a80006;
            --bs-primary-rgb: 168, 0, 6;
            --bs-font-sans-serif: 'Inter', sans-serif;
            --bs-body-bg: #F3F4F6;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bs-body-bg);
            color: #291714;
        }
        
        h1, h2, h3, h4, h5, h6, .text-oswald, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }
        
        /* 0px Border Radius */
        * {
            border-radius: 0 !important;
        }
        
        /* Primary Colors */
        .btn-primary {
            background-color: #a80006;
            border-color: #a80006;
            color: #fff;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #7a0004 !important;
            border-color: #7a0004 !important;
        }
        
        .text-primary { color: #a80006 !important; }
        .bg-primary { background-color: #a80006 !important; }
        
        /* Icons */
        .material-symbols-outlined {
            vertical-align: middle;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #a80006; }
        
        /* Sidebar layout styles */
        .admin-sidebar {
            width: 250px;
            background-color: #291714;
            color: #fff;
            position: fixed;
            height: 100vh;
            z-index: 1040;
            display: flex;
            flex-direction: column;
        }
        .admin-sidebar a {
            color: #d1d5db;
            text-decoration: none;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .admin-sidebar a:hover, .admin-sidebar a.active {
            background-color: #374151;
            color: #fff;
        }
        .admin-sidebar a.active {
            background-color: #a80006;
        }
        
        .admin-main {
            margin-left: 250px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .admin-header {
            background-color: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .admin-content {
            padding: 2rem;
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="p-4 border-bottom border-secondary">
                <h1 class="fs-5 fw-bold text-primary mb-0">KOPI ANCUA HARMONI</h1>
                <p class="text-secondary small mb-0">Admin Panel</p>
            </div>
            
            <nav class="flex-grow-1 py-3 overflow-auto">
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                
                <div class="px-4 pt-3 pb-2 text-secondary small fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">MASTER DATA</div>
                <a href="{{ route('admin.menu.index') }}" class="{{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">restaurant_menu</span> Menu
                </a>
                <a href="{{ route('admin.category.index') }}" class="{{ request()->routeIs('admin.category.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">category</span> Kategori
                </a>
                <a href="{{ route('admin.kasir.index') }}" class="{{ request()->routeIs('admin.kasir.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">badge</span> Data Kasir
                </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                <div class="px-4 pt-3 pb-2 text-secondary small fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">KEUANGAN</div>
                <a href="{{ route('admin.report') }}" class="{{ request()->routeIs('admin.report') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">bar_chart</span> Laporan
                </a>
                @endif
                
                <hr class="border-secondary my-3 mx-3">

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-danger">
                    <span class="material-symbols-outlined">logout</span> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </nav>
            
            <div class="p-3 border-top border-secondary">
                <div class="text-secondary small" style="font-size: 0.7rem;">LOGGED IN AS</div>
                <div class="text-white fw-bold text-truncate">{{ auth()->user()->name }}</div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main flex-grow-1 w-100">
            <!-- Top Bar -->
            <div class="admin-header shadow-sm">
                <h2 class="fs-4 mb-0 fw-bold">@yield('page-title', 'Admin Panel')</h2>
                <div class="d-flex align-items-center gap-3">
                    @if(session('success'))
                    <span class="badge bg-success text-oswald fs-6 py-2">{{ session('success') }}</span>
                    @endif
                    @if(session('error'))
                    <span class="badge bg-danger text-oswald fs-6 py-2">{{ session('error') }}</span>
                    @endif
                    @yield('top-action')
                </div>
            </div>
            
            <!-- Page Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
