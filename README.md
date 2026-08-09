# mca/firewall

**English** | [Türkçe](README.tr.md)

IP whitelist/blacklist rules, CIDR matching, trash, and request middleware for Laravel 13.  
**Access suite core** — pair later with `mca/access-log` and `mca/access-intel`.

## Features

- Whitelist / blacklist with single IP or CIDR
- Soft delete trash (restore / permanent delete)
- Optional expiry, hit counters
- Middleware: `mca.firewall` (auto on `web` + `api`)
- Root-only admin UI (`mca/permission` soft-dep)
- MCA Hub card (`extra.mca`)

## Install

```bash
composer require mca/firewall
php artisan mca:firewall:install
```

With path repo (starter):

```bash
composer require mca/firewall:@dev
php artisan mca:firewall:install
```

Open `/mca/firewall` as root.

## Config

```env
MCA_FIREWALL_ENABLED=true
MCA_FIREWALL_PROTECTION=true
MCA_FIREWALL_AUTO_REGISTER=true
MCA_FIREWALL_GROUPS=web,api
MCA_FIREWALL_USE_PERMISSION_ROOT=true
```

## Helpers

```php
mca_firewall()->blacklist('1.2.3.4'); // via create()
mca_firewall()->isBlacklisted($ip);
mca_firewall_allows($ip);
```

## Suite dependencies

| Package | Relation |
|---------|----------|
| `mca/permission` | Suggested — root admin UI |
| `mca/hub` | Suggested — dashboard card |
| `mca/access-log` | Suggested (planned) — request logging |
| `mca/access-intel` | Suggested (planned) — health scoring |

## License

MIT
