<?php

namespace Mca\Firewall\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Mca\Firewall\Models\FirewallRule;
use Mca\Firewall\Support\IpMatcher;

class UpdateRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'ip' => [
                'required',
                'string',
                'max:64',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || ! IpMatcher::isValid($value)) {
                        $fail(mca_fw('validation.ip_invalid'));
                    }
                },
            ],
            'type' => ['required', Rule::in([FirewallRule::TYPE_WHITELIST, FirewallRule::TYPE_BLACKLIST])],
            'label' => ['nullable', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['sometimes', 'boolean'],
            'expires_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
