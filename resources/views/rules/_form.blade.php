@php
    use Mca\Firewall\Models\FirewallRule;
    $ip = old('ip', $rule->ip);
    $type = old('type', $rule->type ?: FirewallRule::TYPE_BLACKLIST);
    $label = old('label', $rule->label);
    $reason = old('reason', $rule->reason);
    $isActive = old('is_active', $rule->is_active ?? true);
    $expiresAt = old('expires_at', optional($rule->expires_at)->format('Y-m-d\TH:i'));
@endphp

<div class="mca-fw-fields">
    <label class="mca-sett-field">
        <span class="mca-perm-label">{{ mca_fw('fields.ip') }}</span>
        <input type="text" name="ip" value="{{ $ip }}" required class="mca-perm-input" placeholder="1.2.3.4 veya 1.2.3.0/24">
        <span class="mca-perm-help">{{ mca_fw('fields.ip_help') }}</span>
        @error('ip') <span class="mca-perm-error">{{ $message }}</span> @enderror
    </label>

    <label class="mca-sett-field">
        <span class="mca-perm-label">{{ mca_fw('fields.type') }}</span>
        <select name="type" class="mca-perm-input">
            <option value="{{ FirewallRule::TYPE_BLACKLIST }}" @selected($type === FirewallRule::TYPE_BLACKLIST)>{{ mca_fw('types.blacklist') }}</option>
            <option value="{{ FirewallRule::TYPE_WHITELIST }}" @selected($type === FirewallRule::TYPE_WHITELIST)>{{ mca_fw('types.whitelist') }}</option>
        </select>
        @error('type') <span class="mca-perm-error">{{ $message }}</span> @enderror
    </label>

    <label class="mca-sett-field">
        <span class="mca-perm-label">{{ mca_fw('fields.label') }}</span>
        <input type="text" name="label" value="{{ $label }}" class="mca-perm-input">
        @error('label') <span class="mca-perm-error">{{ $message }}</span> @enderror
    </label>

    <label class="mca-sett-field">
        <span class="mca-perm-label">{{ mca_fw('fields.expires_at') }}</span>
        <input type="datetime-local" name="expires_at" value="{{ $expiresAt }}" class="mca-perm-input">
        @error('expires_at') <span class="mca-perm-error">{{ $message }}</span> @enderror
    </label>

    <label class="mca-sett-field mca-sett-field--wide">
        <span class="mca-perm-label">{{ mca_fw('fields.reason') }}</span>
        <textarea name="reason" rows="3" class="mca-perm-input">{{ $reason }}</textarea>
        @error('reason') <span class="mca-perm-error">{{ $message }}</span> @enderror
    </label>

    <label class="mca-sett-field mca-fw-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked((bool) $isActive)>
        <span>{{ mca_fw('fields.is_active') }}</span>
    </label>
</div>
