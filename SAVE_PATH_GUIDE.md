# Dosya Kaydetme Yolu Rehberi

Bu dokümanda dosyaların nasıl kaydedileceği ve .env ayarları açıklanmaktadır.

## 📋 İçindekiler

- [Varsayılan Kayıt Yolu](#varsayılan-kayıt-yolu)
- [.env Yapılandırması](#env-yapılandırması)
- [Kullanım Örnekleri](#kullanım-örnekleri)
- [Path Formatları](#path-formatları)

---

## Varsayılan Kayıt Yolu

Paket varsayılan olarak dosyaları `writable/attachments/` dizinine kaydeder.

### Otomatik Dizin Oluşturma

Dizin yoksa otomatik olarak oluşturulur:

```php
$saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->saveToDefault(); // writable/attachments/rapor.xlsx
```

---

## .env Yapılandırması

### SAVER_DEFAULT_SAVE_PATH

Varsayılan kayıt yolunu belirler:

```env
# WRITEPATH'e göre yol (önerilen)
SAVER_DEFAULT_SAVE_PATH=attachments

# Alt dizinler
SAVER_DEFAULT_SAVE_PATH=exports/reports

# Mutlak yol (Linux/Mac)
SAVER_DEFAULT_SAVE_PATH=/var/www/uploads

# Mutlak yol (Windows)
SAVER_DEFAULT_SAVE_PATH=C:\uploads
```

### SAVER_TEMP_PATH

Geçici dosyaların yolu:

```env
SAVER_TEMP_PATH=uploads/temp
```

### SAVER_AUTO_SAVE

Otomatik kaydetme modu:

```env
# true: download() yerine otomatik kaydet
SAVER_AUTO_SAVE=true

# false: Normal download() davranışı
SAVER_AUTO_SAVE=false
```

### SAVER_DEFAULT_DRIVER

Varsayılan sürücü:

```env
SAVER_DEFAULT_DRIVER=excel
```

---

## Kullanım Örnekleri

### 1. Varsayılan Yola Kaydetme

```php
use Yakupeyisan\CodeIgniter4Saver\Saver;

$saver = new Saver();

// Varsayılan yola kaydet (writable/attachments/)
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->saveToDefault();

echo "Dosya kaydedildi: " . $filePath;
```

### 2. Özel Yola Kaydetme

```php
// Belirli bir yola kaydet
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->save(WRITEPATH . 'exports/reports/');

// veya mutlak yol
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->save('/var/www/uploads/');
```

### 3. Helper Metod ile Kaydetme

```php
// Varsayılan yola kaydet
$filePath = $saver->saveToDefault('excel', $data, 'rapor.xlsx');

// Özel yola kaydet
$filePath = $saver->saveToPath('excel', $data, 'rapor.xlsx', WRITEPATH . 'exports/');
```

### 4. Download Yerine Save

```php
// .env'de SAVER_AUTO_SAVE=true ise
$saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->download(); // Otomatik olarak kaydeder, indirmez

// Manuel kontrol
if ($saver->config->autoSave) {
    $filePath = $saver->excel()
        ->setData($data)
        ->setFileName('rapor.xlsx')
        ->saveToDefault();
} else {
    $saver->excel()
        ->setData($data)
        ->setFileName('rapor.xlsx')
        ->download();
}
```

### 5. Tarihli Dosya Adları

```php
$fileName = 'rapor_' . date('Y-m-d_His') . '.xlsx';

$filePath = $saver->excel()
    ->setData($data)
    ->setFileName($fileName)
    ->saveToDefault();
```

### 6. Alt Dizinlere Kaydetme

```php
// Yıl/ay klasörüne kaydet
$year = date('Y');
$month = date('m');
$path = WRITEPATH . "attachments/{$year}/{$month}/";

$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->save($path);
```

---

## Path Formatları

### WRITEPATH'e Göre Yol (Önerilen)

```php
// .env'de
SAVER_DEFAULT_SAVE_PATH=attachments

// Kodda
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->saveToDefault();
// Sonuç: writable/attachments/rapor.xlsx
```

### Mutlak Yol (Linux/Mac)

```env
SAVER_DEFAULT_SAVE_PATH=/var/www/uploads
```

### Mutlak Yol (Windows)

```env
SAVER_DEFAULT_SAVE_PATH=C:\uploads
```

### Alt Dizinler

```env
SAVER_DEFAULT_SAVE_PATH=exports/reports/monthly
```

---

## Controller Örneği

```php
<?php

namespace App\Controllers;

use Yakupeyisan\CodeIgniter4Saver\Saver;

class ExportController extends BaseController
{
    public function exportAndSave()
    {
        $data = [
            ['Ad', 'Soyad', 'Email'],
            ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
        ];

        $saver = new Saver();

        // Varsayılan yola kaydet
        $filePath = $saver->excel()
            ->setData($data)
            ->setFileName('kullanicilar_' . date('Y-m-d') . '.xlsx')
            ->saveToDefault();

        return $this->response->setJSON([
            'success' => true,
            'file_path' => $filePath,
            'message' => 'Dosya başarıyla kaydedildi'
        ]);
    }

    public function exportToCustomPath()
    {
        $data = [/* ... */];

        $saver = new Saver();

        // Özel yola kaydet
        $customPath = WRITEPATH . 'exports/' . date('Y/m/');
        $filePath = $saver->excel()
            ->setData($data)
            ->setFileName('rapor.xlsx')
            ->save($customPath);

        return $this->response->setJSON([
            'success' => true,
            'file_path' => $filePath
        ]);
    }

    public function downloadOrSave()
    {
        $data = [/* ... */];
        $action = $this->request->getGet('action'); // 'download' veya 'save'

        $saver = new Saver();
        $driver = $saver->excel()
            ->setData($data)
            ->setFileName('rapor.xlsx');

        if ($action === 'save') {
            $filePath = $driver->saveToDefault();
            return $this->response->setJSON([
                'success' => true,
                'file_path' => $filePath
            ]);
        } else {
            $driver->download();
        }
    }
}
```

---

## Güvenlik Notları

### Dizin İzinleri

```bash
# writable/attachments dizinine yazma izni verin
chmod -R 775 writable/attachments
chown -R www-data:www-data writable/attachments
```

### Path Traversal Koruması

Paket otomatik olarak path traversal saldırılarına karşı korumalıdır:

```php
// ❌ Tehlikeli (paket bunu engeller)
$saver->excel()->save('../../../etc/passwd');

// ✅ Güvenli
$saver->excel()->save(WRITEPATH . 'attachments/');
```

### Dosya Adı Sanitizasyonu

```php
// Güvenli dosya adı oluşturma
$fileName = preg_replace('/[^a-zA-Z0-9_-]/', '', $userInput) . '.xlsx';

$saver->excel()
    ->setData($data)
    ->setFileName($fileName)
    ->saveToDefault();
```

---

## Sorun Giderme

### "Directory not writable" Hatası

```bash
# Dizin izinlerini kontrol edin
ls -la writable/attachments/

# İzinleri düzeltin
chmod -R 775 writable/attachments
```

### .env Değişiklikleri Uygulanmıyor

```bash
# Cache'i temizleyin
php spark cache:clear

# Config'i yeniden yükleyin
# veya uygulamayı yeniden başlatın
```

### Path Bulunamıyor

```php
// Path'i kontrol edin
$config = config('Saver');
echo "Save Path: " . $config->savePath;

// Dizin oluşturuluyor mu kontrol edin
if (!is_dir($config->savePath)) {
    mkdir($config->savePath, 0755, true);
}
```

---

## Best Practices

1. **WRITEPATH Kullanın**: Mutlak yol yerine WRITEPATH'e göre yol kullanın
2. **Alt Dizinler**: Büyük projelerde alt dizinler kullanın (yıl/ay)
3. **Dosya Adları**: Tarih ve zaman ekleyerek çakışmaları önleyin
4. **İzinler**: Dizin izinlerini düzenli kontrol edin
5. **Temizlik**: Eski dosyaları düzenli olarak temizleyin

---

**Son Güncelleme**: 2024-12-15

