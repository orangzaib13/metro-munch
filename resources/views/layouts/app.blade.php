<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Restaurant Management') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Livewire -->
    @livewireStyles
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">


</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        @if(auth()->user()->user_role === 'Admin')
        @include('layouts.sidebars.admin')
          @elseif(auth()->user()->user_role === 'Manager')
        @include('layouts.sidebars.branch')
         @endif

        
        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <button type="button" class="btn text-danger" id="sidebar-toggle"><i class="fas fa-bars"></i></button>
                </div>
                <div class="header-right">
                    <div class="date-time">
                        <i class="fas fa-calendar"></i>
                        <span id="current-date"></span>
                    </div>
                    <a href="{{ route('logout') }}" class="logout-btn btn btn-primary btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Please fix the errors below:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    <x-notification-toast />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Livewire -->
    @livewireScripts
    
    <script>
        document.getElementById("sidebar-toggle").addEventListener("click", function () {
            const sidebar = document.querySelector(".sidebar");
            const content = document.querySelector(".main-content");

            sidebar.classList.toggle("collapsed");
            content.classList.toggle("shifted");

        });
        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', options);
        }
        
        updateDateTime();
        setInterval(updateDateTime, 60000);
    </script>
</body>
</html>
