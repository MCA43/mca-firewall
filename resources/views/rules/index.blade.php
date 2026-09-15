@php
    $np = config('firewall.routes.web.name_prefix', 'mca.firewall.');
@endphp
@extends(\Mca\Firewall\Support\McaFirewallView::layout())

@section('title', mca_fw('pages.index_title'))

@section('content')
    <div class="mca-fw-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_fw('pages.index_title') }}</h1>
            <p class="mca-perm-help">{{ mca_fw('hint.proxy') }}</p>
        </div>
        <a href="{{ route($np.'create') }}" class="mca-ui-btn mca-ui-btn--primary">
            @include('mca-firewall::partials.icon', ['name' => 'plus', 'class' => 'mca-ui-icon mca-ui-icon--sm'])
            {{ mca_fw('nav.create') }}
        </a>
    </div>

    <div class="mca-fw-filters">
        <div class="mca-fw-tabs">
            @foreach (['all', 'whitelist', 'blacklist'] as $tab)
                <a href="{{ route($np.'index', array_filter(['type' => $tab === 'all' ? null : $tab, 'q' => $search ?: null])) }}"
                   class="mca-fw-tabs__link {{ $filterType === $tab ? 'is-active' : '' }}">
                    {{ mca_fw('filters.'.$tab) }}
                    <span class="mca-fw-tabs__count">{{ $counts[$tab] ?? 0 }}</span>
                </a>
            @endforeach
            <a href="{{ route($np.'trash') }}" class="mca-fw-tabs__link">
                {{ mca_fw('nav.trash') }}
                <span class="mca-fw-tabs__count">{{ $counts['trash'] ?? 0 }}</span>
            </a>
        </div>

        <form method="get" action="{{ route($np.'index') }}" class="mca-fw-search">
            @if ($filterType !== 'all')
                <input type="hidden" name="type" value="{{ $filterType }}">
            @endif
            <input type="search" name="q" value="{{ $search }}" placeholder="{{ mca_fw('filters.search') }}" class="mca-perm-input">
        </form>
    </div>

    <div class="mca-perm-card">
        <div class="mca-perm-card__body mca-fw-table-wrap">
            @if ($rules->isEmpty())
                <p class="mca-perm-help">{{ mca_fw('table.empty') }}</p>
            @else
                <table class="mca-fw-table">
                    <thead>
                        <tr>
                            <th>{{ mca_fw('fields.ip') }}</th>
                            <th>{{ mca_fw('fields.type') }}</th>
                            <th>{{ mca_fw('fields.label') }}</th>
                            <th>{{ mca_fw('fields.hits') }}</th>
                            <th>{{ mca_fw('fields.is_active') }}</th>
                            <th class="mca-fw-col-actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rules as $rule)
                            <tr>
                                <td>
                                    <code class="mca-fw-ip">{{ $rule->ip }}</code>
                                    @if ($rule->expires_at)
                                        <div class="mca-perm-help">{{ mca_fw('fields.expires_at') }}: {{ $rule->expires_at->format('Y-m-d H:i') }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="mca-fw-badge mca-fw-badge--{{ $rule->type }}">{{ mca_fw('types.'.$rule->type) }}</span>
                                </td>
                                <td>
                                    <div>{{ $rule->label ?: '—' }}</div>
                                    @if ($rule->reason)
                                        <div class="mca-perm-help">{{ \Illuminate\Support\Str::limit($rule->reason, 80) }}</div>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($rule->hits) }}
                                    @if ($rule->last_hit_at)
                                        <div class="mca-perm-help">{{ $rule->last_hit_at->diffForHumans() }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($rule->isExpired())
                                        <span class="mca-fw-badge mca-fw-badge--expired">{{ mca_fw('table.status_expired') }}</span>
                                    @elseif ($rule->is_active)
                                        <span class="mca-fw-badge mca-fw-badge--active">{{ mca_fw('table.status_active') }}</span>
                                    @else
                                        <span class="mca-fw-badge mca-fw-badge--inactive">{{ mca_fw('table.status_inactive') }}</span>
                                    @endif
                                </td>
                                <td class="mca-fw-col-actions">
                                    <div class="mca-fw-actions mca-ui-list-card__actions mca-ui-list-card__actions--tight">
                                        <form method="post" action="{{ route($np.'toggle', $rule) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="mca-ui-btn mca-ui-btn--secondary mca-ui-btn--icon mca-fw-tip"
                                                    data-tooltip="{{ mca_fw('actions.toggle') }}"
                                                    title="{{ mca_fw('actions.toggle') }}"
                                                    aria-label="{{ mca_fw('actions.toggle') }}">
                                                @include('mca-firewall::partials.icon', ['name' => 'power', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                            </button>
                                        </form>
                                        <a href="{{ route($np.'edit', $rule) }}"
                                           class="mca-ui-btn mca-ui-btn--secondary mca-ui-btn--icon mca-fw-tip"
                                           data-tooltip="{{ mca_fw('actions.edit') }}"
                                           title="{{ mca_fw('actions.edit') }}"
                                           aria-label="{{ mca_fw('actions.edit') }}">
                                            @include('mca-firewall::partials.icon', ['name' => 'pencil', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                        </a>
                                        <form method="post" action="{{ route($np.'destroy', $rule) }}"
                                              data-mca-confirm="{{ mca_fw('confirm.trash') }}"
                                              data-mca-confirm-title="{{ mca_fw('modal.confirm_title') }}"
                                              data-mca-confirm-text="{{ mca_fw('actions.delete') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="mca-ui-btn mca-ui-btn--danger mca-ui-btn--icon mca-fw-tip"
                                                    data-tooltip="{{ mca_fw('actions.delete') }}"
                                                    title="{{ mca_fw('actions.delete') }}"
                                                    aria-label="{{ mca_fw('actions.delete') }}">
                                                @include('mca-firewall::partials.icon', ['name' => 'trash', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mca-fw-pagination">{{ $rules->links('mca-firewall::partials.pagination') }}</div>
            @endif
        </div>
    </div>

    <p class="mca-perm-help mca-fw-suite-hint">{{ mca_fw('hint.suite') }}</p>
@endsection
