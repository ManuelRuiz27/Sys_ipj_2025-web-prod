<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Assets -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body class="bg-dark text-light">
        <div class="container pt-3 pb-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="text-center mb-4">
                        <a href="/" class="text-decoration-none d-inline-block">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:120px;width:auto;" />
                        </a>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed-logo-bar">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="page-logo-img">
        </div>
    </body>
</html>
