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
            // UI utils shared
            window.showToast = function(message, type = 'info', delay = 2500) {
                const wrap = document.createElement('div');
                const theme = (type === 'success') ? 'success' : (type === 'danger' ? 'danger' : 'primary');
                wrap.className = 'toast align-items-center text-bg-'+theme+' border-0 position-fixed top-0 end-0 m-3';
                wrap.setAttribute('role','alert'); wrap.setAttribute('aria-live','assertive'); wrap.setAttribute('aria-atomic','true');
                wrap.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
                document.body.appendChild(wrap);
                const t = new bootstrap.Toast(wrap, { delay }); t.show();
                wrap.addEventListener('hidden.bs.toast', ()=>wrap.remove());
            }
            window.showSpinnerIn = function(element) {
                if (!element) return;
                element.innerHTML = `<div class="d-flex align-items-center"><div class="spinner-border spinner-border-sm me-2" role="status"></div> Cargando…</div>`;
            }
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
