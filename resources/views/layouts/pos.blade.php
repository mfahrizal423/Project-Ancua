<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'KOPI ANCUA')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;700&family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <style>
        /* Custom Industrial Theme overriding Bootstrap */
        :root {
            --bs-primary: #a80006;
            --bs-primary-rgb: 168, 0, 6;
            --bs-font-sans-serif: 'Inter', sans-serif;
        }
        
        body {
            background-color: #f8f9fa; /* light background outside container */
            font-family: 'Inter', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6, .text-oswald, .navbar-brand {
            font-family: 'Oswald', sans-serif;
            text-transform: uppercase;
        }
        
        /* Global reset (removed border-radius: 0 to soften design) */
        
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
        
        .btn-outline-primary {
            color: #a80006;
            border-color: #a80006;
        }
        .btn-outline-primary:hover {
            background-color: #a80006;
            color: #fff;
        }
        
        .text-primary {
            color: #a80006 !important;
        }
        
        .bg-primary {
            background-color: #a80006 !important;
        }
        
        /* Responsive App Container */
        .app-container {
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
        }
        
        /* Fixed Headers & Footers — Full Width */
        .fixed-responsive {
            width: 100% !important;
            max-width: 100% !important;
            left: 0 !important;
            right: 0 !important;
        }
        
        /* Icons */
        .material-symbols-outlined {
            vertical-align: middle;
        }
        
        /* Utility */
        .border-dashed { border-style: dashed !important; }
        .text-bg-dark { background-color: #291714 !important; color: white !important; }
        
        /* Alert animation */
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-10px); }
            10% { opacity: 1; transform: translateY(0); }
            80% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-10px); }
        }
        .animate-fade-in-out {
            animation: fadeInOut 3s ease-in-out forwards;
        }
        .toast-top {
            position: absolute;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 1050;
        }
        
        /* Hidden scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @vite(['resources/css/pos.css'])
</head>
<body>
    <div class="container-fluid app-container p-0 d-flex flex-column">
        @if(session('success'))
        <div class="toast-top bg-success text-white p-2 text-center animate-fade-in-out fw-bold text-oswald">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="toast-top bg-danger text-white p-2 text-center animate-fade-in-out fw-bold text-oswald">
            {{ session('error') }}
        </div>
        @endif
        
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
