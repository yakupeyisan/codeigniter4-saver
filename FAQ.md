# Sıkça Sorulan Sorular (FAQ)

CodeIgniter 4 Saver paketi hakkında sıkça sorulan sorular ve cevapları.

## Genel Sorular

### 🤔 Paket neler yapabilir?

CodeIgniter 4 Saver ile:
- Excel dosyaları oluşturabilirsiniz (Xlsx, Xls)
- Word belgeleri oluşturabilirsiniz (Docx)
- PDF dosyaları oluşturabilirsiniz (mPDF veya TCPDF)
- HTML sayfaları oluşturabilirsiniz
- CSV dosyaları oluşturabilir ve okuyabilirsiniz

### 💰 Paket ücretli mi?

Hayır, tamamen ücretsiz ve açık kaynaklıdır (MIT Lisansı).

### 🔄 Hangi PHP versiyonları destekleniyor?

PHP 8.1 ve üzeri versiyonlar desteklenmektedir.

### 🎯 CodeIgniter versiyonu?

CodeIgniter 4.0 ve üzeri tüm versiyonlarla uyumludur.

## Kurulum Soruları

### ❓ Composer olmadan kurabilir miyim?

Hayır, bu paket Composer gerektirir. Manuel kurulum önerilmez çünkü birçok bağımlılığı vardır.

### ❓ Kurulum sonrası ne yapmalıyım?

```bash
# 1. Config dosyasını publish edin
php spark saver:publish

# 2. Gerekli dizinleri oluşturun
mkdir -p writable/uploads/temp
mkdir -p writable/uploads/saved

# 3. İzinleri ayarlayın
chmod -R 775 writable/uploads
```

### ❓ "Class not found" hatası alıyorum

```bash
# Composer autoload'u yeniden oluşturun
composer dump-autoload

# Cache'i temizleyin
php spark cache:clear
```

## Kullanım Soruları

### ❓ Türkçe karakterler bozuluyor

Excel için:
```php
$saver->excel()
    ->setData($data)
    ->download(); // Xlsx otomatik UTF-8 destekler
```

CSV için:
```php
$saver->csv()
    ->setData($data)
    ->setBom(true) // Excel için BOM ekle
    ->setEncoding('UTF-8')
    ->download();
```

PDF için:
```php
$saver->pdf()
    ->setFont('dejavusans') // Unicode destekli font
    ->setContent($html)
    ->download();
```

### ❓ Büyük veri setleri nasıl işlenir?

```php
// Bellek limitini artırın
ini_set('memory_limit', '512M');
set_time_limit(300);

// Chunk kullanın
$data = [['Header1', 'Header2']];
$userModel->chunk(1000, function($users) use (&$data) {
    foreach ($users as $user) {
        $data[] = [$user->field1, $user->field2];
    }
});

$saver->excel()->setData($data)->download();
```

### ❓ Model verilerini nasıl export ederim?

```php
use App\Models\UserModel;
use Yakupeyisan\CodeIgniter4Saver\Saver;

$userModel = new UserModel();

// Hızlı export
Saver::exportFromModel($userModel, 'excel', 'users.xlsx');

// Manuel
$users = $userModel->findAll();
$data = [['ID', 'Name', 'Email']];
foreach ($users as $user) {
    $data[] = [$user->id, $user->name, $user->email];
}

$saver->excel()->setData($data)->download();
```

### ❓ Email eki olarak nasıl gönderilir?

```php
$saver = new Saver();

// Excel'i string olarak al
$excelContent = $saver->excel()
    ->setData($data)
    ->toString();

// Email gönder
$email = \Config\Services::email();
$email->setTo('user@example.com');
$email->setSubject('Rapor');
$email->setMessage('Ekteki raporu inceleyebilirsiniz.');
$email->attach(
    $excelContent,
    'attachment',
    'report.xlsx',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);
$email->send();
```

### ❓ Dosyayı sunucuya nasıl kaydederim?

```php
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('report.xlsx')
    ->save(WRITEPATH . 'exports/');

echo "Dosya kaydedildi: " . $filePath;
```

### ❓ Özel şablon nasıl kullanılır?

1. Şablon dosyası oluşturun: `app/Views/saver/templates/custom.php`

```php
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        /* Özel stilleriniz */
    </style>
</head>
<body>
    <h1><?= $title ?></h1>
    <!-- Özel HTML kodunuz -->
    <table>
        <?php foreach ($data as $row): ?>
            <!-- ... -->
        <?php endforeach; ?>
    </table>
</body>
</html>
```

2. Kullanın:

```php
$saver->html()
    ->setData($data)
    ->setTemplate('custom')
    ->download();
```

## Hata Çözümleri

### ❌ "Directory not writable" hatası

```bash
# Dizin izinlerini kontrol edin
ls -la writable/uploads/

# İzinleri düzeltin
chmod -R 775 writable/uploads/
chown -R www-data:www-data writable/

# Dizin yoksa oluşturun
mkdir -p writable/uploads/temp
mkdir -p writable/uploads/saved
```

### ❌ "Memory limit" hatası

```php
// Controller'da
ini_set('memory_limit', '512M');

// Veya php.ini'de
memory_limit = 512M
```

