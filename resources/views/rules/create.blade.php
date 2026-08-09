@php
    $np = config('firewall.routes.web.name_prefix', 'mca.firewall.');
@endphp
@extends(\Mca\Firewall\Support\McaFirewallView::layout())

@section('title', mca_fw('pages.create_title'))

@section('content')
    <div class="mca-fw-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_fw('pages.create_title') }}</h1>
            <p class="mca-perm-help">{{ mca_fw('fields.ip_help') }}</p>
        </div>
        <a href="{{ route($np.'index') }}" class="mca-ui-btn mca-ui-btn--ghost">{{ mca_fw('actions.back') }}</a>
    </div>

    <div class="mca-perm-card mca-fw-form-card">
        <div class="mca-perm-card__body">
            <form method="post" action="{{ route($np.'store') }}" class="mca-fw-form">
                @csrf
                @include('mca-firewall::rules._form')
                <div class="mca-fw-form__actions">
                    <button type="submit" class="mca-ui-btn mca-ui-btn--primary">{{ mca_fw('actions.create') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
