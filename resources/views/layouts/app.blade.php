<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
<title>@yield('title', 'SoNiYo Beauty Salon — The Art of Luxury Beauty')</title>
<meta name="description" content="SoNiYo Beauty Salon — an award-winning luxury hair & beauty atelier. Precision cuts, couture color, bridal artistry and signature hair spa rituals." />
<meta name="theme-color" content="#0a0908" />
<link rel="icon" type="image/svg+xml" href="{{ asset('assets/soniyo-emblem.svg') }}" />
<link rel="dns-prefetch" href="https://images.unsplash.com" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />


@stack('styles')
</head>
<body>

@include('partials.loader')
@include('partials.header')
@include('partials.sidebar')

@yield('content')

@include('partials.footer')

<button class="totop" id="toTop" aria-label="Back to top">↑</button>

<script src="{{ asset('js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
