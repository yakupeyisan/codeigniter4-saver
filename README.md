# CodeIgniter 4 Saver

CodeIgniter 4 için kapsamlı ve güçlü dosya kaydetme paketi. Excel, Word, PDF, HTML, CSV ve daha fazla formatı destekler.

## 🎯 Özellikler

- ✅ **Excel Export/Import** - PhpSpreadsheet ile güçlü Excel işlemleri
- ✅ **Word Oluşturma** - PhpWord ile profesyonel Word belgeleri
- ✅ **PDF Oluşturma** - mPDF ve TCPDF desteği
- ✅ **HTML/SHTML Export** - Özelleştirilebilir HTML çıktıları
- ✅ **CSV Export/Import** - Hızlı ve kolay CSV işlemleri
- ✅ **Şablon Desteği** - Özel şablonlarla belgeler oluşturun
- ✅ **Veri Formatlaması** - Otomatik veri tipi algılama ve formatlama
- ✅ **Toplu İşlemler** - Büyük veri setlerini verimli işleme
- ✅ **Dosya İndirme** - Doğrudan tarayıcıya indirme
- ✅ **Dosya Kaydetme** - Sunucuya kaydetme desteği
- ✅ **Dosya Koruması** - Şifre koruması ve salt okuma modu
- ✅ **Arka Plan Antet** - Watermark ve antet desteği
- ✅ **Esnek API** - Kolay kullanım ve özelleştirme

## 📦 Kurulum

Composer ile kurulum:

```bash
composer require yakupeyisan/codeigniter4-saver
```

### .env Yapılandırması

`.env` dosyanıza aşağıdaki ayarları ekleyin:

```env
# Varsayılan kayıt yolu (writable/attachments/)
SAVER_DEFAULT_SAVE_PATH=attachments

# Otomatik kaydetme modu (true/false)
SAVER_AUTO_SAVE=false

# Varsayılan sürücü
SAVER_DEFAULT_DRIVER=excel
```

Detaylı yapılandırma için [SAVE_PATH_GUIDE.md](SAVE_PATH_GUIDE.md) dosyasına bakın.

## 🚀 Hızlı Başlangıç

### Excel Export

```php
use Yakupeyisan\CodeIgniter4Saver\Saver;

$saver = new Saver();

// Basit veri export
$data = [
    ['Ad', 'Soyad', 'Email'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
    ['Mehmet', 'Kaya', 'mehmet@example.com']
];

$saver->excel()
    ->setData($data)
    ->setFileName('kullanicilar.xlsx')
    ->download();

// Veya varsayılan yola kaydet (writable/attachments/)
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('kullanicilar.xlsx')
    ->saveToDefault();

// Veya özel yola kaydet
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('kullanicilar.xlsx')
    ->save('path/to/directory');
```

### PDF Oluşturma

```php
$saver = new Saver();

$html = '<h1>Başlık</h1><p>İçerik</p>';

$saver->pdf()
    ->setContent($html)
    ->setFileName('belge.pdf')
    ->setOrientation('portrait') // portrait veya landscape
    ->setPageSize('A4')
    ->download();
```

### Word Oluşturma

```php
$saver = new Saver();

$saver->word()
    ->addTitle('Belge Başlığı', 1)
    ->addText('Bu bir paragraftır.')
    ->addTable([
        ['Kolon 1', 'Kolon 2'],
        ['Değer 1', 'Değer 2']
    ])
    ->setFileName('belge.docx')
    ->download();
```

### HTML Export

```php
$saver = new Saver();

$data = [
    ['Ad', 'Soyad', 'Email'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com']
];

$saver->html()
    ->setData($data)
    ->setTitle('Kullanıcı Listesi')
    ->setTemplate('custom-template') // Opsiyonel
    ->setFileName('liste.html')
    ->download();
```

### CSV Export

```php
$saver = new Saver();

$data = [
    ['Ad', 'Soyad', 'Email'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com']
];

$saver->csv()
    ->setData($data)
    ->setDelimiter(',')
    ->setEnclosure('"')
    ->setFileName('data.csv')
    ->download();
```

## 📖 Detaylı Kullanım

Daha fazla örnek ve kullanım senaryosu için [EXAMPLES.md](EXAMPLES.md) dosyasına bakın.

## ⚙️ Yapılandırma

Konfigürasyon dosyasını kopyalayın:

```bash
php spark saver:publish
```

