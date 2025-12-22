<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use Mpdf\Mpdf;
use TCPDF;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\PdfException;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class PdfDriver extends BaseDriver
{
    /**
     * PDF motoru (mpdf veya tcpdf)
     *
     * @var string
     */
    protected string $engine = 'mpdf';

    /**
     * PDF içeriği
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Sayfa yönlendirmesi (portrait/landscape)
     *
     * @var string
     */
    protected string $orientation = 'portrait';

    /**
     * Sayfa boyutu
     *
     * @var string
     */
    protected string $pageSize = 'A4';

    /**
     * Kenar boşlukları [left, right, top, bottom]
     *
     * @var array
     */
    protected array $margins = [15, 15, 15, 15];

    /**
     * Başlık metni
     *
     * @var string
     */
    protected string $header = '';

    /**
     * Altbilgi metni
     *
     * @var string
     */
    protected string $footer = '';

    /**
     * Filigran metni
     *
     * @var string
     */
    protected string $watermark = '';

    /**
     * Font
     *
     * @var string
     */
    protected string $font = 'dejavusans';

    /**
     * Font boyutu
     *
     * @var int
     */
    protected int $fontSize = 11;

    /**
     * Düzenleme şifresi
     *
     * @var string
     */
    protected string $password = '';

    /**
     * Kullanıcı şifresi (görüntüleme için)
     *
     * @var string
     */
    protected string $userPassword = '';

    /**
     * Sadece okuma modu (düzenleme yasak)
     *
     * @var bool
     */
    protected bool $readOnly = false;

    /**
     * PDF izinleri
     *
     * @var array
     */
    protected array $permissions = [
        'print' => false,      // Yazdırma
        'modify' => false,     // Düzenleme
        'copy' => false,       // Kopyalama
        'annot-forms' => false, // Form doldurma
    ];

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
        $this->engine = $this->config->pdf['engine'];
        $this->orientation = $this->config->pdf['default_orientation'];
        $this->pageSize = $this->config->pdf['default_page_size'];
        $this->font = $this->config->pdf['default_font'];
        $this->fontSize = $this->config->pdf['default_font_size'];
        $this->margins = [
            $this->config->pdf['margin_left'],
            $this->config->pdf['margin_right'],
            $this->config->pdf['margin_top'],
            $this->config->pdf['margin_bottom'],
        ];
    }

    /**
     * PDF motorunu belirler
     *
     * @param string $engine mpdf veya tcpdf
     * @return self
     */
    public function setEngine(string $engine): self
    {
        if (!in_array($engine, ['mpdf', 'tcpdf'])) {
            throw PdfException::forInvalidEngine($engine);
        }

        $this->engine = $engine;
        return $this;
    }

    /**
     * İçerik belirler
     *
     * @param string $content HTML içerik
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Sayfa yönlendirmesini belirler
     *
     * @param string $orientation portrait veya landscape
     * @return self
     */
    public function setOrientation(string $orientation): self
    {
        if (!in_array($orientation, ['portrait', 'landscape'])) {
            throw PdfException::forInvalidOrientation($orientation);
        }

        $this->orientation = $orientation;
        return $this;
    }

    /**
     * Sayfa boyutunu belirler
     *
     * @param string $size A4, A3, Letter, vb.
     * @return self
     */
    public function setPageSize(string $size): self
    {
        $this->pageSize = $size;
        return $this;
    }

    /**
     * Kenar boşluklarını belirler
     *
     * @param int $left
     * @param int $right
     * @param int $top
     * @param int $bottom
     * @return self
     */
    public function setMargins(int $left, int $right, int $top, int $bottom): self
    {
        $this->margins = [$left, $right, $top, $bottom];
        return $this;
    }

    /**
     * Başlık belirler
     *
     * @param string $header
     * @return self
     */
    public function setHeader(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Altbilgi belirler
     *
     * @param string $footer
     * @return self
     */
    public function setFooter(string $footer): self
    {
        $this->footer = $footer;
        return $this;
    }

    /**
     * Filigran belirler
     *
     * @param string $watermark
     * @return self
     */
    public function setWatermark(string $watermark): self
    {
        $this->watermark = $watermark;
        return $this;
    }

    /**
     * Font belirler
     *
     * @param string $font
     * @param int $size
     * @return self
     */
    public function setFont(string $font, int $size = 11): self
    {
        $this->font = $font;
        $this->fontSize = $size;
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
     * Kullanıcı şifresi belirler (görüntüleme için)
     *
     * @param string $password
     * @return self
     */
    public function setUserPassword(string $password): self
    {
        $this->userPassword = $password;
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
        if ($readOnly) {
            // Read-only ise tüm düzenleme izinlerini kapat
            $this->permissions['modify'] = false;
            $this->permissions['copy'] = false;
            $this->permissions['annot-forms'] = false;
        }
        return $this;
    }

    /**
     * PDF izinlerini belirler
     *
     * @param array $permissions
     * @return self
     */
    public function setPermissions(array $permissions): self
    {
        $this->permissions = array_merge($this->permissions, $permissions);
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
            throw PdfException::forInvalidImagePath($imagePath);
        }
        $this->watermarkImage = $imagePath;
        return $this;
    }

    /**
     * Veriyi HTML tablosuna çevirir
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self
    {
        parent::setData($data);
        $this->content = $this->arrayToHtmlTable($data);
        return $this;
    }

    /**
     * Array'i HTML tablosuna çevirir
     *
     * @param array $data
     * @return string
     */
    protected function arrayToHtmlTable(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        $html = '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">';
        
        foreach ($data as $index => $row) {
            $html .= '<tr>';
            $tag = $index === 0 ? 'th' : 'td';
            $style = $index === 0 ? ' style="background-color: #E0E0E0; font-weight: bold;"' : '';
            
            foreach ($row as $cell) {
                $html .= "<{$tag}{$style}>" . htmlspecialchars((string) $cell) . "</{$tag}>";
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        return $html;
    }

    /**
     * {@inheritDoc}
     */
    public function download(): void
    {
        if (empty($this->content)) {
            throw PdfException::forEmptyContent();
        }

        $fileName = $this->getFileName('.pdf');

        if ($this->engine === 'mpdf') {
            $this->downloadWithMpdf($fileName);
        } else {
            $this->downloadWithTcpdf($fileName);
        }
    }

    /**
     * mPDF ile indir
     *
     * @param string $fileName
     * @return void
     */
    protected function downloadWithMpdf(string $fileName): void
    {
        $config = [
            'mode' => 'utf-8',
            'format' => $this->pageSize,
            'orientation' => substr($this->orientation, 0, 1), // P veya L
            'margin_left' => $this->margins[0],
            'margin_right' => $this->margins[1],
            'margin_top' => $this->margins[2],
            'margin_bottom' => $this->margins[3],
        ];

        $mpdf = new Mpdf($config);
        
        if (!empty($this->header)) {
            $mpdf->SetHeader($this->header);
        }
        
        if (!empty($this->footer)) {
            $mpdf->SetFooter($this->footer);
        }
        
        // Watermark metni
        if (!empty($this->watermark)) {
            $mpdf->SetWatermarkText($this->watermark);
            $mpdf->showWatermarkText = true;
        }

        // Watermark resmi
        if (!empty($this->watermarkImage)) {
            $mpdf->SetWatermarkImage($this->watermarkImage, 0.2, 'P'); // 0.2 opacity
        }

        // Şifre koruması ve izinler
        if (!empty($this->userPassword) || !empty($this->password)) {
            $userPassword = $this->userPassword ?: '';
            $ownerPassword = $this->password ?: '';
            
            // İzinleri hesapla
            $permissions = 0;
            if ($this->permissions['print']) {
                $permissions |= 4; // Print
            }
            if ($this->permissions['modify']) {
                $permissions |= 8; // Modify
            }
            if ($this->permissions['copy']) {
                $permissions |= 16; // Copy
            }
            if ($this->permissions['annot-forms']) {
                $permissions |= 32; // Annot-forms
            }
            
            $mpdf->SetProtection($permissions, $userPassword, $ownerPassword);
        }

        $mpdf->WriteHTML($this->content);
        $mpdf->Output($fileName, 'D');
        exit;
    }

    /**
     * TCPDF ile indir
     *
     * @param string $fileName
     * @return void
     */
    protected function downloadWithTcpdf(string $fileName): void
    {
        $orientation = $this->orientation === 'portrait' ? 'P' : 'L';
        
        $pdf = new TCPDF($orientation, 'mm', $this->pageSize, true, 'UTF-8', false);
        
        $pdf->SetCreator('CodeIgniter 4 Saver');
        $pdf->SetAuthor('CodeIgniter 4 Saver');
        
        if (!empty($this->header)) {
            $pdf->SetHeaderData('', 0, $this->header, '');
        }
        
        $pdf->setHeaderFont([$this->font, '', $this->fontSize]);
        $pdf->setFooterFont([$this->font, '', $this->fontSize - 2]);
        
        $pdf->SetMargins($this->margins[0], $this->margins[2], $this->margins[1]);
        $pdf->SetAutoPageBreak(true, $this->margins[3]);
        
        // Şifre koruması ve izinler
        if (!empty($this->userPassword) || !empty($this->password)) {
            $userPassword = $this->userPassword ?: '';
            $ownerPassword = $this->password ?: '';
            
            // İzinleri hesapla
            $permissions = [];
            if (!$this->permissions['print']) {
                $permissions[] = 'print';
            }
            if (!$this->permissions['modify']) {
                $permissions[] = 'modify';
            }
            if (!$this->permissions['copy']) {
                $permissions[] = 'copy';
            }
            if (!$this->permissions['annot-forms']) {
                $permissions[] = 'annot-forms';
            }
            
            $pdf->setProtection($permissions, $userPassword, $ownerPassword);
        }
        
        $pdf->AddPage();
        $pdf->SetFont($this->font, '', $this->fontSize);
        
        // Watermark metni
        if (!empty($this->watermark)) {
            $pdf->StartTransform();
            $pdf->SetAlpha(0.3);
            $pdf->SetFont($this->font, 'B', 50);
            $pdf->SetTextColor(200, 200, 200);
            $pdf->Rotate(45, 105, 150);
            $pdf->Text(105, 150, $this->watermark);
            $pdf->StopTransform();
            $pdf->SetAlpha(1);
            $pdf->SetTextColor(0, 0, 0);
        }
        
        // Watermark resmi
        if (!empty($this->watermarkImage)) {
            $pdf->Image($this->watermarkImage, 50, 50, 100, 100, '', '', '', false, 300, '', false, false, 0);
        }
        
        $pdf->writeHTML($this->content, true, false, true, false, '');
        
        $pdf->Output($fileName, 'D');
        exit;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $path): string
    {
        if (empty($this->content)) {
            throw PdfException::forEmptyContent();
        }

        $this->ensureDirectoryWritable($path);

        $fileName = $this->getFileName('.pdf');
        $filePath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        if ($this->engine === 'mpdf') {
            $this->saveWithMpdf($filePath);
        } else {
            $this->saveWithTcpdf($filePath);
        }

        if (!file_exists($filePath)) {
            throw SaverException::forFileNotSaved($filePath);
        }

        return $filePath;
    }

    /**
     * mPDF ile kaydet
     *
     * @param string $filePath
     * @return void
     */
    protected function saveWithMpdf(string $filePath): void
    {
        $config = [
            'mode' => 'utf-8',
            'format' => $this->pageSize,
            'orientation' => substr($this->orientation, 0, 1),
            'margin_left' => $this->margins[0],
            'margin_right' => $this->margins[1],
            'margin_top' => $this->margins[2],
            'margin_bottom' => $this->margins[3],
        ];

        $mpdf = new Mpdf($config);
        
        if (!empty($this->header)) {
            $mpdf->SetHeader($this->header);
        }
        
        if (!empty($this->footer)) {
            $mpdf->SetFooter($this->footer);
        }
        
        // Watermark metni
        if (!empty($this->watermark)) {
            $mpdf->SetWatermarkText($this->watermark);
            $mpdf->showWatermarkText = true;
        }

        // Watermark resmi
        if (!empty($this->watermarkImage)) {
            $mpdf->SetWatermarkImage($this->watermarkImage, 0.2, 'P');
        }

        // Şifre koruması ve izinler
        if (!empty($this->userPassword) || !empty($this->password)) {
            $userPassword = $this->userPassword ?: '';
            $ownerPassword = $this->password ?: '';
            
            $permissions = 0;
            if ($this->permissions['print']) {
                $permissions |= 4;
            }
            if ($this->permissions['modify']) {
                $permissions |= 8;
            }
            if ($this->permissions['copy']) {
                $permissions |= 16;
            }
            if ($this->permissions['annot-forms']) {
                $permissions |= 32;
            }
            
            $mpdf->SetProtection($permissions, $userPassword, $ownerPassword);
        }

        $mpdf->WriteHTML($this->content);
        $mpdf->Output($filePath, 'F');
    }

    /**
     * TCPDF ile kaydet
     *
     * @param string $filePath
     * @return void
     */
    protected function saveWithTcpdf(string $filePath): void
    {
        $orientation = $this->orientation === 'portrait' ? 'P' : 'L';
        
        $pdf = new TCPDF($orientation, 'mm', $this->pageSize, true, 'UTF-8', false);
        
        $pdf->SetCreator('CodeIgniter 4 Saver');
        $pdf->SetAuthor('CodeIgniter 4 Saver');
        
        if (!empty($this->header)) {
            $pdf->SetHeaderData('', 0, $this->header, '');
        }
        
        $pdf->setHeaderFont([$this->font, '', $this->fontSize]);
        $pdf->setFooterFont([$this->font, '', $this->fontSize - 2]);
        
        $pdf->SetMargins($this->margins[0], $this->margins[2], $this->margins[1]);
        $pdf->SetAutoPageBreak(true, $this->margins[3]);
        
        // Şifre koruması ve izinler
        if (!empty($this->userPassword) || !empty($this->password)) {
            $userPassword = $this->userPassword ?: '';
            $ownerPassword = $this->password ?: '';
            
            $permissions = [];
            if (!$this->permissions['print']) {
                $permissions[] = 'print';
            }
            if (!$this->permissions['modify']) {
                $permissions[] = 'modify';
            }
            if (!$this->permissions['copy']) {
                $permissions[] = 'copy';
            }
            if (!$this->permissions['annot-forms']) {
                $permissions[] = 'annot-forms';
            }
            
            $pdf->setProtection($permissions, $userPassword, $ownerPassword);
        }
        
        $pdf->AddPage();
        $pdf->SetFont($this->font, '', $this->fontSize);
        
        // Watermark metni
        if (!empty($this->watermark)) {
            $pdf->StartTransform();
            $pdf->SetAlpha(0.3);
            $pdf->SetFont($this->font, 'B', 50);
            $pdf->SetTextColor(200, 200, 200);
            $pdf->Rotate(45, 105, 150);
            $pdf->Text(105, 150, $this->watermark);
            $pdf->StopTransform();
            $pdf->SetAlpha(1);
            $pdf->SetTextColor(0, 0, 0);
        }
        
        // Watermark resmi
        if (!empty($this->watermarkImage)) {
            $pdf->Image($this->watermarkImage, 50, 50, 100, 100, '', '', '', false, 300, '', false, false, 0);
        }
        
        $pdf->writeHTML($this->content, true, false, true, false, '');
        
        $pdf->Output($filePath, 'F');
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        if (empty($this->content)) {
            throw PdfException::forEmptyContent();
        }

        if ($this->engine === 'mpdf') {
            return $this->toStringWithMpdf();
        }
        
        return $this->toStringWithTcpdf();
    }

    /**
     * mPDF ile string döndür
     *
     * @return string
     */
    protected function toStringWithMpdf(): string
    {
        $config = [
            'mode' => 'utf-8',
            'format' => $this->pageSize,
            'orientation' => substr($this->orientation, 0, 1),
        ];

        $mpdf = new Mpdf($config);
        $mpdf->WriteHTML($this->content);
        
        return $mpdf->Output('', 'S');
    }

    /**
     * TCPDF ile string döndür
     *
     * @return string
     */
    protected function toStringWithTcpdf(): string
    {
        $orientation = $this->orientation === 'portrait' ? 'P' : 'L';
        
        $pdf = new TCPDF($orientation, 'mm', $this->pageSize, true, 'UTF-8', false);
        $pdf->AddPage();
        $pdf->SetFont($this->font, '', $this->fontSize);
        $pdf->writeHTML($this->content, true, false, true, false, '');
        
        return $pdf->Output('', 'S');
    }

    /**
     * {@inheritDoc}
     */
    protected function getMimeType(): string
    {
        return 'application/pdf';
    }
}

