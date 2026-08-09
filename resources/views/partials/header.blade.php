@php
    $np = config('firewall.routes.web.name_prefix', 'mca.firewall.');
@endphp
<header class="mca-ui-shell" id="mcaUiShell">
    <div class="mca-ui-shell__wrap">
        <div class="mca-ui-shell__inner">
            <a href="{{ route($np.'index') }}" class="mca-ui-brand">
                <span class="mca-ui-brand__mark" aria-hidden="true">
                    @include('mca-firewall::partials.icon', ['name' => 'shield'])
                </span>
                <span>{{ $mcaFwTitle ?? mca_fw('app.brand') }}</span>
            </a>

            <button type="button"
                    class="mca-ui-menu-btn"
                    id="mcaUiMenuBtn"
                    aria-expanded="false"
                    aria-controls="mcaUiNav"
                    aria-label="{{ mca_fw('app.nav_aria') }}">
                @include('mca-firewall::partials.icon', ['name' => 'menu'])
            </button>
        </div>

        <nav class="mca-ui-nav" id="mcaUiNav" aria-label="{{ mca_fw('app.nav_aria') }}">
            @if(Route::has('mca.hub.index'))
                <a href="{{ route('mca.hub.index') }}" class="mca-ui-nav__link">
                    @include('mca-firewall::partials.icon', ['name' => 'grid', 'class' => 'mca-ui-icon mca-ui-icon--sm'])
                    {{ mca_fw('nav.back_mca') }}
                </a>
            @endif

            <a href="{{ route($np.'index') }}"
               class="mca-ui-nav__link {{ request()->routeIs($np.'index') || request()->routeIs($np.'create') || request()->routeIs($np.'edit') ? 'mca-ui-nav__link--active' : '' }}">
                {{ mca_fw('nav.rules') }}
            </a>

            <a href="{{ route($np.'trash') }}"
               class="mca-ui-nav__link {{ request()->routeIs($np.'trash') ? 'mca-ui-nav__link--active' : '' }}">
                {{ mca_fw('nav.trash') }}
            </a>

            <a href="{{ route($np.'create') }}" class="mca-ui-nav__link">
                {{ mca_fw('nav.create') }}
            </a>
        </nav>
    </div>
</header>
