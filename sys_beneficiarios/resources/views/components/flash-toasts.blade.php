@php
    $toasts = [];
    $success = session('status') ?? session('success');
    if ($success) $toasts[] = ['msg' => $success, 'class' => 'text-bg-success'];
    if (session('error')) $toasts[] = ['msg' => session('error'), 'class' => 'text-bg-danger'];
    if (session('warning')) $toasts[] = ['msg' => session('warning'), 'class' => 'text-bg-warning'];
    if (session('info')) $toasts[] = ['msg' => session('info'), 'class' => 'text-bg-info'];
@endphp

@if(count($toasts))
<div aria-live="polite" aria-atomic="true" class="position-fixed top-0 end-0 p-3" style="z-index: 1080">
    @foreach($toasts as $i => $t)
        <div class="toast align-items-center {{ $t['class'] }} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-auto-show="true">
            <div class="d-flex">
                <div class="toast-body">{{ $t['msg'] }}</div>
                <button type="button" class="btn-close @if(Str::contains($t['class'],'text-bg')) btn-close-white @endif me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endforeach
</div>
@endif
