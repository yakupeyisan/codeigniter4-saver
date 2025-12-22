# .env Yapılandırma Rehberi

Bu dokümanda CodeIgniter 4 Saver paketi için .env ayarları açıklanmaktadır.

## 📋 .env Ayarları

`.env` dosyanıza aşağıdaki ayarları ekleyin:

```env
# ============================================
# CodeIgniter 4 Saver Ayarları
# ============================================

# Varsayılan Kayıt Yolu
# Dosyaların kaydedileceği varsayılan dizin
# WRITEPATH'e göre yol veya mutlak yol kullanabilirsiniz
# Örnekler:
#   - attachments (WRITEPATH/attachments)
#   - exports/reports (WRITEPATH/exports/reports)
#   - /var/www/uploads (mutlak yol - Linux/Mac)
#   - C:\uploads (mutlak yol - Windows)
SAVER_DEFAULT_SAVE_PATH=attachments

# Geçici Dosya Yolu
# Geçici dosyaların saklanacağı dizin
# Varsayılan: WRITEPATH/uploads/temp/
SAVER_TEMP_PATH=uploads/temp

# Otomatik Kaydetme Modu
# true: download() yerine otomatik olarak varsayılan yola kaydeder
# false: Normal download() davranışı (tarayıcıya indirir)
# Varsayılan: false
SAVER_AUTO_SAVE=false

# Varsayılan Sürücü
# excel, word, pdf, html, csv
# Varsayılan: excel
SAVER_DEFAULT_DRIVER=excel
```

## 🔧 Ayar Açıklamaları

### SAVER_DEFAULT_SAVE_PATH

Dosyaların kaydedileceği varsayılan dizin.

**Örnekler:**

```env
# WRITEPATH'e göre yol (önerilen)
SAVER_DEFAULT_SAVE_PATH=attachments
# Sonuç: writable/attachments/

# Alt dizinler
SAVER_DEFAULT_SAVE_PATH=exports/reports
# Sonuç: writable/exports/reports/

# Mutlak yol (Linux/Mac)
SAVER_DEFAULT_SAVE_PATH=/var/www/uploads
# Sonuç: /var/www/uploads/

# Mutlak yol (Windows)
SAVER_DEFAULT_SAVE_PATH=C:\uploads
# Sonuç: C:\uploads\
```

### SAVER_TEMP_PATH

Geçici dosyaların saklanacağı dizin.

```env
SAVER_TEMP_PATH=uploads/temp
# Sonuç: writable/uploads/temp/
```

### SAVER_AUTO_SAVE

Otomatik kaydetme modu. `true` ise `download()` metodu dosyayı indirmek yerine varsayılan yola kaydeder.

```env
# Otomatik kaydet
SAVER_AUTO_SAVE=true

# Normal indirme
SAVER_AUTO_SAVE=false
```

### SAVER_DEFAULT_DRIVER

Varsayılan sürücü. `getDefaultDriver()` metodu çağrıldığında kullanılır.

```env
SAVER_DEFAULT_DRIVER=excel
# veya: word, pdf, html, csv
```

## 📝 Kullanım Örnekleri

### Varsayılan Yola Kaydetme

```php
use Yakupeyisan\CodeIgniter4Saver\Saver;

$saver = new Saver();

// .env'deki SAVER_DEFAULT_SAVE_PATH yoluna kaydet
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->saveToDefault();

echo "Dosya kaydedildi: " . $filePath;
```

### Otomatik Kaydetme Modu

```env
SAVER_AUTO_SAVE=true
```

```php
// download() otomatik olarak kaydeder
$saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->download(); // writable/attachments/rapor.xlsx'ye kaydeder
```

### Özel Yola Kaydetme

```php
// .env ayarlarını görmezden gel, özel yol kullan
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('rapor.xlsx')
    ->save(WRITEPATH . 'custom/path/');
```

## 🔄 Değişiklikleri Uygulama

.env dosyasını değiştirdikten sonra:

```bash
# Cache'i temizle
php spark cache:clear

# Veya uygulamayı yeniden başlat
```

## ✅ Kontrol

Config değerlerini kontrol etmek için:

```php
$config = config('Saver');

echo "Save Path: " . $config->savePath;
echo "Auto Save: " . ($config->autoSave ? 'true' : 'false');
echo "Default Driver: " . $config->defaultDriver;
```

---

**Not**: Tüm ayarlar opsiyoneldir. Belirtilmezse varsayılan değerler kullanılır.

**Son Güncelleme**: 2024-12-15

