# API Reference

CodeIgniter 4 Saver paketi için tam API referans dokümantasyonu.

## İçindekiler

- [Saver](#saver)
- [ExcelDriver](#exceldriver)
- [WordDriver](#worddriver)
- [PdfDriver](#pdfdriver)
- [HtmlDriver](#htmldriver)
- [CsvDriver](#csvdriver)
- [Helper Functions](#helper-functions)
- [Exceptions](#exceptions)

---

## Saver

Ana saver sınıfı. Tüm driver'lara erişim sağlar.

### Constructor

```php
public function __construct(?SaverConfig $config = null)
```

**Parametreler:**
- `$config` (SaverConfig|null): Özel konfigürasyon nesnesi

**Örnek:**
```php
$saver = new Saver();
// veya özel config ile
$config = new \Config\Saver();
$saver = new Saver($config);
```

### excel()

Excel driver'ını döndürür.

```php
public function excel(?string $format = null): ExcelDriver
```

**Parametreler:**
- `$format` (string|null): Excel formatı (Xlsx, Xls, Csv)

**Döner:** ExcelDriver

**Örnek:**
```php
$excel = $saver->excel();
$excel = $saver->excel('Xlsx');
```

### word()

Word driver'ını döndürür.

```php
public function word(): WordDriver
```

**Döner:** WordDriver

### pdf()

PDF driver'ını döndürür.

```php
public function pdf(?string $engine = null): PdfDriver
```

**Parametreler:**
- `$engine` (string|null): PDF motoru (mpdf, tcpdf)

**Döner:** PdfDriver

### html()

HTML driver'ını döndürür.

```php
public function html(): HtmlDriver
```

**Döner:** HtmlDriver

### csv()

CSV driver'ını döndürür.

```php
public function csv(): CsvDriver
```

**Döner:** CsvDriver

### Static Methods

#### exportExcel()

Hızlı Excel export.

```php
public static function exportExcel(array $data, string $fileName, string $format = 'Xlsx'): void
```

#### exportCsv()

Hızlı CSV export.

```php
public static function exportCsv(array $data, string $fileName): void
```

#### exportPdf()

Hızlı PDF export.

```php
public static function exportPdf(string $html, string $fileName, string $engine = 'mpdf'): void
```

#### exportHtml()

Hızlı HTML export.

```php
public static function exportHtml(array $data, string $fileName, string $title = 'Document'): void
```

#### exportFromModel()

Model verilerini export eder.

```php
public static function exportFromModel(
    object $model,
    string $driver = 'excel',
    string $fileName = 'export',
    array $options = []
): void
```

---

## ExcelDriver

Excel dosyaları oluşturma ve yönetme.

### setData()

```php
public function setData(array $data): self
```

Veriyi ayarlar.

**Parametreler:**
- `$data` (array): 2 boyutlu array

**Döner:** self (method chaining için)

**Throws:** SaverException - Veri boş ise

### setFileName()

```php
public function setFileName(string $fileName): self
```

Dosya adını ayarlar.

### setFormat()

```php
public function setFormat(string $format): self
```

Excel formatını belirler.

**Parametreler:**
- `$format` (string): Xlsx, Xls veya Csv

**Throws:** SaverException - Geçersiz format

### setSheetTitle()

```php
public function setSheetTitle(string $title): self
```

Sayfa başlığını ayarlar.

### setHeaderStyle()

```php
public function setHeaderStyle(array $style): self
```

Başlık satırı stilini ayarlar.

**Parametreler:**
- `$style` (array): PhpSpreadsheet stil array'i

**Örnek:**
```php
$excel->setHeaderStyle([
    'font' => ['bold' => true, 'size' => 12],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00']
    ]
]);
```

### setColumnWidths()

```php
public function setColumnWidths(array $widths): self
```

Kolon genişliklerini ayarlar.

**Parametreler:**
- `$widths` (array): Kolon harfi => genişlik

**Örnek:**
```php
$excel->setColumnWidths(['A' => 20, 'B' => 30]);
```

### setAutoFilter()

```php
public function setAutoFilter(bool $enable = true): self
```

Otomatik filtre ekler.

### download()

```php
public function download(): void
```

Dosyayı tarayıcıya indirir.

### save()

```php
public function save(string $path): string
```

Dosyayı belirtilen dizine kaydeder.

**Döner:** string - Kaydedilen dosyanın tam yolu

### toString()

```php
public function toString(): string
```

İçeriği string olarak döndürür (email ekleri için).

### getSpreadsheet()

```php
public function getSpreadsheet(): Spreadsheet
```

PhpSpreadsheet nesnesine direkt erişim (ileri düzey kullanım).

---

## WordDriver

Word belgeleri oluşturma.

### addTitle()

```php
public function addTitle(string $text, int $level = 1, array $style = []): self
```

Başlık ekler.

**Parametreler:**
- `$text` (string): Başlık metni
- `$level` (int): Başlık seviyesi (1-6)
- `$style` (array): Özel stil

### addText()

```php
public function addText(string $text, array $style = []): self
```

Metin ekler.

### addTextBreak()

```php
public function addTextBreak(int $count = 1): self
```

Paragraf sonu ekler.

### addTable()

```php
public function addTable(array $data, array $style = []): self
```

Tablo ekler.

**Parametreler:**
- `$data` (array): 2 boyutlu array
- `$style` (array): Tablo stili

### addListItems()

```php
public function addListItems(array $items, int $depth = 0): self
```

Liste ekler.

**Parametreler:**
- `$items` (array): Liste elemanları
- `$depth` (int): Girinti seviyesi

### addImage()

```php
public function addImage(string $path, array $style = []): self
```

Resim ekler.

**Parametreler:**
- `$path` (string): Resim dosya yolu
- `$style` (array): Resim stili

**Throws:** WordException - Dosya bulunamaz ise

**Örnek:**
```php
$word->addImage('image.jpg', [
    'width' => 200,
    'height' => 200,
    'alignment' => Jc::CENTER
]);
```

### addPageBreak()

```php
public function addPageBreak(): self
```

Sayfa sonu ekler.

### addSection()

```php
public function addSection(): self
```

Yeni sayfa (section) ekler.

### getPhpWord()

```php
public function getPhpWord(): PhpWord
```

PhpWord nesnesine direkt erişim.

### getSection()

```php
public function getSection(): Section
```

Aktif section'a erişim.

---

## PdfDriver

PDF belgeleri oluşturma.

### setEngine()

```php
public function setEngine(string $engine): self
```

PDF motorunu belirler.

**Parametreler:**
- `$engine` (string): mpdf veya tcpdf

**Throws:** PdfException - Geçersiz motor

### setContent()

```php
public function setContent(string $content): self
```

HTML içeriği ayarlar.

### setOrientation()

```php
public function setOrientation(string $orientation): self
```

Sayfa yönlendirmesi.

**Parametreler:**
- `$orientation` (string): portrait veya landscape

**Throws:** PdfException - Geçersiz yönlendirme

### setPageSize()

```php
public function setPageSize(string $size): self
```

Sayfa boyutu (A4, A3, Letter, vb.).

### setMargins()

```php
public function setMargins(int $left, int $right, int $top, int $bottom): self
```

Kenar boşlukları (mm).

### setHeader()

```php
public function setHeader(string $header): self
```

Başlık metni.

### setFooter()

```php
public function setFooter(string $footer): self
```

Altbilgi metni. `{PAGENO}` ve `{nbpg}` değişkenleri kullanılabilir.

### setWatermark()

```php
public function setWatermark(string $watermark): self
```

Filigran metni.

### setFont()

```php
public function setFont(string $font, int $size = 11): self
```

Font ayarları.

---

## HtmlDriver

HTML dosyaları oluşturma.

### setTitle()

```php
public function setTitle(string $title): self
```

Sayfa başlığı.

### setTemplate()

```php
public function setTemplate(string $template): self
```

Özel şablon seçimi.

**Parametreler:**
- `$template` (string): Şablon adı (uzantısız)

Şablon dosyası: `app/Views/saver/templates/{template}.php`

### addCustomCss()

```php
public function addCustomCss(string $css): self
```

Özel CSS ekler.

### addCustomJs()

```php
public function addCustomJs(string $js): self
```

Özel JavaScript ekler.

### setCharset()

```php
public function setCharset(string $charset): self
```

Karakter kodlaması (varsayılan: UTF-8).

---

## CsvDriver

CSV dosyaları oluşturma ve okuma.

### setDelimiter()

```php
public function setDelimiter(string $delimiter): self
```

Ayırıcı karakter (varsayılan: ,).

### setEnclosure()

```php
public function setEnclosure(string $enclosure): self
```

Çevreleyen karakter (varsayılan: ").

### setEscape()

```php
public function setEscape(string $escape): self
```

Kaçış karakteri (varsayılan: \).

### setEncoding()

```php
public function setEncoding(string $encoding): self
```

Karakter kodlaması (varsayılan: UTF-8).

### setBom()

```php
public function setBom(bool $bom): self
```

BOM (Byte Order Mark) ekleme (Excel için UTF-8 desteği).

### read()

```php
public function read(string $filePath, bool $hasHeader = true): array
```

CSV dosyasını okur.

**Parametreler:**
- `$filePath` (string): Dosya yolu
- `$hasHeader` (bool): İlk satır başlık mı?

**Döner:** array

**Throws:** SaverException - Dosya bulunamazsa

---

## Helper Functions

### saver()

```php
function saver(): Saver
```

Saver instance'ı döndürür.

### export_excel()

```php
function export_excel(array $data, string $fileName, string $format = 'Xlsx'): void
```

Hızlı Excel export.

### export_csv()

```php
function export_csv(array $data, string $fileName): void
```

Hızlı CSV export.

### export_pdf()

```php
function export_pdf(string $html, string $fileName, string $engine = 'mpdf'): void
```

Hızlı PDF export.

### export_html()

```php
function export_html(array $data, string $fileName, string $title = 'Document'): void
```

Hızlı HTML export.

### export_word()

```php
function export_word(array $data, string $fileName): void
```

Hızlı Word export.

---

## Exceptions

### SaverException

Ana exception sınıfı.

**Static Methods:**

```php
SaverException::forInvalidDriver(string $driver)
SaverException::forEmptyData()
SaverException::forInvalidFileName()
SaverException::forDirectoryNotWritable(string $path)
SaverException::forFileNotSaved(string $path)
SaverException::forInvalidTemplate(string $template)
SaverException::forInvalidFormat(string $format)
SaverException::forMissingExtension(string $extension)
```

### ExcelException

Excel işlemleri için exception.

```php
ExcelException::forInvalidSheetName(string $name)
ExcelException::forInvalidCellReference(string $cell)
ExcelException::forInvalidColumnWidth(string $column)
```

### PdfException

PDF işlemleri için exception.

```php
PdfException::forInvalidEngine(string $engine)
PdfException::forInvalidOrientation(string $orientation)
PdfException::forInvalidPageSize(string $size)
PdfException::forEmptyContent()
```

### WordException

Word işlemleri için exception.

```php
WordException::forInvalidImagePath(string $path)
WordException::forInvalidTableStructure()
WordException::forEmptyContent()
```

---

## Method Chaining

Tüm driver'lar method chaining destekler:

```php
$saver->excel()
    ->setData($data)
    ->setFileName('report.xlsx')
    ->setSheetTitle('Rapor')
    ->setAutoFilter(true)
    ->setHeaderStyle($style)
    ->download();
```

## Type Hints

Tüm methodlar PHP 8.1+ type hints kullanır:

```php
public function setData(array $data): self
public function download(): void
public function save(string $path): string
```

## Return Values

- **self**: Method chaining için
- **void**: İşlem tamamlanır, değer dönmez
- **string**: Dosya yolu veya içerik
- **array**: Veri collection
- **object**: Driver veya utility nesnesi

