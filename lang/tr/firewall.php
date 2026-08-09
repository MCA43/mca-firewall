<?php

return [
    'app' => [
        'title' => 'Güvenlik Duvarı',
        'brand' => 'Güvenlik Duvarı',
        'nav_aria' => 'Güvenlik duvarı menüsü',
    ],
    'nav' => [
        'back_mca' => 'MCA Hub',
        'rules' => 'Kurallar',
        'trash' => 'Çöp kutusu',
        'create' => 'Kural ekle',
    ],
    'filters' => [
        'all' => 'Tümü',
        'whitelist' => 'Whitelist',
        'blacklist' => 'Blacklist',
        'search' => 'IP, etiket ara…',
    ],
    'fields' => [
        'ip' => 'IP / CIDR',
        'ip_help' => 'Örnek: 1.2.3.4 veya 1.2.3.0/24',
        'type' => 'Liste',
        'label' => 'Etiket',
        'reason' => 'Sebep',
        'is_active' => 'Aktif',
        'expires_at' => 'Bitiş',
        'hits' => 'Eşleşme',
        'last_hit' => 'Son eşleşme',
        'created' => 'Oluşturulma',
    ],
    'types' => [
        'whitelist' => 'Whitelist',
        'blacklist' => 'Blacklist',
    ],
    'actions' => [
        'save' => 'Kaydet',
        'create' => 'Kural oluştur',
        'edit' => 'Düzenle',
        'delete' => 'Çöpe taşı',
        'restore' => 'Geri al',
        'force_delete' => 'Kalıcı sil',
        'toggle' => 'Durum',
        'cancel' => 'İptal',
        'back' => 'Geri',
    ],
    'table' => [
        'empty' => 'Henüz kural yok.',
        'trash_empty' => 'Çöp kutusu boş.',
        'status_active' => 'Aktif',
        'status_inactive' => 'Pasif',
        'status_expired' => 'Süresi dolmuş',
    ],
    'flash' => [
        'created' => 'Kural oluşturuldu.',
        'updated' => 'Kural güncellendi.',
        'trashed' => 'Kural çöp kutusuna taşındı.',
        'restored' => 'Kural geri alındı.',
        'force_deleted' => 'Kural kalıcı olarak silindi.',
        'toggled' => 'Kural durumu güncellendi.',
    ],
    'confirm' => [
        'trash' => 'Bu kural çöp kutusuna taşınsın mı?',
        'force_delete' => 'Bu kural kalıcı olarak silinsin mi? Bu işlem geri alınamaz.',
        'restore' => 'Bu kural geri alınsın mı?',
    ],
    'errors' => [
        'root_only' => 'Güvenlik duvarını yalnızca root kullanıcılar yönetebilir.',
        'blocked' => 'IP adresiniz engellenmiş.',
    ],
    'validation' => [
        'ip_invalid' => 'Geçerli bir IPv4/IPv6 adresi veya CIDR aralığı girin.',
    ],
    'modal' => [
        'ok' => 'Tamam',
        'confirm' => 'Onayla',
        'cancel' => 'İptal',
        'close' => 'Kapat',
        'alert_title' => 'Bilgi',
        'confirm_title' => 'Onay',
    ],
    'console' => [
        'install' => [
            'start' => 'MCA Firewall kuruluyor…',
            'config_ready' => 'Config hazır',
            'assets_published' => 'Asset’ler yayınlandı',
            'migration_done' => 'Migration tamam',
            'done' => 'MCA Firewall kuruldu.',
            'web_ui' => 'Web arayüzü: /:prefix',
        ],
    ],
    'pages' => [
        'index_title' => 'IP kuralları',
        'create_title' => 'IP kuralı ekle',
        'edit_title' => 'IP kuralını düzenle',
        'trash_title' => 'Çöp kutusu',
    ],
    'hint' => [
        'suite' => 'Access ailesi: istek geçmişi ve sağlık skoru için mca/access-log ile mca/access-intel ekleyin.',
        'proxy' => 'Proxy/CDN arkasındaysanız Laravel TrustProxies ayarını doğru yapılandırın.',
    ],
];
