@php
    $__flashData = [];
    foreach (['success', 'error', 'warning', 'info'] as $__key) {
        if (session($__key)) {
            $__flashData[$__key] = session($__key);
        }
    }
    if (session('status') && !in_array(session('status'), ['profile-updated', 'password-updated', 'verification-link-sent'])) {
        $__flashData['status'] = session('status');
    }
@endphp
<script>window.__flash = @json($__flashData);</script>
