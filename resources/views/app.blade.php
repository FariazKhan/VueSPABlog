<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="author" content="name">
        <meta name="description" content="description here">
        <meta name="keywords" content="keywords,here">
        <link rel="icon" type="image/png" href="{{ asset('assets/images/blog/'. $blog_settings->blog_logo) }}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">

        <title inertia>{{ $blog_settings->blog_name ? $blog_settings->blog_name : '' }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet">	
        <style>
            @import url('https://fonts.googleapis.com/css?family=Quicksand');
            ::selection{background-color: aliceblue}
            .font-serif{font-family : 'Quicksand', sans-serif;}
            .smooth {
                transition: box-shadow 0.3s ease-in-out;
            }
        </style>


        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased leading-normal tracking-normal">
        @inertia

        {{-- @env ('local')
            <script src="http://localhost:8080/js/bundle.js"></script>
        @endenv --}}

        <script src="https://unpkg.com/popper.js@1/dist/umd/popper.min.js"></script>
	    <script src="https://unpkg.com/tippy.js@4"></script>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <script>
            //Init tooltips
            tippy('.avatar')
        </script>
    </body>
</html>
