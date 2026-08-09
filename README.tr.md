# mca/firewall

**Türkçe** | [English](README.md)

Laravel 13 için IP whitelist/blacklist, CIDR, çöp kutusu ve istek middleware’i.  
**Access suite çekirdeği** — sonra `mca/access-log` ve `mca/access-intel` ile genişler.

## Özellikler

- Tek IP veya CIDR white/black list
- Soft delete çöp kutusu (geri al / kalıcı sil)
- Opsiyonel süre, eşleşme sayacı
- Middleware: `mca.firewall` (`web` + `api` otomatik)
- Root-only admin UI (`mca/permission` soft-dep)
- MCA Hub kartı (`extra.mca`)

## Kurulum

```bash
composer require mca/firewall
php artisan mca:firewall:install
```

Starter path repo:

```bash
composer require mca/firewall:@dev
php artisan mca:firewall:install
```

Root ile `/mca/firewall` açın.

## Yapılandırma

```env
MCA_FIREWALL_ENABLED=true
MCA_FIREWALL_PROTECTION=true
MCA_FIREWALL_AUTO_REGISTER=true
MCA_FIREWALL_GROUPS=web,api
MCA_FIREWALL_USE_PERMISSION_ROOT=true
```

Proxy/CDN arkasında Laravel `TrustProxies` ayarını kontrol edin.

## Aile bağımlılıkları

| Paket | İlişki |
|---------|----------|
| `mca/permission` | Önerilen — root admin UI |
| `mca/hub` | Önerilen — hub kartı |
| `mca/access-log` | Önerilen (planlı) — istek günlüğü |
| `mca/access-intel` | Önerilen (planlı) — sağlık skoru |

## Lisans

MIT
