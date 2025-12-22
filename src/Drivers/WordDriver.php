<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\SimpleType\Jc;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\WordException;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class WordDriver extends BaseDriver
{
    /**
     * PhpWord nesnesi
     *
     * @var PhpWord
     */
    protected PhpWord $phpWord;

    /**
     * Section nesnesi
     *
     * @var Section
     */
    protected Section $section;

    /**
     * Varsayılan font
     *
     * @var string
     */
    protected string $defaultFont = 'Arial';

    /**
     * Varsayılan font boyutu
     *
     * @var int
     */
    protected int $defaultFontSize = 11;

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
     * Constructor
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->phpWord = new PhpWord();
        $this->defaultFont = $this->config->word['default_font'];
        $this->defaultFontSize = $this->config->word['default_font_size'];
        $this->setupDocument();
        $this->section = $this->phpWord->addSection();
    }

    /**
     * Döküman ayarlarını yapar
     */
    protected function setupDocument(): void
    {
        $properties = $this->phpWord->getDocInfo();
        $properties->setCreator($this->config->word['creator']);
        
        if (!empty($this->config->word['title'])) {
            $properties->setTitle($this->config->word['title']);
        }
        
        if (!empty($this->config->word['description'])) {
            $properties->setDescription($this->config->word['description']);
        }
        
        if (!empty($this->config->word['subject'])) {
            $properties->setSubject($this->config->word['subject']);
        }
    }

    /**
     * Başlık ekler
     *
     * @param string $text
     * @param int $level 1-6 arası
     * @param array $style
     * @return self
     */
    public function addTitle(string $text, int $level = 1, array $style = []): self
    {
        $defaultStyle = [
            'size' => 16 - ($level * 2),
            'bold' => true,
            'name' => $this->defaultFont,
        ];

        $style = array_merge($defaultStyle, $style);
        $this->section->addTitle($text, $level);
        
        return $this;
    }

    /**
     * Metin ekler
     *
     * @param string $text
     * @param array $style
     * @return self
     */
    public function addText(string $text, array $style = []): self
    {
        $defaultStyle = [
            'size' => $this->defaultFontSize,
            'name' => $this->defaultFont,
        ];

        $style = array_merge($defaultStyle, $style);
        $this->section->addText($text, $style);
        
        return $this;
    }

    /**
     * Paragraf sonu ekler
     *
     * @return self
     */
    public function addTextBreak(int $count = 1): self
    {
        $this->section->addTextBreak($count);
        return $this;
    }

    /**
     * Tablo ekler
     *
     * @param array $data
     * @param array $style
     * @return self
     */
    public function addTable(array $data, array $style = []): self
    {
        if (empty($data)) {
            return $this;
        }

        // Tablo oluştur
        $tableStyle = array_merge([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ], $style);

        $table = $this->section->addTable($tableStyle);

        // Satırları ekle
        foreach ($data as $rowIndex => $row) {
            $table->addRow();
            foreach ($row as $cell) {
                $cellStyle = [];
                
                // İlk satır için başlık stili
                if ($rowIndex === 0) {
                    $cellStyle = [
                        'bgColor' => 'E0E0E0',
                        'valign' => 'center',
                    ];
                }

                $table->addCell(2000, $cellStyle)->addText(
                    (string) $cell,
                    ['bold' => $rowIndex === 0]
                );
            }
        }

        return $this;
    }

    /**
     * Liste ekler
     *
     * @param array $items
     * @param int $depth Girinti seviyesi
     * @return self
     */
    public function addListItems(array $items, int $depth = 0): self
    {
        foreach ($items as $item) {
            $this->section->addListItem($item, $depth);
        }
        
        return $this;
    }

    /**
     * Resim ekler
     *
     * @param string $path
     * @param array $style
     * @return self
     */
    public function addImage(string $path, array $style = []): self
    {
        if (!file_exists($path)) {
            throw WordException::forInvalidImagePath($path);
        }

        $defaultStyle = [
            'width' => 200,
            'height' => 200,
            'alignment' => Jc::CENTER,
        ];

        $style = array_merge($defaultStyle, $style);
        $this->section->addImage($path, $style);
        
        return $this;
    }

    /**
     * Sayfa sonu ekler
     *
     * @return self
     */
    public function addPageBreak(): self
    {
        $this->section->addPageBreak();
        return $this;
    }

    /**
     * Yeni sayfa ekler
     *
     * @return self
     */
    public function addSection(): self
    {
        $this->section = $this->phpWord->addSection();
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
        $this->readOnly = true;
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
            throw WordException::forInvalidImagePath($imagePath);
        }
        $this->watermarkImage = $imagePath;
        return $this;
    }

    /**
     * Veriyi tablo olarak ekler
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        parent::setData($data);
        $this->addTable($data);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function download(): void
    {
        // Watermark ve koruma ayarlarını uygula
        $this->applyWatermark();
        $this->applyProtection();

        $fileName = $this->getFileName('.docx');

        header('Content-Type: ' . $this->getMimeType());
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
        
        // Şifre koruması varsa uygula
        if (!empty($this->password)) {
            $writer->setPassword($this->password);
        }
        
        $writer->save('php://output');
        exit;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $path): string
    {
        $this->ensureDirectoryWritable($path);

        // Watermark ve koruma ayarlarını uygula
        $this->applyWatermark();
        $this->applyProtection();

        $fileName = $this->getFileName('.docx');
        $filePath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
        
        // Şifre koruması varsa uygula
        if (!empty($this->password)) {
            $writer->setPassword($this->password);
        }
        
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
        // Watermark ve koruma ayarlarını uygula
        $this->applyWatermark();
        $this->applyProtection();

        ob_start();
        $writer = IOFactory::createWriter($this->phpWord, 'Word2007');
        
        // Şifre koruması varsa uygula
        if (!empty($this->password)) {
            $writer->setPassword($this->password);
        }
        
        $writer->save('php://output');
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Arka plan antet/watermark uygular
     *
     * @return void
     */
    protected function applyWatermark(): void
    {
        // Her section'a watermark ekle
        foreach ($this->phpWord->getSections() as $section) {
            // Metin watermark
            if (!empty($this->watermark)) {
                try {
                    // PhpWord'de watermark için header kullanılır
                    $header = $section->addHeader();
                    $header->addText($this->watermark, [
                        'size' => 72,
                        'color' => '808080',
                        'italic' => true,
                    ], [
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                        'spaceAfter' => 0,
                    ]);
                } catch (\Exception $e) {
                    // Eski sürümlerde desteklenmeyebilir
                    //log_message('warning', 'Word watermark text not available: ' . $e->getMessage());
                }
            }

            // Resim watermark
            if (!empty($this->watermarkImage)) {
                try {
                    $header = $section->addHeader();
                    $header->addImage($this->watermarkImage, [
                        'width' => 500,
                        'height' => 500,
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    ]);
                } catch (\Exception $e) {
                    //log_message('warning', 'Word watermark image not available: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Koruma ayarlarını uygular
     *
     * @return void
     */
    protected function applyProtection(): void
    {
        if ($this->readOnly || !empty($this->password)) {
            $settings = $this->phpWord->getSettings();
            
            // Read-only modu
            if ($this->readOnly) {
                // PhpWord'de read-only için document protection kullanılır
                // Bu özellik PhpWord'ün sürümüne bağlı olarak değişebilir
                try {
                    $settings->setDocumentProtection('readOnly');
                } catch (\Exception $e) {
                    // Eski sürümlerde desteklenmeyebilir
                    //log_message('warning', 'Word read-only protection not available: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    }

    /**
     * PhpWord nesnesini döndürür (ileri düzey kullanım için)
     *
     * @return PhpWord
     */
    public function getPhpWord(): PhpWord
    {
        return $this->phpWord;
    }

    /**
     * Aktif section'ı döndürür
     *
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }
}

