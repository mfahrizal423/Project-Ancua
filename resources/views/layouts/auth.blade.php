<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'KOPI ANCUA HARMONI')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Oswald:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <style>
        /* Custom Industrial Theme overriding Bootstrap */
        :root {
            --bs-primary: #a80006;
            --bs-primary-rgb: 168, 0, 6;
            --bs-font-sans-serif: 'Inter', sans-serif;
        }
        
        body {
            background-color: #fff;
            font-family: 'Inter', sans-serif;
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
        }
    </style>
    @vite(['resources/css/auth.css'])
</head>
<body class="d-flex flex-column min-vh-100">
    @yield('content')
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
