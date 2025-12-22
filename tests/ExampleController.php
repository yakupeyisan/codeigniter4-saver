<?php

/**
 * Example Controller
 * 
 * Bu dosya test amaçlıdır. Gerçek uygulamanızda app/Controllers dizinine kopyalayın.
 */

namespace App\Controllers;

use CodeIgniter\Controller;
use Yakupeyisan\CodeIgniter4Saver\Saver;

class ExportController extends Controller
{
    /**
     * Test verileri
     */
    private function getSampleData(): array
    {
        return [
            ['ID', 'Ad', 'Soyad', 'Email', 'Telefon', 'Şehir', 'Tarih'],
            [1, 'Ahmet', 'Yılmaz', 'ahmet@example.com', '555-0001', 'İstanbul', date('Y-m-d')],
            [2, 'Mehmet', 'Kaya', 'mehmet@example.com', '555-0002', 'Ankara', date('Y-m-d')],
            [3, 'Ayşe', 'Demir', 'ayse@example.com', '555-0003', 'İzmir', date('Y-m-d')],
            [4, 'Fatma', 'Çelik', 'fatma@example.com', '555-0004', 'Bursa', date('Y-m-d')],
            [5, 'Ali', 'Şahin', 'ali@example.com', '555-0005', 'Antalya', date('Y-m-d')],
        ];
    }

