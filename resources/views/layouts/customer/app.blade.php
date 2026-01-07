<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Metro Munch') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Custome Css  -->
     <link rel="stylesheet" href="{{ asset('css/customer/style.css') }}">

    <!-- Livewire -->
    @livewireStyles
    @stack('styles')
    </head>
    <body class="bg-light">

    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Livewire -->
    @livewireScripts

        <section class="features py-5 bg-white">
        <div class="container feature-container">
           <div class="row text-center">
             <div class="col-md-4 feature-item">
                <div class="feature-icon fs-1 text-danger"><i class="fas fa-motorcycle"></i></div>
                <h4 class="fs-4">Fast Delivery</h4>
                <p class="text-sm">We deliver your food within 30 minutes.</p>
            </div>
            <div class="col-md-4 feature-item">
                <div class="feature-icon fs-1 text-danger"><i class="fas fa-leaf"></i></div>
                <h4 class="fs-4">Fresh Food</h4>
                <p class="text-sm">We use only the freshest ingredients.</p>
            </div>
            <div class="col-md-4 feature-item">
                <div class="feature-icon fs-1 text-danger"><i class="fas fa-headset"></i></div>
                <h4 class="fs-4">24/7 Support</h4>
                <p class="text-sm">Our support team is always ready to help.</p>
            </div>
           </div>
        </div>
    </section>

    <footer class="footer bg-dark text-white py-lg-5">
        <div class="container py-5">
          <div class="row mb-5 align-items-start">
            <div class="col-md-9 mb-4 pe-lg-5">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-img">
              <h5 class="fw-bold mb-3">About Metro Munch</h5>
              <p>Your go-to destination for delicious meals delivered fast to your door. Enjoy a wide range of cuisines and flavors.</p>
            </div>
            <div class="col-md-3 mb-4">
              <h5 class="fw-bold mb-3">Contact Us</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i>123 Food St, Flavor Town, FT 45678</p>
                <p><i class="fas fa-phone me-2"></i>+1 (234) 567-8901</p>
                <p><i class="fas fa-envelope me-2"></i>info@metromunch.com</p>
            </div>
          </div>
        </div>  
        <div class="text-center">
                <small>&copy; {{ date('Y') }} Metro Munch. All rights reserved. Designed and Developed with ❤️ by <a href="https://orangzaib.netlify.app/" target="_blank" class="text-decoration-none text-danger">OrangZaib</a></small> 
            </div>
    </footer>
    <!-- Page-level scripts -->
    @stack('scripts')
</body>
</html>
