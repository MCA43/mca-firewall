@php
    $np = config('firewall.routes.web.name_prefix', 'mca.firewall.');
@endphp
@extends(\Mca\Firewall\Support\McaFirewallView::layout())

@section('title', mca_fw('pages.trash_title'))

@section('content')
    <div class="mca-fw-toolbar">
        <div>
            <h1 class="mca-perm-title">{{ mca_fw('pages.trash_title') }}</h1>
            <p class="mca-perm-help">{{ $counts['trash'] ?? 0 }}</p>
        </div>
        <a href="{{ route($np.'index') }}" class="mca-ui-btn mca-ui-btn--ghost">{{ mca_fw('actions.back') }}</a>
    </div>

    <div class="mca-perm-card">
        <div class="mca-perm-card__body mca-fw-table-wrap">
            @if ($rules->isEmpty())
                <p class="mca-perm-help">{{ mca_fw('table.trash_empty') }}</p>
            @else
                <table class="mca-fw-table">
                    <thead>
                        <tr>
                            <th>{{ mca_fw('fields.ip') }}</th>
                            <th>{{ mca_fw('fields.type') }}</th>
                            <th>{{ mca_fw('fields.label') }}</th>
                            <th class="mca-fw-col-actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rules as $rule)
                            <tr>
                                <td><code class="mca-fw-ip">{{ $rule->ip }}</code></td>
                                <td><span class="mca-fw-badge mca-fw-badge--{{ $rule->type }}">{{ mca_fw('types.'.$rule->type) }}</span></td>
                                <td>{{ $rule->label ?: '—' }}</td>
                                <td class="mca-fw-col-actions">
                                    <div class="mca-fw-actions mca-ui-list-card__actions mca-ui-list-card__actions--tight">
                                        <form method="post" action="{{ route($np.'restore', $rule) }}"
                                              data-mca-confirm="{{ mca_fw('confirm.restore') }}"
                                              data-mca-confirm-title="{{ mca_fw('modal.confirm_title') }}"
                                              data-mca-confirm-text="{{ mca_fw('actions.restore') }}"
                                              data-mca-confirm-danger="0">
                                            @csrf
                                            <button type="submit"
                                                    class="mca-ui-btn mca-ui-btn--secondary mca-ui-btn--icon mca-fw-tip"
                                                    data-tooltip="{{ mca_fw('actions.restore') }}"
                                                    title="{{ mca_fw('actions.restore') }}"
                                                    aria-label="{{ mca_fw('actions.restore') }}">
                                                @include('mca-firewall::partials.icon', ['name' => 'restore', 'class' => 'mca-ui-icon mca-ui-icon--xs'])
                                            </button>
                                        </form>
                                        <form method="post" action="{{ route($np.'force-destroy', $rule) }}"
                                              data-mca-confirm="{{ mca_fw('confirm.force_delete') }}"
                                              data-mca-confirm-title="{{ mca_fw('modal.confirm_title') }}"
                                              data-mca-confirm-text="{{ mca_fw('actions.force_delete') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="mca-ui-btn mca-ui-btn--danger mca-ui-btn--icon mca-fw-tip"
                                                    data-tooltip="{{ mca_fw('actions.force_delete') }}"
                                                    title="{{ mca_fw('actions.force_delete') }}"
                                                    aria-label="{{ mca_fw('actions.force_delete') }}">
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
@endsection