    /**
     * Ana sayfa - Format seçimi
     */
    public function index()
    {
        $html = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <title>Saver Test</title>
            <style>
                body { font-family: Arial; padding: 50px; background: #f5f5f5; }
                .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
                h1 { color: #333; border-bottom: 3px solid #4CAF50; padding-bottom: 10px; }
                .btn { display: inline-block; padding: 15px 30px; margin: 10px; background: #4CAF50; color: white; text-decoration: none; border-radius: 5px; transition: 0.3s; }
                .btn:hover { background: #45a049; transform: scale(1.05); }
                .btn.excel { background: #217346; }
                .btn.word { background: #2b579a; }
                .btn.pdf { background: #d32f2f; }
                .btn.html { background: #ff6f00; }
                .btn.csv { background: #00897b; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>🎯 CodeIgniter 4 Saver - Test</h1>
                <p>Aşağıdaki butonlara tıklayarak farklı formatlarda dosya indirebilirsiniz:</p>
                <div style="margin-top: 30px;">
                    <a href="/export/excel" class="btn excel">📊 Excel İndir (.xlsx)</a>
                    <a href="/export/word" class="btn word">📄 Word İndir (.docx)</a>
                    <a href="/export/pdf" class="btn pdf">📕 PDF İndir (.pdf)</a>
                    <a href="/export/html" class="btn html">🌐 HTML İndir (.html)</a>
                    <a href="/export/csv" class="btn csv">📈 CSV İndir (.csv)</a>
                </div>
                <hr style="margin: 30px 0;">
                <h2>İleri Düzey Örnekler:</h2>
                <a href="/export/excel-styled" class="btn excel">Excel (Stilli)</a>
                <a href="/export/word-advanced" class="btn word">Word (İleri Düzey)</a>
                <a href="/export/pdf-custom" class="btn pdf">PDF (Özel)</a>
            </div>
        </body>
        </html>
        HTML;

        return $html;
    }

    /**
     * Excel export
     */
    public function excel()
    {
        $saver = new Saver();
        $saver->excel()
            ->setData($this->getSampleData())
            ->setFileName('kullanicilar.xlsx')
            ->setSheetTitle('Kullanıcılar')
            ->setAutoFilter(true)
            ->download();
    }

    /**
     * Excel export (Stilli)
     */
    public function excelStyled()
    {
        $saver = new Saver();
        $saver->excel()
            ->setData($this->getSampleData())
            ->setFileName('kullanicilar_stilli.xlsx')
            ->setSheetTitle('Kullanıcılar')
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
                ],
            ])
            ->setColumnWidths([
                'A' => 10,
                'B' => 15,
                'C' => 15,
                'D' => 30,
                'E' => 15,
                'F' => 15,
                'G' => 15,
            ])
            ->setAutoFilter(true)
            ->download();
    }

    /**
     * Word export
     */
    public function word()
    {
        $saver = new Saver();
        $saver->word()
            ->addTitle('Kullanıcı Listesi', 1)
            ->addTextBreak()
            ->addText('Rapor Tarihi: ' . date('d.m.Y H:i:s'))
            ->addTextBreak(2)
            ->addTable($this->getSampleData())
            ->setFileName('kullanicilar.docx')
            ->download();
    }

    /**
     * Word export (İleri Düzey)
     */
    public function wordAdvanced()
    {
        $saver = new Saver();
        $saver->word()
            ->addTitle('Detaylı Kullanıcı Raporu', 1)
            ->addTextBreak()
            ->addText('Rapor Tarihi: ' . date('d.m.Y H:i:s'), ['size' => 10, 'italic' => true])
            ->addTextBreak(2)
            ->addTitle('Özet', 2)
            ->addListItems([
                'Toplam Kullanıcı: 5',
                'Aktif Kullanıcı: 5',
                'Son Güncelleme: ' . date('d.m.Y'),
            ])
            ->addTextBreak(2)
            ->addTitle('Kullanıcı Listesi', 2)
            ->addTable($this->getSampleData())
            ->addPageBreak()
            ->addTitle('Ek Bilgiler', 1)
            ->addText('Bu rapor otomatik olarak oluşturulmuştur.')
            ->setFileName('kullanicilar_detayli.docx')
            ->download();
    }

    /**
     * PDF export
     */
    public function pdf()
    {
        $saver = new Saver();
        $saver->pdf()
            ->setData($this->getSampleData())
            ->setFileName('kullanicilar.pdf')
            ->download();
    }

    /**
     * PDF export (Özel)
     */
    public function pdfCustom()
    {
        $html = '<h1 style="text-align:center; color:#4CAF50;">Kullanıcı Raporu</h1>';
        $html .= '<p>Rapor Tarihi: ' . date('d.m.Y H:i:s') . '</p>';
        $html .= '<hr>';
        
        // Tabloyu manuel oluştur
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">';
        $data = $this->getSampleData();
        
        foreach ($data as $index => $row) {
            $html .= '<tr>';
            $tag = $index === 0 ? 'th' : 'td';
            $style = $index === 0 ? ' style="background-color:#4CAF50; color:white;"' : '';
            
            foreach ($row as $cell) {
                $html .= "<{$tag}{$style}>{$cell}</{$tag}>";
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';

        $saver = new Saver();
        $saver->pdf()
            ->setContent($html)
            ->setHeader('Şirket Adı')
            ->setFooter('Sayfa {PAGENO}')
            ->setFileName('kullanicilar_ozel.pdf')
            ->download();
    }

    /**
     * HTML export
     */
    public function html()
    {
        $saver = new Saver();
        $saver->html()
            ->setData($this->getSampleData())
            ->setTitle('Kullanıcı Listesi')
            ->setFileName('kullanicilar.html')
            ->download();
    }

    /**
     * CSV export
     */
    public function csv()
    {
        $saver = new Saver();
        $saver->csv()
            ->setData($this->getSampleData())
            ->setBom(true) // Excel için UTF-8 desteği
            ->setFileName('kullanicilar.csv')
            ->download();
    }

    /**
     * Helper kullanımı örneği
     */
    public function helperExample()
    {
        helper('saver');
        
        // Hızlı export
        export_excel($this->getSampleData(), 'quick_export.xlsx');
    }

    /**
     * Kaydetme örneği
     */
    public function saveExample()
    {
        $saver = new Saver();
        
        $excelPath = $saver->excel()
            ->setData($this->getSampleData())
            ->setFileName('backup_' . date('Y-m-d') . '.xlsx')
            ->save(WRITEPATH . 'exports/');

        return $this->response->setJSON([
            'success' => true,
            'file_path' => $excelPath,
            'message' => 'Dosya başarıyla kaydedildi!'
        ]);
    }
}