### ❌ "Maximum execution time" hatası

```php
// Controller'da
set_time_limit(300); // 5 dakika

// Veya php.ini'de
max_execution_time = 300
```

### ❌ "Class 'PhpOffice\...' not found"

```bash
# Bağımlılıkları tekrar yükleyin
composer install

# Veya güncelle
composer update

# Autoload'u yeniden oluştur
composer dump-autoload
```

### ❌ PDF Türkçe karakter sorunu

```php
// Unicode destekli font kullanın
$saver->pdf()
    ->setFont('dejavusans') // veya 'freesans'
    ->setContent($html)
    ->download();
```

## Performans Soruları

### ⚡ En hızlı format hangisi?

Hız sıralaması (1000 satır için):
1. CSV (~0.5 saniye)
2. HTML (~1 saniye)
3. Excel (~2 saniye)
4. PDF (~3 saniye)
5. Word (~4 saniye)

### ⚡ Excel performansını nasıl artırabilirim?

```php
// 1. Otomatik kolon genişliğini kapatın
$excel->setColumnWidths(['A' => 15, 'B' => 20]); // Manuel

// 2. Stil karmaşıklığını azaltın
// Basit stiller kullanın

// 3. Formül kullanmayın
// Hesaplanmış değerleri direkt ekleyin

// 4. Chunk ile işleyin
// Büyük veri setlerini parçalara bölün
```

### ⚡ PDF oluşturma yavaş

```php
// TCPDF yerine mPDF kullanın (daha hızlı)
$saver->pdf('mpdf')
    ->setContent($html)
    ->download();

// HTML'i basit tutun
// Karmaşık CSS'den kaçının
// Resimler için optimize edilmiş boyutlar kullanın
```

## İleri Düzey Sorular

### 🔧 PhpSpreadsheet nesnesine direkt erişim

```php
$excelDriver = $saver->excel();
$excelDriver->setData($data);

// PhpSpreadsheet nesnesine erişim
$spreadsheet = $excelDriver->getSpreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Özel işlemler
$sheet->setCellValue('A1', 'Custom Value');
$sheet->mergeCells('A1:D1');

$excelDriver->download();
```

### 🔧 Çoklu sayfa (sheet) eklemek

```php
$excelDriver = $saver->excel();
$spreadsheet = $excelDriver->getSpreadsheet();

// İlk sayfa
$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Sayfa 1');
$sheet1->fromArray($data1);

// İkinci sayfa
$sheet2 = $spreadsheet->createSheet();
$sheet2->setTitle('Sayfa 2');
$sheet2->fromArray($data2);

$excelDriver->setFileName('multi-sheet.xlsx')->download();
```

### 🔧 Özel sütun formatları

```php
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$excelDriver = $saver->excel();
$spreadsheet = $excelDriver->getSpreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Para formatı
$sheet->getStyle('D2:D100')
    ->getNumberFormat()
    ->setFormatCode('#,##0.00 ₺');

// Tarih formatı
$sheet->getStyle('E2:E100')
    ->getNumberFormat()
    ->setFormatCode('DD.MM.YYYY');

// Yüzde formatı
$sheet->getStyle('F2:F100')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

$excelDriver->download();
```

### 🔧 Queue ile kullanım

```php
// Job sınıfı
namespace App\Jobs;

use CodeIgniter\Queue\Job;

class ExportJob extends Job
{
    public function process()
    {
        $data = $this->data['data'];
        $fileName = $this->data['fileName'];
        
        $saver = new \Yakupeyisan\CodeIgniter4Saver\Saver();
        $filePath = $saver->excel()
            ->setData($data)
            ->setFileName($fileName)
            ->save(WRITEPATH . 'exports/');
        
        // Email gönder
        // Kullanıcıyı bilgilendir
    }
}

// Controller'da
$job = new ExportJob([
    'data' => $largeDataSet,
    'fileName' => 'large_export.xlsx'
]);

service('queue')->push($job);
```

## Troubleshooting

### 🔍 Debug modu

```php
// Config'de
public $debug = true;

// Hata detaylarını göster
try {
    $saver->excel()->setData($data)->download();
} catch (\Exception $e) {
    //log_message('error', $e->getMessage());
    //log_message('error', $e->getTraceAsString());
    throw $e;
}
```

### 🔍 Log kontrolü

```bash
# CodeIgniter logları
tail -f writable/logs/log-*.log

# PHP error log
tail -f /var/log/php/error.log
```

## Destek

### 💬 Nasıl yardım alabilirim?

1. **Dokümantasyon**: README.md, EXAMPLES.md, API_REFERENCE.md
2. **GitHub Issues**: Hata bildirimi veya soru
3. **Email**: yakupeyisan@gmail.com

### 📧 Email ile soru sorarken

Lütfen şunları ekleyin:
- PHP versiyonu
- CodeIgniter versiyonu
- Paket versiyonu
- Hata mesajı
- Minimal örnek kod
- Ne denediniz?

---

**Sorunuz cevap bulamadı mı?**

GitHub'da issue açın: https://github.com/yakupeyisan/codeigniter4-saver/issues

