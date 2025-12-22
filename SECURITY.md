# Security Policy

## Desteklenen Versiyonlar

Aşağıdaki versiyonlar güvenlik güncellemeleri almaktadır:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |

## Güvenlik Açığı Bildirimi

Bir güvenlik açığı bulduysanız, lütfen **herkese açık issue açmayın**.

Bunun yerine:

1. **Email**: yakupeyisan@gmail.com adresine email gönderin
2. **Konu**: "SECURITY: [Kısa açıklama]"
3. **İçerik**: Detaylı açıklama, yeniden oluşturma adımları, etki analizi

### Beklenen Yanıt Süresi

- İlk yanıt: 48 saat içinde
- Durum güncellemesi: 7 gün içinde
- Düzeltme: Kritikliğe bağlı olarak 30 gün içinde

## Güvenlik Önlemleri

Bu paket aşağıdaki güvenlik önlemlerini içerir:

### 1. Input Validation

```php
// Veri boş kontrol
if (empty($data)) {
    throw SaverException::forEmptyData();
}

// Dosya adı kontrolü
if (empty($this->fileName)) {
    throw SaverException::forInvalidFileName();
}
```

### 2. Path Traversal Koruması

```php
// Dizin yazılabilirlik kontrolü
protected function ensureDirectoryWritable(string $path): void
{
    if (!is_dir($path)) {
        if (!mkdir($path, 0755, true)) {
            throw SaverException::forDirectoryNotWritable($path);
        }
    }
}
```

### 3. XSS Koruması

HTML output'da otomatik escape:

```php
htmlspecialchars($value, ENT_QUOTES, $charset)
```

### 4. File Permission Kontrolü

```php
if (!is_writable($path)) {
    throw SaverException::forDirectoryNotWritable($path);
}
```

## Best Practices

### Kullanıcı Girdilerini Doğrulayın

```php
// ❌ Kötü
$saver->excel()
    ->setData($_POST['data'])
    ->download();

// ✅ İyi
$data = $this->validate($_POST['data']);
if ($data) {
    $saver->excel()
        ->setData($data)
        ->download();
}
```

### Dosya İzinlerini Kontrol Edin

```php
// Writable dizinlerde çalışın
$savePath = WRITEPATH . 'exports/';

// Public dizinlere kaydetmekten kaçının
// ❌ public/exports/
// ✅ writable/exports/
```

### Dosya Adlarını Sanitize Edin

```php
// ❌ Kötü
$fileName = $_GET['name'] . '.xlsx';

// ✅ İyi
$fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['name']) . '.xlsx';
```

### Bellek ve Süre Limitleri

```php
// Büyük dosyalar için
ini_set('memory_limit', '512M');
set_time_limit(300);

// Veri boyutunu kontrol et
if (count($data) > 100000) {
    // Chunk kullan veya uyarı ver
}
```

### SQL Injection (Model Export)

```php
// ❌ Kötü
$userModel->where('id', $_GET['id'])->findAll();

// ✅ İyi
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id) {
    $userModel->where('id', $id)->findAll();
}
```

## Bilinen Güvenlik Konuları

### 1. Büyük Dosya İşleme

**Risk**: Bellek tükenmesi (DoS)

**Önlem**:
```php
// Veri boyutunu sınırlayın
if (count($data) > 50000) {
    throw new Exception('Çok fazla veri');
}
```

### 2. Dosya Upload

**Not**: Bu paket dosya upload işlemez, sadece oluşturur.

### 3. External Content

**Risk**: SSRF, XXE

**Önlem**: External URL'lerden veri çekmeyin.

```php
// ❌ Sakıncalı
$html = file_get_contents($_GET['url']);

// ✅ Güvenli
$html = $this->getSafeContent();
```

## CVE Raporları

Bu bölüm CVE raporları için ayrılmıştır.

### Geçmiş CVE'ler

Henüz bilinen CVE yok.

## Dependency Security

Bağımlılıklar düzenli olarak kontrol edilir:

```bash
composer audit
```

Bilinen güvenlik açıkları için:
- PhpSpreadsheet: [Advisory](https://github.com/PHPOffice/PhpSpreadsheet/security/advisories)
- PhpWord: [Advisory](https://github.com/PHPOffice/PHPWord/security/advisories)
- mPDF: [Advisory](https://github.com/mpdf/mpdf/security/advisories)
- TCPDF: [Advisory](https://github.com/tecnickcom/tcpdf/security/advisories)

## Security Checklist

Export işlemlerinde kontrol edilmesi gerekenler:

- [ ] Kullanıcı girdileri validate edildi mi?
- [ ] Dosya adları sanitize edildi mi?
- [ ] Dizin izinleri doğru mu?
- [ ] Bellek limitleri ayarlandı mı?
- [ ] Veri boyutu kontrol edildi mi?
- [ ] XSS koruması var mı?
- [ ] Path traversal koruması var mı?
- [ ] Error handling düzgün mü?
- [ ] Sensitive data loglara yazılmıyor mu?
- [ ] Rate limiting var mı? (opsiyonel)

## Hall of Fame

Güvenlik açığı bulan ve bildiren kişiler burada listelenecektir.

---

**Son Güncelleme**: 2024-12-15

Güvenlik konusunda sorularınız için: yakupeyisan@gmail.com

