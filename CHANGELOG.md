# Changelog

## [0.1.0] - 2026-08-09

### Added
- IP whitelist / blacklist rules with CIDR support
- Soft delete trash (restore + force delete)
- Temporary rules via `expires_at`
- Hit counter + last match timestamp
- `mca.firewall` middleware (auto-register on `web` / `api`)
- Root-only admin UI (`/mca/firewall`)
- Hub integration (`extra.mca`, suite: access / core)
- Events: `IpBlocked`, `RuleChanged`
- Helpers: `mca_firewall()`, `mca_fw()`, `mca_firewall_allows()`
- Install command: `php artisan mca:firewall:install`

### Notes
- Access suite satellites (planned): `mca/access-log`, `mca/access-intel`
- Soft-deps: `mca/permission` (root UI), `mca/hub` (dashboard card)
- Configure Laravel `TrustProxies` behind CDN/proxy
