<?php

namespace Yakupeyisan\CodeIgniter4Saver;

use Yakupeyisan\CodeIgniter4Saver\Config\Saver as SaverConfig;
use Yakupeyisan\CodeIgniter4Saver\Contracts\SaverInterface;
use Yakupeyisan\CodeIgniter4Saver\Drivers\ExcelDriver;
use Yakupeyisan\CodeIgniter4Saver\Drivers\WordDriver;
use Yakupeyisan\CodeIgniter4Saver\Drivers\PdfDriver;
use Yakupeyisan\CodeIgniter4Saver\Drivers\HtmlDriver;
use Yakupeyisan\CodeIgniter4Saver\Drivers\CsvDriver;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class Saver
{
    /**
     * Config
     *
     * @var SaverConfig
     */
    protected SaverConfig $config;

    /**
     * Aktif sürücü
     *
     * @var SaverInterface|null
     */
    protected ?SaverInterface $driver = null;

    /**
     * Constructor
     *
     * @param SaverConfig|null $config
     */
    public function __construct(?SaverConfig $config = null)
    {
        if ($config !== null) {
            $this->config = $config;
        } else {
            $ciConfig = config('Saver');
            // If CodeIgniter returns a bridge config, get the package config from it
            if ($ciConfig instanceof \Config\Saver && method_exists($ciConfig, 'getPackageConfig')) {
                $this->config = $ciConfig::getPackageConfig();
            } elseif ($ciConfig instanceof SaverConfig) {
                $this->config = $ciConfig;
            } else {
                // Fallback: create new package config instance
                $this->config = new SaverConfig();
            }
        }
    }

    /**
     * Excel sürücüsünü döndürür
     *
     * @param string|null $format Xlsx, Xls veya Csv
     * @return ExcelDriver
     */
    public function excel(?string $format = null): ExcelDriver
    {
        $driver = new ExcelDriver($this->config);
        
        if ($format !== null) {
            $driver->setFormat($format);
        }
        
        $this->driver = $driver;
        return $driver;
    }

    /**
     * Word sürücüsünü döndürür
     *
     * @return WordDriver
     */
    public function word(): WordDriver
    {
        $driver = new WordDriver($this->config);
        $this->driver = $driver;
        return $driver;
    }

    /**
     * PDF sürücüsünü döndürür
     *
     * @param string|null $engine mpdf veya tcpdf
     * @return PdfDriver
     */
    public function pdf(?string $engine = null): PdfDriver
    {
        $driver = new PdfDriver($this->config);
        
        if ($engine !== null) {
            $driver->setEngine($engine);
        }
        
        $this->driver = $driver;
        return $driver;
    }

    /**
     * HTML sürücüsünü döndürür
     *
     * @return HtmlDriver
     */
    public function html(): HtmlDriver
    {
        $driver = new HtmlDriver($this->config);
        $this->driver = $driver;
        return $driver;
    }

    /**
     * CSV sürücüsünü döndürür
     *
     * @return CsvDriver
     */
    public function csv(): CsvDriver
    {
        $driver = new CsvDriver($this->config);
        $this->driver = $driver;
        return $driver;
    }

    /**
     * Varsayılan sürücüyü döndürür
     *
     * @return SaverInterface
     */
    public function getDefaultDriver(): SaverInterface
    {
        return match ($this->config->defaultDriver) {
            'excel' => $this->excel(),
            'word' => $this->word(),
            'pdf' => $this->pdf(),
            'html' => $this->html(),
            'csv' => $this->csv(),
            default => throw SaverException::forInvalidDriver($this->config->defaultDriver),
        };
    }

    /**
     * Aktif sürücüyü döndürür
     *
     * @return SaverInterface|null
     */
    public function getDriver(): ?SaverInterface
    {
        return $this->driver;
    }

    /**
     * Hızlı Excel export
     *
     * @param array $data
     * @param string $fileName
     * @param string $format
     * @return void
     */
    public static function exportExcel(array $data, string $fileName, string $format = 'Xlsx'): void
    {
        $saver = new self();
        $saver->excel($format)
            ->setData($data)
            ->setFileName($fileName)
            ->download();
    }

    /**
     * Hızlı CSV export
     *
     * @param array $data
     * @param string $fileName
     * @return void
     */
    public static function exportCsv(array $data, string $fileName): void
    {
        $saver = new self();
        $saver->csv()
            ->setData($data)
            ->setFileName($fileName)
            ->download();
    }

    /**
     * Hızlı PDF export
     *
     * @param string $html
     * @param string $fileName
     * @param string $engine
     * @return void
     */
    public static function exportPdf(string $html, string $fileName, string $engine = 'mpdf'): void
    {
        $saver = new self();
        $saver->pdf($engine)
            ->setContent($html)
            ->setFileName($fileName)
            ->download();
    }

    /**
     * Hızlı HTML export
     *
     * @param array $data
     * @param string $fileName
     * @param string $title
     * @return void
     */
    public static function exportHtml(array $data, string $fileName, string $title = 'Document'): void
    {
        $saver = new self();
        $saver->html()
            ->setData($data)
            ->setFileName($fileName)
            ->setTitle($title)
            ->download();
    }

    /**
     * Model verilerini export eder
     *
     * @param object $model CodeIgniter Model
     * @param string $driver excel, word, pdf, html, csv
     * @param string $fileName
     * @param array $options
     * @return void
     */
    public static function exportFromModel(
        object $model,
        string $driver = 'excel',
        string $fileName = 'export',
        array $options = []
    ): void {
        // Model'den veriyi al
        $data = $model->findAll();
        
        if (empty($data)) {
            throw SaverException::forEmptyData();
        }

        // Objeleri array'e çevir
        $arrayData = [];
        
        // İlk satır başlıklar
        $firstRow = (array) $data[0];
        $arrayData[] = array_keys($firstRow);
        
        // Veri satırları
        foreach ($data as $row) {
            $arrayData[] = array_values((array) $row);
        }

        $saver = new self();
        
        match ($driver) {
            'excel' => $saver->excel()->setData($arrayData)->setFileName($fileName)->download(),
            'word' => $saver->word()->setData($arrayData)->setFileName($fileName)->download(),
            'pdf' => $saver->pdf()->setData($arrayData)->setFileName($fileName)->download(),
            'html' => $saver->html()->setData($arrayData)->setFileName($fileName)->download(),
            'csv' => $saver->csv()->setData($arrayData)->setFileName($fileName)->download(),
            default => throw SaverException::forInvalidDriver($driver),
        };
    }

    /**
     * Dosyayı varsayılan yola kaydeder
     *
     * @param string $driver excel, word, pdf, html, csv
     * @param array $data
     * @param string $fileName
     * @return string Kaydedilen dosyanın tam yolu
     */
    public function saveToDefault(string $driver, array $data, string $fileName): string
    {
        $driverInstance = match ($driver) {
            'excel' => $this->excel(),
            'word' => $this->word(),
            'pdf' => $this->pdf(),
            'html' => $this->html(),
            'csv' => $this->csv(),
            default => throw SaverException::forInvalidDriver($driver),
        };

        return $driverInstance
            ->setData($data)
            ->setFileName($fileName)
            ->saveToDefault();
    }

    /**
     * Dosyayı belirtilen yola kaydeder
     *
     * @param string $driver excel, word, pdf, html, csv
     * @param array $data
     * @param string $fileName
     * @param string $path
     * @return string Kaydedilen dosyanın tam yolu
     */
    public function saveToPath(string $driver, array $data, string $fileName, string $path): string
    {
        $driverInstance = match ($driver) {
            'excel' => $this->excel(),
            'word' => $this->word(),
            'pdf' => $this->pdf(),
            'html' => $this->html(),
            'csv' => $this->csv(),
            default => throw SaverException::forInvalidDriver($driver),
        };

        return $driverInstance
            ->setData($data)
            ->setFileName($fileName)
            ->save($path);
    }
}

