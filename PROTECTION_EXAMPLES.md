# Dosya Koruması ve Antet Örnekleri

Bu dokümanda dosya koruması (şifre, salt okuma) ve arka plan antet (watermark) özelliklerinin kullanım örnekleri bulunmaktadır.

## 📋 İçindekiler

- [Excel Koruması](#excel-koruması)
- [Word Koruması](#word-koruması)
- [PDF Koruması](#pdf-koruması)
- [Antet/Watermark](#antetwatermark)

---

## Excel Koruması

### Şifre ile Koruma

```php
use Yakupeyisan\CodeIgniter4Saver\Saver;

$saver = new Saver();

$data = [
    ['Ad', 'Soyad', 'Email'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
];

// Şifre ile koruma (düzenleme için şifre gerekir)
$saver->excel()
    ->setData($data)
    ->setFileName('korunmus.xlsx')
    ->setPassword('sifre123') // Düzenleme için şifre
    ->download();
```

### Sadece Okuma Modu (Şifresiz)

```php
// Sadece görüntüleme, düzenleme yasak
$saver->excel()
    ->setData($data)
    ->setFileName('salt-okuma.xlsx')
    ->setReadOnly(true) // Düzenleme yasak
    ->download();
```

### Özel Koruma Seçenekleri

```php
// Hangi işlemlere izin verileceğini belirle
$saver->excel()
    ->setData($data)
    ->setFileName('ozel-koruma.xlsx')
    ->setPassword('sifre123')
    ->setProtectionOptions([
        'formatCells' => false,      // Hücre formatlama yasak
        'insertRows' => false,       // Satır ekleme yasak
        'deleteRows' => false,       // Satır silme yasak
        'selectLockedCells' => true, // Kilitli hücreleri seçebilir
        'selectUnlockedCells' => true, // Kilitli olmayan hücreleri seçebilir
    ])
    ->download();
```

### Excel'de Arka Plan Antet

```php
// Metin antet
$saver->excel()
    ->setData($data)
    ->setFileName('antetli.xlsx')
    ->setWatermark('GİZLİ - SADECE İÇ KULLANIM')
    ->download();

// Resim antet
$saver->excel()
    ->setData($data)
    ->setFileName('resim-antetli.xlsx')
    ->setWatermarkImage(ROOTPATH . 'public/images/logo-watermark.png')
    ->download();
```

### Kombine Kullanım

```php
// Hem koruma hem antet
$saver->excel()
    ->setData($data)
    ->setFileName('tam-korunmus.xlsx')
    ->setPassword('sifre123')
    ->setWatermark('GİZLİ BELGE')
    ->setReadOnly(true)
    ->download();
```

---

## Word Koruması

### Şifre ile Koruma

```php
$saver->word()
    ->addTitle('Gizli Rapor', 1)
    ->addText('Bu belge korumalıdır.')
    ->setFileName('korunmus.docx')
    ->setPassword('sifre123')
    ->download();
```

### Sadece Okuma Modu

```php
$saver->word()
    ->addTitle('Rapor', 1)
    ->addTable($data)
    ->setFileName('salt-okuma.docx')
    ->setReadOnly(true)
    ->download();
```

### Word'de Arka Plan Antet

```php
// Metin antet
$saver->word()
    ->addTitle('Belge', 1)
    ->addText('İçerik')
    ->setWatermark('TASLAK')
    ->setFileName('antetli.docx')
    ->download();

// Resim antet
$saver->word()
    ->addTitle('Belge', 1)
    ->setWatermarkImage(ROOTPATH . 'public/images/watermark.png')
    ->setFileName('resim-antetli.docx')
    ->download();
```

---

## PDF Koruması

### Şifre ile Koruma

```php
// Düzenleme için şifre
$saver->pdf()
    ->setContent('<h1>Gizli Belge</h1>')
    ->setFileName('korunmus.pdf')
    ->setPassword('sifre123') // Düzenleme için şifre
    ->download();
```

### Kullanıcı ve Sahip Şifresi

```php
// Görüntüleme için kullanıcı şifresi
// Düzenleme için sahip şifresi
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setFileName('cift-sifreli.pdf')
    ->setUserPassword('goster123')  // Görüntüleme şifresi
    ->setPassword('duzenle123')     // Düzenleme şifresi
    ->download();
```

### İzin Kontrolü

```php
// Hangi işlemlere izin verileceğini belirle
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setFileName('izinli.pdf')
    ->setPassword('sifre123')
    ->setPermissions([
        'print' => true,      // Yazdırma izni var
        'modify' => false,    // Düzenleme yasak
        'copy' => false,      // Kopyalama yasak
        'annot-forms' => false, // Form doldurma yasak
    ])
    ->download();
```

### Sadece Okuma Modu

```php
// Sadece görüntüleme, tüm düzenlemeler yasak
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setFileName('salt-okuma.pdf')
    ->setReadOnly(true) // Tüm düzenleme izinleri kapatılır
    ->download();
```

### PDF'de Arka Plan Antet

```php
// Metin antet
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setWatermark('GİZLİ')
    ->setFileName('antetli.pdf')
    ->download();

// Resim antet
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setWatermarkImage(ROOTPATH . 'public/images/watermark.png')
    ->setFileName('resim-antetli.pdf')
    ->download();
```

### Kombine Kullanım

```php
// Hem koruma hem antet
$saver->pdf()
    ->setContent('<h1>Gizli Belge</h1>')
    ->setFileName('tam-korunmus.pdf')
    ->setPassword('sifre123')
    ->setWatermark('GİZLİ - SADECE İÇ KULLANIM')
    ->setReadOnly(true)
    ->setPermissions([
        'print' => false,
        'modify' => false,
        'copy' => false,
    ])
    ->download();
```

---

## Antet/Watermark

### Metin Antet

Tüm formatlarda metin antet eklenebilir:

```php
// Excel
$saver->excel()->setWatermark('GİZLİ')->download();

// Word
$saver->word()->setWatermark('TASLAK')->download();

// PDF
$saver->pdf()->setWatermark('KONFİDYANSİYEL')->download();
```

### Resim Antet

Resim dosyası ile antet ekleme:

```php
$watermarkPath = ROOTPATH . 'public/images/company-watermark.png';

// Excel
$saver->excel()
    ->setData($data)
    ->setWatermarkImage($watermarkPath)
    ->download();

// Word
$saver->word()
    ->addTitle('Belge', 1)
    ->setWatermarkImage($watermarkPath)
    ->download();

// PDF
$saver->pdf()
    ->setContent('<h1>Belge</h1>')
    ->setWatermarkImage($watermarkPath)
    ->download();
```

### Antet Özelleştirme

```php
// Excel'de header/footer olarak
$saver->excel()
    ->setData($data)
    ->setWatermark('Şirket Adı - Gizli Belge')
    ->download();

// PDF'de sayfa boyutuna göre otomatik ayarlanır
$saver->pdf()
    ->setContent($html)
    ->setWatermark('WATERMARK')
    ->setPageSize('A4')
    ->download();
```

---

## Gerçek Dünya Senaryoları

### Senaryo 1: Finansal Rapor (Sadece Görüntüleme)

```php
$saver->pdf()
    ->setContent($financialReportHtml)
    ->setFileName('finansal-rapor.pdf')
    ->setReadOnly(true) // Düzenleme yasak
    ->setWatermark('GİZLİ - FİNANSAL RAPOR')
    ->setPassword('') // Şifre yok, sadece read-only
    ->download();
```

### Senaryo 2: Müşteri Sözleşmesi (Şifreli)

```php
$saver->word()
    ->addTitle('Hizmet Sözleşmesi', 1)
    ->addText($contractText)
    ->setFileName('sozlesme.docx')
    ->setPassword('contract2024') // Düzenleme için şifre
    ->setWatermark('RESMİ BELGE')
    ->download();
```

### Senaryo 3: Excel Veri Tablosu (Kısmi Koruma)

```php
$saver->excel()
    ->setData($sensitiveData)
    ->setFileName('veri-tablosu.xlsx')
    ->setPassword('data2024')
    ->setProtectionOptions([
        'formatCells' => false,    // Formatlama yasak
        'insertRows' => false,     // Satır ekleme yasak
        'deleteRows' => false,     // Satır silme yasak
        'selectLockedCells' => true, // Seçim izni var
    ])
    ->setWatermark('İÇ KULLANIM')
    ->download();
```

### Senaryo 4: Çok Katmanlı Koruma

```php
// Hem görüntüleme hem düzenleme şifresi
$saver->pdf()
    ->setContent($confidentialDocument)
    ->setFileName('cok-gizli.pdf')
    ->setUserPassword('view123')   // Görüntüleme şifresi
    ->setPassword('edit123')       // Düzenleme şifresi
    ->setPermissions([
        'print' => false,          // Yazdırma yasak
        'modify' => false,         // Düzenleme yasak
        'copy' => false,           // Kopyalama yasak
    ])
    ->setWatermark('ÇOK GİZLİ')
    ->download();
```

---

## Notlar

### Excel

- **Xlsx formatı**: Tam koruma desteği
- **Xls formatı**: Sınırlı koruma desteği
- **Csv formatı**: Koruma desteklenmez

### Word

- **Docx formatı**: Tam koruma desteği
- Şifre koruması PhpWord'ün sürümüne bağlıdır
- Read-only modu bazı sürümlerde sınırlı olabilir

### PDF

- **mPDF**: Tam koruma ve izin desteği
- **TCPDF**: Tam koruma ve izin desteği
- Her iki motor da watermark destekler

### Güvenlik

- Şifreler açık metin olarak saklanmaz
- PDF şifreleme AES-128 veya AES-256 kullanır
- Excel/Word şifreleme format standartlarına uygundur

---

## Sorun Giderme

### Excel'de Şifre Çalışmıyor

```php
// Xlsx formatı kullanın (Xls'de sınırlı)
$saver->excel('Xlsx')
    ->setPassword('sifre')
    ->download();
```

### Word'de Koruma Uygulanmıyor

PhpWord sürümünü kontrol edin:
```bash
composer show phpoffice/phpword
```

### PDF'de İzinler Çalışmıyor

```php
// mPDF kullanın (daha iyi destek)
$saver->pdf('mpdf')
    ->setPassword('sifre')
    ->setPermissions([...])
    ->download();
```

---

**Son Güncelleme**: 2024-12-15

