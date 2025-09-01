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
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-primary text-white py-3 mb-4">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="container pt-0 pb-5">
            {{ $slot }}
        </main>
        <x-flash-toasts />
        <div class="fixed-logo-bar">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="page-logo-img">
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.toast[data-auto-show="true"]').forEach(el => {
                    const t = new bootstrap.Toast(el, { delay: 3000 });
                    t.show();
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
