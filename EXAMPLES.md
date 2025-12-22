# CodeIgniter 4 Saver - Detaylı Örnekler

Bu dokümanda CodeIgniter 4 Saver paketinin tüm özelliklerini ve kullanım senaryolarını bulabilirsiniz.

## İçindekiler

- [Excel İşlemleri](#excel-işlemleri)
- [Word Belgeleri](#word-belgeleri)
- [PDF Oluşturma](#pdf-oluşturma)
- [HTML Export](#html-export)
- [CSV İşlemleri](#csv-işlemleri)
- [Model Export](#model-export)
- [İleri Düzey Kullanım](#ileri-düzey-kullanım)

---

## Excel İşlemleri

### Basit Excel Export

```php
use Yakupeyisan\CodeIgniter4Saver\Saver;

$data = [
    ['Ad', 'Soyad', 'Email', 'Telefon'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com', '555-0001'],
    ['Mehmet', 'Kaya', 'mehmet@example.com', '555-0002'],
    ['Ayşe', 'Demir', 'ayse@example.com', '555-0003'],
];

$saver = new Saver();
$saver->excel()
    ->setData($data)
    ->setFileName('kullanicilar.xlsx')
    ->download();
```

### Excel Format Seçimi

```php
// XLSX (varsayılan)
$saver->excel('Xlsx')
    ->setData($data)
    ->setFileName('rapor')
    ->download();

// XLS (eski Excel formatı)
$saver->excel('Xls')
    ->setData($data)
    ->setFileName('rapor')
    ->download();
```

### Excel Stil ve Formatlama

```php
$saver->excel()
    ->setData($data)
    ->setFileName('styled_report.xlsx')
    ->setSheetTitle('Kullanıcı Listesi')
    ->setHeaderStyle([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 12,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4CAF50'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ])
    ->setColumnWidths([
        'A' => 20,
        'B' => 25,
        'C' => 35,
        'D' => 20,
    ])
    ->setAutoFilter(true)
    ->download();
```

### Excel Dosyasını Sunucuya Kaydetme

```php
$filePath = $saver->excel()
    ->setData($data)
    ->setFileName('backup.xlsx')
    ->save(WRITEPATH . 'exports/');

echo "Dosya kaydedildi: " . $filePath;
```

### İleri Düzey Excel Kullanımı

```php
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$excelDriver = $saver->excel();
$excelDriver->setData($data);

// Spreadsheet nesnesine direkt erişim
$spreadsheet = $excelDriver->getSpreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Para formatı uygula
$sheet->getStyle('D2:D' . count($data))
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

// Formül ekle
$sheet->setCellValue('D' . (count($data) + 2), '=SUM(D2:D' . count($data) . ')');

$excelDriver->setFileName('advanced_report.xlsx')->download();
```

---

## Word Belgeleri

### Basit Word Belgesi

```php
$saver->word()
    ->addTitle('Rapor Başlığı', 1)
    ->addText('Bu bir örnek rapordur.')
    ->addTextBreak()
    ->addText('İkinci paragraf.')
    ->setFileName('rapor.docx')
    ->download();
```

### Word'de Tablo Ekleme

```php
$tableData = [
    ['Ürün', 'Miktar', 'Fiyat'],
    ['Laptop', '5', '15000 TL'],
    ['Mouse', '20', '200 TL'],
    ['Klavye', '15', '500 TL'],
];

$saver->word()
    ->addTitle('Ürün Listesi', 1)
    ->addTextBreak()
    ->addTable($tableData)
    ->setFileName('urunler.docx')
    ->download();
```

### Word'de Liste ve Resim

```php
$saver->word()
    ->addTitle('Proje Raporu', 1)
    ->addTextBreak()
    ->addTitle('Yapılacaklar', 2)
    ->addListItems([
        'Tasarım tamamlanacak',
        'Backend geliştirme',
        'Frontend entegrasyonu',
        'Test ve deployment'
    ])
    ->addTextBreak(2)
    ->addTitle('Ekran Görüntüsü', 2)
    ->addImage(ROOTPATH . 'public/images/screenshot.jpg', [
        'width' => 400,
        'height' => 300,
        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
    ])
    ->addPageBreak()
    ->addTitle('İkinci Bölüm', 1)
    ->addText('Yeni sayfadaki içerik')
    ->setFileName('proje_raporu.docx')
    ->download();
```

### Özel Stil ile Metin

```php
$saver->word()
    ->addText('Normal metin')
    ->addTextBreak()
    ->addText('Kalın ve büyük metin', [
        'bold' => true,
        'size' => 16,
        'color' => 'FF0000',
    ])
    ->addTextBreak()
    ->addText('İtalik ve altı çizili', [
        'italic' => true,
        'underline' => 'single',
    ])
    ->setFileName('styled_text.docx')
    ->download();
```

---

## PDF Oluşturma

### Basit PDF (mPDF)

```php
$html = '<h1>PDF Başlığı</h1>';
$html .= '<p>Bu bir örnek PDF belgesidir.</p>';
$html .= '<p>İkinci paragraf.</p>';

$saver->pdf('mpdf')
    ->setContent($html)
    ->setFileName('belge.pdf')
    ->download();
```

### PDF (TCPDF)

```php
$saver->pdf('tcpdf')
    ->setContent($html)
    ->setFileName('belge.pdf')
    ->download();
```

### PDF Sayfa Ayarları

```php
$saver->pdf()
    ->setContent($html)
    ->setOrientation('landscape') // veya 'portrait'
    ->setPageSize('A3') // A4, A3, Letter, vb.
    ->setMargins(20, 20, 20, 20) // left, right, top, bottom
    ->setFileName('custom_page.pdf')
    ->download();
```

### PDF'de Başlık, Altbilgi ve Filigran

```php
$saver->pdf('mpdf')
    ->setContent($html)
    ->setHeader('Şirket Adı - Rapor')
    ->setFooter('Sayfa {PAGENO} / {nbpg}')
    ->setWatermark('TASLAK')
    ->setFileName('official_document.pdf')
    ->download();
```

### Tablo Verisinden PDF

```php
$data = [
    ['ID', 'Ad', 'Email'],
    ['1', 'Ahmet Yılmaz', 'ahmet@example.com'],
    ['2', 'Mehmet Kaya', 'mehmet@example.com'],
];

$saver->pdf()
    ->setData($data) // Otomatik HTML tablosuna çevrilir
    ->setFileName('users.pdf')
    ->download();
```

### Özel HTML ile PDF

```php
$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #333; text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
    <h1>Aylık Satış Raporu</h1>
    <table>
        <thead>
            <tr>
                <th>Ürün</th>
                <th>Miktar</th>
                <th>Tutar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ürün 1</td>
                <td>100</td>
                <td>5.000 TL</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
HTML;

$saver->pdf()
    ->setContent($html)
    ->setFileName('monthly_report.pdf')
    ->download();
```

---

## HTML Export

### Basit HTML Export

```php
$data = [
    ['İsim', 'Email', 'Telefon'],
    ['Ahmet', 'ahmet@example.com', '555-0001'],
    ['Mehmet', 'mehmet@example.com', '555-0002'],
];

$saver->html()
    ->setData($data)
    ->setTitle('Kullanıcı Listesi')
    ->setFileName('users.html')
    ->download();
```

### Özel CSS ile HTML

```php
$customCss = <<<CSS
table { 
    width: 100%; 
    border: 2px solid #4CAF50;
}
th { 
    background-color: #4CAF50 !important; 
    color: white !important;
}
CSS;

$saver->html()
    ->setData($data)
    ->setTitle('Styled Report')
    ->addCustomCss($customCss)
    ->setFileName('styled_report.html')
    ->download();
```

### Özel JavaScript ile HTML

```php
$customJs = <<<JS
document.addEventListener('DOMContentLoaded', function() {
    console.log('Sayfa yüklendi');
    
    // Tablo satırlarına tıklama eventi ekle
    document.querySelectorAll('tr').forEach(row => {
        row.addEventListener('click', function() {
            this.style.backgroundColor = '#ffeb3b';
        });
    });
});
JS;

$saver->html()
    ->setData($data)
    ->addCustomJs($customJs)
    ->setFileName('interactive.html')
    ->download();
```

---

## CSV İşlemleri

### Basit CSV Export

```php
$data = [
    ['Ad', 'Soyad', 'Email'],
    ['Ahmet', 'Yılmaz', 'ahmet@example.com'],
    ['Mehmet', 'Kaya', 'mehmet@example.com'],
];

$saver->csv()
    ->setData($data)
    ->setFileName('users.csv')
    ->download();
```

### CSV Ayarları

```php
$saver->csv()
    ->setData($data)
    ->setDelimiter(';') // Noktalı virgül
    ->setEnclosure('"')
    ->setEncoding('UTF-8')
    ->setBom(true) // Excel için UTF-8 desteği
    ->setFileName('users.csv')
    ->download();
```

### CSV Okuma

```php
$csvDriver = $saver->csv();

// Başlıklı CSV okuma
$data = $csvDriver->read(WRITEPATH . 'uploads/data.csv', true);

// Başlıksız CSV okuma
$data = $csvDriver->read(WRITEPATH . 'uploads/data.csv', false);

print_r($data);
```

---

## Model Export

### Model Verilerini Excel'e Aktarma

```php
use App\Models\UserModel;
use Yakupeyisan\CodeIgniter4Saver\Saver;

$userModel = new UserModel();

Saver::exportFromModel($userModel, 'excel', 'users.xlsx');
```

### Model Verilerini PDF'e Aktarma

```php
Saver::exportFromModel($userModel, 'pdf', 'users.pdf');
```

### Model Verilerini CSV'ye Aktarma

```php
Saver::exportFromModel($userModel, 'csv', 'users.csv');
```

---

## İleri Düzey Kullanım

### Helper Fonksiyonları

```php
// Helper'ı yükle
helper('saver');

// Hızlı Excel export
export_excel($data, 'rapor.xlsx');

// Hızlı CSV export
export_csv($data, 'data.csv');

// Hızlı PDF export
export_pdf('<h1>PDF İçeriği</h1>', 'belge.pdf');

// Hızlı HTML export
export_html($data, 'rapor.html', 'Rapor Başlığı');

// Hızlı Word export
export_word($data, 'belge.docx');
```

### Controller'da Kullanım

```php
<?php

namespace App\Controllers;

use Yakupeyisan\CodeIgniter4Saver\Saver;
use App\Models\ProductModel;

class ExportController extends BaseController
{
    public function exportProducts()
    {
        $productModel = new ProductModel();
        $products = $productModel->findAll();
        
        // Veriyi formatla
        $data = [['ID', 'Ürün Adı', 'Fiyat', 'Stok']];
        
        foreach ($products as $product) {
            $data[] = [
                $product->id,
                $product->name,
                $product->price . ' TL',
                $product->stock,
            ];
        }
        
        // Format parametresini al
        $format = $this->request->getGet('format') ?? 'excel';
        
        $saver = new Saver();
        
        switch ($format) {
            case 'excel':
                $saver->excel()
                    ->setData($data)
                    ->setFileName('products.xlsx')
                    ->setAutoFilter(true)
                    ->download();
                break;
                
            case 'pdf':
                $saver->pdf()
                    ->setData($data)
                    ->setFileName('products.pdf')
                    ->download();
                break;
                
            case 'csv':
                $saver->csv()
                    ->setData($data)
                    ->setFileName('products.csv')
                    ->download();
                break;
                
            default:
                return $this->response->setJSON([
                    'error' => 'Geçersiz format'
                ]);
        }
    }
    
    public function saveReport()
    {
        $data = [/* ... */];
        
        $saver = new Saver();
        
        // Excel olarak kaydet
        $excelPath = $saver->excel()
            ->setData($data)
            ->setFileName('report_' . date('Y-m-d') . '.xlsx')
            ->save(WRITEPATH . 'reports/');
        
        // PDF olarak kaydet
        $pdfPath = $saver->pdf()
            ->setData($data)
            ->setFileName('report_' . date('Y-m-d') . '.pdf')
            ->save(WRITEPATH . 'reports/');
        
        return $this->response->setJSON([
            'success' => true,
            'excel' => $excelPath,
            'pdf' => $pdfPath,
        ]);
    }
}
```

### String Olarak Alma (Email için)

```php
// Excel'i email eki için hazırla
$excelContent = $saver->excel()
    ->setData($data)
    ->toString();

// Email gönder
$email = \Config\Services::email();
$email->attach($excelContent, 'attachment', 'report.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// PDF'i email eki için hazırla
$pdfContent = $saver->pdf()
    ->setContent($html)
    ->toString();

$email->attach($pdfContent, 'attachment', 'document.pdf', 'application/pdf');
```

### Çoklu Format Export

```php
public function exportMultiple()
{
    $data = [/* ... */];
    
    $saver = new Saver();
    $fileName = 'report_' . date('Y-m-d');
    $savePath = WRITEPATH . 'exports/';
    
    // Excel
    $saver->excel()->setData($data)->setFileName($fileName)->save($savePath);
    
    // PDF
    $saver->pdf()->setData($data)->setFileName($fileName)->save($savePath);
    
    // CSV
    $saver->csv()->setData($data)->setFileName($fileName)->save($savePath);
    
    // HTML
    $saver->html()->setData($data)->setFileName($fileName)->save($savePath);
    
    return "Tüm formatlar başarıyla kaydedildi!";
}
```

---

## Performans İpuçları

### Büyük Veri Setleri

```php
// Büyük veri setleri için chunk kullanın
$userModel = new UserModel();

$data = [['ID', 'Ad', 'Email']];

$userModel->chunk(1000, function ($users) use (&$data) {
    foreach ($users as $user) {
        $data[] = [$user->id, $user->name, $user->email];
    }
});

$saver->excel()->setData($data)->setFileName('all_users.xlsx')->download();
```

### Bellek Optimizasyonu

```php
// Büyük dosyalar için PHP bellek limitini artırın
ini_set('memory_limit', '512M');
set_time_limit(300);

// Stream kullanımı
$saver->excel()
    ->setData($largeDataSet)
    ->save(WRITEPATH . 'exports/');
```

---

## Hata Yönetimi

```php
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

try {
    $saver->excel()
        ->setData($data)
        ->setFileName('report.xlsx')
        ->download();
} catch (SaverException $e) {
    //log_message('error', 'Export hatası: ' . $e->getMessage());
    return $this->response->setJSON([
        'error' => 'Dosya oluşturulamadı'
    ]);
}
```

