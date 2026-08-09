<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $mcaFwTitle ?? mca_fw('app.title'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ \Mca\Firewall\Support\McaFirewallView::uiCssUrl() }}">
    <link rel="stylesheet" href="{{ \Mca\Firewall\Support\McaFirewallView::cssUrl() }}">
    @stack('mca-fw-head')
</head>
<body class="mca-ui-root mca-perm-root mca-fw-root">
    @include('mca-firewall::partials.header')

    <main class="mca-ui-main mca-perm-main mca-fw-main">
        @include('mca-firewall::partials.flash')
        @yield('content')
    </main>

    @php
        $mcaUiI18n = [
            'ok' => mca_fw('modal.ok'),
            'confirm' => mca_fw('modal.confirm'),
            'cancel' => mca_fw('modal.cancel'),
            'close' => mca_fw('modal.close'),
            'alert_title' => mca_fw('modal.alert_title'),
            'confirm_title' => mca_fw('modal.confirm_title'),
        ];
    @endphp
    <script>
        window.McaUiI18n = @json($mcaUiI18n);
    </script>
    <script src="{{ \Mca\Firewall\Support\McaFirewallView::uiJsUrl() }}" defer></script>
    <script src="{{ \Mca\Firewall\Support\McaFirewallView::jsUrl() }}" defer></script>
    @stack('mca-fw-scripts')
</body>
</html>
