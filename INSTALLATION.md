# Kurulum Rehberi

CodeIgniter 4 Saver paketini kurma ve yapılandırma rehberi.

## 📋 Gereksinimler

- PHP 8.1 veya üzeri
- CodeIgniter 4.x
- Composer

## 📦 Kurulum Adımları

### 1. Composer ile Kurulum

Proje dizininizde terminali açın ve aşağıdaki komutu çalıştırın:

```bash
composer require yakupeyisan/codeigniter4-saver
```

### 2. Konfigürasyon Dosyasını Yayınlama

Config dosyasını projenize kopyalamak için:

```bash
php spark saver:publish
```

Bu komut `app/Config/Saver.php` dosyasını oluşturacaktır.

### 3. .env Yapılandırması

`.env` dosyanıza aşağıdaki ayarları ekleyin:

```env
# Varsayılan kayıt yolu (writable/attachments/)
SAVER_DEFAULT_SAVE_PATH=attachments

# Geçici dosya yolu
SAVER_TEMP_PATH=uploads/temp

# Otomatik kaydetme modu (true/false)
SAVER_AUTO_SAVE=false

# Varsayılan sürücü
SAVER_DEFAULT_DRIVER=excel
```

Detaylı yapılandırma için [ENV_CONFIGURATION.md](ENV_CONFIGURATION.md) dosyasına bakın.

### 4. Konfigürasyon Dosyasını Yayınlama (Opsiyonel)

Config dosyasını özelleştirmek isterseniz:

```bash
php spark saver:publish
```

Bu komut `app/Config/Saver.php` dosyasını oluşturacaktır.

### 5. Dizinleri Oluşturma

Gerekli dizinleri oluşturun ve yazma izni verin:

```bash
# Varsayılan kayıt dizini
mkdir -p writable/attachments
chmod -R 775 writable/attachments

# Geçici dosya dizini
mkdir -p writable/uploads/temp
chmod -R 775 writable/uploads/temp
```

**Not**: Dizinler yoksa otomatik olarak oluşturulur, ancak izinleri manuel ayarlamanız gerekebilir.

## 🎯 Hızlı Test

Kurulumun başarılı olup olmadığını test edin:

### Controller Oluşturun

`app/Controllers/TestSaver.php`:

```php
<?php

namespace App\Controllers;

use Yakupeyisan\CodeIgniter4Saver\Saver;

class TestSaver extends BaseController
{
    public function index()
    {
        $data = [
            ['Ad', 'Soyad', 'Email'],
            ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
            ['Mehmet', 'Kaya', 'mehmet@example.com'],
        ];

        $saver = new Saver();
        
        return $this->response->setJSON([
            'message' => 'Saver paketi başarıyla yüklendi!',
            'test' => 'Excel dosyası indirmek için /test-saver/excel adresine gidin'
        ]);
    }

    public function excel()
    {
        $data = [
            ['Ad', 'Soyad', 'Email'],
            ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
            ['Mehmet', 'Kaya', 'mehmet@example.com'],
        ];

        $saver = new Saver();
        $saver->excel()
            ->setData($data)
            ->setFileName('test.xlsx')
            ->download();
    }
}
```

### Route Ekleyin

`app/Config/Routes.php`:

```php
$routes->get('test-saver', 'TestSaver::index');
$routes->get('test-saver/excel', 'TestSaver::excel');
```

### Test Edin

Tarayıcınızda açın:
- `http://localhost:8080/test-saver` - API testi
- `http://localhost:8080/test-saver/excel` - Excel indirme testi

## 🔧 Opsiyonel Ayarlar

### Helper'ı Otomatik Yükle

`app/Config/Autoload.php`:

```php
public $helpers = ['saver'];
```

Bu sayede helper fonksiyonlarını direkt kullanabilirsiniz:

```php
// Artık bu şekilde kullanabilirsiniz
export_excel($data, 'rapor.xlsx');
export_csv($data, 'data.csv');
```

### PDF Font Kurulumu (mPDF için)

Türkçe karakter desteği için:

```bash
composer require mpdf/mpdf
```

Özel fontlar eklemek için `vendor/mpdf/mpdf/ttfonts/` dizinine TTF font dosyalarını ekleyin.

### TCPDF Font Kurulumu

```bash
composer require tecnickcom/tcpdf
```

## 🐛 Sorun Giderme

### Hafıza Hatası

PHP bellek limitini artırın:

**php.ini**:
```ini
memory_limit = 512M
```

Veya kod içinde:
```php
ini_set('memory_limit', '512M');
```

### Zaman Aşımı Hatası

Execution time'ı artırın:

**php.ini**:
```ini
max_execution_time = 300
```

Veya kod içinde:
```php
set_time_limit(300);
```

### Yazma İzni Hatası

Dizinlere yazma izni verin:

```bash
chmod -R 775 writable/
chown -R www-data:www-data writable/
```

### Composer Bağımlılık Hatası

Tüm bağımlılıkları güncelleyin:

```bash
composer update
```

Veya cache'i temizleyin:

```bash
composer clear-cache
composer install
```

## 📚 Sonraki Adımlar

Kurulum tamamlandı! Şimdi:

1. [README.md](README.md) - Genel bakış ve temel kullanım
2. [EXAMPLES.md](EXAMPLES.md) - Detaylı örnekler ve kullanım senaryoları
3. API Dokümantasyonu - Tüm metodlar ve parametreler

## 💡 İpuçları

1. **Üretim Ortamı**: Üretim ortamında `error_reporting` ve `display_errors` ayarlarını kapatın.

2. **Güvenlik**: Export edilecek verileri mutlaka doğrulayın ve sanitize edin.

3. **Performans**: Büyük veri setleri için chunk kullanın ve progress bar ekleyin.

4. **Loglama**: Export işlemlerini loglayın.

```php
//log_message('info', 'Excel export tamamlandı: ' . $fileName);
```

5. **Queue Kullanımı**: Büyük dosyalar için queue sistemi kullanın.

## 🤝 Destek

Sorun yaşıyorsanız:
- GitHub Issues: [github.com/yakupeyisan/codeigniter4-saver/issues](https://github.com/yakupeyisan/codeigniter4-saver/issues)
- Email: yakupeyisan@gmail.com

## 📄 Lisans

MIT License - Detaylar için [LICENSE](LICENSE) dosyasına bakın.

