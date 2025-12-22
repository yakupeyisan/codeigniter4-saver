<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\ExcelException;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class ExcelDriver extends BaseDriver
{
    /**
     * Spreadsheet nesnesi
     *
     * @var Spreadsheet
     */
    protected Spreadsheet $spreadsheet;

    /**
     * Format (Xlsx, Xls, Csv)
     *
     * @var string
     */
    protected string $format = 'Xlsx';

    /**
     * Başlık stili
     *
     * @var array
     */
    protected array $headerStyle = [];

    /**
     * Kolon genişlikleri
     *
     * @var array
     */
    protected array $columnWidths = [];

    /**
     * Aktif sayfa adı
     *
     * @var string
     */
    protected string $sheetTitle = 'Sheet1';

    /**
     * Otomatik filtre
     *
     * @var bool
     */
    protected bool $autoFilter = false;

    /**
     * Düzenleme şifresi
     *
     * @var string
     */
    protected string $password = '';

    /**
     * Sadece okuma modu (düzenleme yasak)
     *
     * @var bool
     */
    protected bool $readOnly = false;

    /**
     * Arka plan antet/watermark metni
     *
     * @var string
     */
    protected string $watermark = '';

    /**
     * Arka plan antet/watermark resim yolu
     *
     * @var string
     */
    protected string $watermarkImage = '';

    /**
     * Koruma seçenekleri
     *
     * @var array
     */
    protected array $protectionOptions = [
        'sheet' => true,
        'objects' => true,
        'scenarios' => true,
        'formatCells' => false,
        'formatColumns' => false,
        'formatRows' => false,
        'insertColumns' => false,
        'insertRows' => false,
        'insertHyperlinks' => false,
        'deleteColumns' => false,
        'deleteRows' => false,
        'selectLockedCells' => true,
        'sort' => false,
        'autoFilter' => false,
        'pivotTables' => false,
        'selectUnlockedCells' => true,
    ];

    /**
     * Constructor
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->spreadsheet = new Spreadsheet();
        $this->format = $this->config->excel['default_format'];
        $this->setupSpreadsheet();
    }

    /**
     * Spreadsheet ayarlarını yapar
     */
    protected function setupSpreadsheet(): void
    {
        $properties = $this->spreadsheet->getProperties();
        $properties->setCreator($this->config->excel['creator']);
        $properties->setLastModifiedBy($this->config->excel['last_modified_by']);
        
        if (!empty($this->config->excel['title'])) {
            $properties->setTitle($this->config->excel['title']);
        }
        
        if (!empty($this->config->excel['subject'])) {
            $properties->setSubject($this->config->excel['subject']);
        }
        
        if (!empty($this->config->excel['description'])) {
            $properties->setDescription($this->config->excel['description']);
        }
    }

    /**
     * Format belirler
     *
     * @param string $format Xlsx, Xls veya Csv
     * @return self
     */
    public function setFormat(string $format): self
    {
        if (!in_array($format, ['Xlsx', 'Xls', 'Csv'])) {
            throw SaverException::forInvalidFormat($format);
        }

        $this->format = $format;
        return $this;
    }

    /**
     * Sayfa başlığını belirler
     *
     * @param string $title
     * @return self
     */
    public function setSheetTitle(string $title): self
    {
        $this->sheetTitle = $title;
        return $this;
    }

    /**
     * Başlık stilini ayarlar
     *
     * @param array $style
     * @return self
     */
    public function setHeaderStyle(array $style): self
    {
        $this->headerStyle = $style;
        return $this;
    }

    /**
     * Kolon genişliklerini ayarlar
     *
     * @param array $widths ['A' => 20, 'B' => 30]
     * @return self
     */
    public function setColumnWidths(array $widths): self
    {
        $this->columnWidths = $widths;
        return $this;
    }

    /**
     * Otomatik filtre ekler
     *
     * @param bool $enable
     * @return self
     */
    public function setAutoFilter(bool $enable = true): self
    {
        $this->autoFilter = $enable;
        return $this;
    }

    /**
     * Düzenleme için şifre belirler
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->readOnly = true; // Şifre varsa otomatik read-only
        return $this;
    }

    /**
     * Sadece okuma modu (düzenleme yasak)
     *
     * @param bool $readOnly
     * @return self
     */
    public function setReadOnly(bool $readOnly = true): self
    {
        $this->readOnly = $readOnly;
        return $this;
    }

    /**
     * Arka plan antet/watermark metni ekler
     *
     * @param string $text
     * @return self
     */
    public function setWatermark(string $text): self
    {
        $this->watermark = $text;
        return $this;
    }

    /**
     * Arka plan antet/watermark resim ekler
     *
     * @param string $imagePath Resim dosya yolu
     * @return self
     */
    public function setWatermarkImage(string $imagePath): self
    {
        if (!file_exists($imagePath)) {
            throw ExcelException::forInvalidImagePath($imagePath);
        }
        $this->watermarkImage = $imagePath;
        return $this;
    }

    /**
     * Koruma seçeneklerini özelleştirir
     *
     * @param array $options
     * @return self
     */
    public function setProtectionOptions(array $options): self
    {
        $this->protectionOptions = array_merge($this->protectionOptions, $options);
        return $this;
    }

    /**
     * Varsayılan başlık stilini uygular
     *
     * @param string $range
     * @return void
     */
    protected function applyDefaultHeaderStyle(string $range): void
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        $style = $sheet->getStyle($range);

        // Font
        $style->getFont()->setBold(true);

        // Fill
        $style->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');

        // Border
        $style->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Alignment
        $style->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
    }

    /**
     * {@inheritDoc}
     */
    public function download(): void
    {
        $this->buildSpreadsheet();

        $fileName = $this->getFileName('.' . strtolower($this->format));

        header('Content-Type: ' . $this->getMimeType());
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = $this->getWriter();
        $writer->save('php://output');
        exit;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $path): string
    {
        $this->ensureDirectoryWritable($path);
        $this->buildSpreadsheet();

        $fileName = $this->getFileName('.' . strtolower($this->format));
        $filePath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        $writer = $this->getWriter();
        $writer->save($filePath);

        if (!file_exists($filePath)) {
            throw SaverException::forFileNotSaved($filePath);
        }

        return $filePath;
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        $this->buildSpreadsheet();

        ob_start();
        $writer = $this->getWriter();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Spreadsheet'i oluşturur
     *
     * @return void
     */
    protected function buildSpreadsheet(): void
    {
        if (empty($this->data)) {
            throw SaverException::forEmptyData();
        }

        $sheet = $this->spreadsheet->getActiveSheet();
        $sheet->setTitle($this->sheetTitle);

        // Veriyi yaz
        $sheet->fromArray($this->data, null, 'A1');

        // İlk satırı başlık olarak kabul et
        if (count($this->data) > 0) {
            $highestColumn = $sheet->getHighestColumn();
            $headerRange = 'A1:' . $highestColumn . '1';

            // Başlık stili uygula
            if (!empty($this->headerStyle)) {
                $sheet->getStyle($headerRange)->applyFromArray($this->headerStyle);
            } else {
                $this->applyDefaultHeaderStyle($headerRange);
            }

            // Otomatik filtre
            if ($this->autoFilter) {
                $sheet->setAutoFilter($headerRange);
            }
        }

        // Kolon genişlikleri
        if (!empty($this->columnWidths)) {
            foreach ($this->columnWidths as $column => $width) {
                $sheet->getColumnDimension($column)->setWidth($width);
            }
        } else {
            // Otomatik kolon genişliği
            foreach (range('A', $sheet->getHighestColumn()) as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        // Arka plan antet/watermark ekle
        $this->applyWatermark($sheet);

        // Koruma ayarlarını uygula
        if ($this->readOnly || !empty($this->password)) {
            $this->applyProtection($sheet);
        }
    }

    /**
     * Arka plan antet/watermark uygular
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return void
     */
    protected function applyWatermark($sheet): void
    {
        // Metin watermark (Header/Footer olarak)
        if (!empty($this->watermark)) {
            $headerFooter = $sheet->getHeaderFooter();
            // Gri renkli, ortalanmış, italik watermark
            $watermarkText = '&C&"Arial,Italic"&K808080' . $this->watermark;
            $headerFooter->setOddHeader($watermarkText);
            $headerFooter->setEvenHeader($watermarkText);
            $headerFooter->setOddFooter($watermarkText);
            $headerFooter->setEvenFooter($watermarkText);
        }

        // Resim watermark (Background image olarak)
        if (!empty($this->watermarkImage)) {
            try {
                $objDrawing = new Drawing();
                $objDrawing->setPath($this->watermarkImage);
                $objDrawing->setName('Watermark');
                $objDrawing->setDescription('Watermark');
                $objDrawing->setCoordinates('A1');
                $objDrawing->setOffsetX(0);
                $objDrawing->setOffsetY(0);
                $objDrawing->setResizeProportional(false);
                
                // Sayfa boyutuna göre ayarla
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                // Son hücreye kadar genişlet
                $objDrawing->setWidth(1000);
                $objDrawing->setHeight(800);
                $objDrawing->setWorksheet($sheet);
                
                // Z-index'i en alta al (background)
                $objDrawing->setShadow([
                    'visible' => true,
                    'alpha' => 0.3,
                ]);
            } catch (\Exception $e) {
                // Resim yüklenemezse sadece logla, hata fırlatma
                //log_message('warning', 'Watermark image could not be loaded: ' . $e->getMessage());
            }
        }
    }

    /**
     * Koruma ayarlarını uygular
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @return void
     */
    protected function applyProtection($sheet): void
    {
        $protection = $sheet->getProtection();
        
        // Koruma seçeneklerini uygula
        $protection->setSheet($this->protectionOptions['sheet']);
        $protection->setObjects($this->protectionOptions['objects']);
        $protection->setScenarios($this->protectionOptions['scenarios']);
        $protection->setFormatCells($this->protectionOptions['formatCells']);
        $protection->setFormatColumns($this->protectionOptions['formatColumns']);
        $protection->setFormatRows($this->protectionOptions['formatRows']);
        $protection->setInsertColumns($this->protectionOptions['insertColumns']);
        $protection->setInsertRows($this->protectionOptions['insertRows']);
        $protection->setInsertHyperlinks($this->protectionOptions['insertHyperlinks']);
        $protection->setDeleteColumns($this->protectionOptions['deleteColumns']);
        $protection->setDeleteRows($this->protectionOptions['deleteRows']);
        $protection->setSelectLockedCells($this->protectionOptions['selectLockedCells']);
        $protection->setSort($this->protectionOptions['sort']);
        $protection->setAutoFilter($this->protectionOptions['autoFilter']);
        $protection->setPivotTables($this->protectionOptions['pivotTables']);
        $protection->setSelectUnlockedCells($this->protectionOptions['selectUnlockedCells']);

        // Şifre ayarla
        if (!empty($this->password)) {
            $protection->setPassword($this->password);
        }

        // Koruma etkinleştir
        $sheet->getProtection()->setSheet(true);
    }

    /**
     * Writer nesnesini döndürür
     *
     * @return Xlsx|Xls|Csv
     */
    protected function getWriter()
    {
        return match ($this->format) {
            'Xlsx' => new Xlsx($this->spreadsheet),
            'Xls' => new Xls($this->spreadsheet),
            'Csv' => new Csv($this->spreadsheet),
            default => new Xlsx($this->spreadsheet),
        };
    }

    /**
     * {@inheritDoc}
     */
    protected function getMimeType(): string
    {
        return match ($this->format) {
            'Xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Xls' => 'application/vnd.ms-excel',
            'Csv' => 'text/csv',
            default => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        };
    }

    /**
     * Spreadsheet nesnesini döndürür (ileri düzey kullanım için)
     *
     * @return Spreadsheet
     */
    public function getSpreadsheet(): Spreadsheet
    {
        return $this->spreadsheet;
    }
}