Veya manuel olarak `app/Config/Saver.php` dosyasını oluşturun:

```php
<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Saver extends BaseConfig
{
    public string $defaultDriver = 'excel';
    
    public string $tempPath = WRITEPATH . 'uploads/temp/';
    
    public string $savePath = WRITEPATH . 'uploads/saved/';
    
    // Excel ayarları
    public array $excel = [
        'default_format' => 'Xlsx',
        'creator' => 'CodeIgniter 4 Saver',
        'last_modified_by' => 'CodeIgniter 4 Saver',
    ];
    
    // PDF ayarları
    public array $pdf = [
        'engine' => 'mpdf', // mpdf veya tcpdf
        'default_orientation' => 'portrait',
        'default_page_size' => 'A4',
        'default_font' => 'dejavusans',
    ];
    
    // Word ayarları
    public array $word = [
        'default_font' => 'Arial',
        'default_font_size' => 11,
    ];
    
    // HTML ayarları
    public array $html = [
        'template_path' => APPPATH . 'Views/saver/templates/',
        'default_template' => 'default',
    ];
    
    // CSV ayarları
    public array $csv = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'encoding' => 'UTF-8',
    ];
}
```

## 🔒 Dosya Koruması ve Antet

### Şifre ile Koruma

```php
// Excel - Düzenleme için şifre
$saver->excel()
    ->setData($data)
    ->setFileName('korunmus.xlsx')
    ->setPassword('sifre123')
    ->download();

// Word - Düzenleme için şifre
$saver->word()
    ->addTitle('Belge', 1)
    ->setPassword('sifre123')
    ->download();

// PDF - Düzenleme için şifre
$saver->pdf()
    ->setContent($html)
    ->setPassword('sifre123')
    ->download();
```

### Sadece Okuma Modu

```php
// Düzenleme yasak, sadece görüntüleme
$saver->excel()
    ->setData($data)
    ->setReadOnly(true)
    ->download();
```

### Arka Plan Antet (Watermark)

```php
// Metin antet
$saver->excel()
    ->setData($data)
    ->setWatermark('GİZLİ - SADECE İÇ KULLANIM')
    ->download();

// Resim antet
$saver->pdf()
    ->setContent($html)
    ->setWatermarkImage(ROOTPATH . 'public/images/watermark.png')
    ->download();
```

### Kombine Kullanım

```php
// Hem koruma hem antet
$saver->pdf()
    ->setContent($html)
    ->setFileName('tam-korunmus.pdf')
    ->setPassword('sifre123')
    ->setWatermark('GİZLİ BELGE')
    ->setReadOnly(true)
    ->download();
```

Daha fazla örnek için [PROTECTION_EXAMPLES.md](PROTECTION_EXAMPLES.md) dosyasına bakın.

## 🔧 Gelişmiş Özellikler

### Excel'de Stil Ekleme

```php
$saver->excel()
    ->setData($data)
    ->setHeaderStyle([
        'font' => ['bold' => true],
        'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]
    ])
    ->setColumnWidths(['A' => 20, 'B' => 30])
    ->download();
```

### PDF'de Özel Ayarlar

```php
$saver->pdf('mpdf') // veya 'tcpdf'
    ->setContent($html)
    ->setMargins(15, 15, 15, 15)
    ->setHeader('Başlık Metni')
    ->setFooter('Sayfa {PAGENO}')
    ->setWatermark('TASLAK')
    ->download();
```

### Word'de Resim Ekleme

```php
$saver->word()
    ->addTitle('Rapor', 1)
    ->addText('Açıklama metni')
    ->addImage('path/to/image.jpg', ['width' => 200, 'height' => 200])
    ->addPageBreak()
    ->download();
```

## 🤝 Katkıda Bulunma

Katkılarınızı bekliyoruz! Pull request göndermekten çekinmeyin.

## 📄 Lisans

MIT Lisansı ile lisanslanmıştır.

## 👤 Yazar

**Yakup EYİSAN**
- Email: yakupeyisan@gmail.com
- GitHub: [@yakupeyisan](https://github.com/yakupeyisan)

## 🙏 Teşekkürler

Bu paket aşağıdaki harika kütüphaneleri kullanmaktadır:
- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet)
- [PhpWord](https://github.com/PHPOffice/PHPWord)
- [mPDF](https://github.com/mpdf/mpdf)
- [TCPDF](https://github.com/tecnickcom/tcpdf)

