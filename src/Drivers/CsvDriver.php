<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

class CsvDriver extends BaseDriver
{
    /**
     * Ayırıcı karakter
     *
     * @var string
     */
    protected string $delimiter = ',';

    /**
     * Çevreleyen karakter
     *
     * @var string
     */
    protected string $enclosure = '"';

    /**
     * Kaçış karakteri
     *
     * @var string
     */
    protected string $escape = '\\';

    /**
     * Karakter kodlaması
     *
     * @var string
     */
    protected string $encoding = 'UTF-8';

    /**
     * BOM (Byte Order Mark) ekle
     *
     * @var bool
     */
    protected bool $bom = false;

    /**
     * Constructor
     */
    public function __construct($config = null)
    {
        parent::__construct($config);
        $this->delimiter = $this->config->csv['delimiter'];
        $this->enclosure = $this->config->csv['enclosure'];
        $this->escape = $this->config->csv['escape'];
        $this->encoding = $this->config->csv['encoding'];
        $this->bom = $this->config->csv['bom'];
    }

    /**
     * Ayırıcı karakteri belirler
     *
     * @param string $delimiter
     * @return self
     */
    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Çevreleyen karakteri belirler
     *
     * @param string $enclosure
     * @return self
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Kaçış karakterini belirler
     *
     * @param string $escape
     * @return self
     */
    public function setEscape(string $escape): self
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Karakter kodlamasını belirler
     *
     * @param string $encoding
     * @return self
     */
    public function setEncoding(string $encoding): self
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * BOM ekleme durumunu belirler
     *
     * @param bool $bom
     * @return self
     */
    public function setBom(bool $bom): self
    {
        $this->bom = $bom;
        return $this;
    }

    /**
     * CSV içeriğini oluşturur
     *
     * @return string
     */
    protected function buildCsv(): string
    {
        if (empty($this->data)) {
            throw SaverException::forEmptyData();
        }

        $output = fopen('php://temp', 'r+');
        
        // BOM ekle (Excel için UTF-8 desteği)
        if ($this->bom && strtoupper($this->encoding) === 'UTF-8') {
            fwrite($output, "\xEF\xBB\xBF");
        }

        foreach ($this->data as $row) {
            // Encoding dönüşümü
            if (strtoupper($this->encoding) !== 'UTF-8') {
                $row = array_map(function ($value) {
                    return mb_convert_encoding($value, $this->encoding, 'UTF-8');
                }, $row);
            }

            fputcsv($output, $row, $this->delimiter, $this->enclosure, $this->escape);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * CSV dosyasını okur ve array olarak döndürür
     *
     * @param string $filePath
     * @param bool $hasHeader İlk satır başlık mı?
     * @return array
     */
    public function read(string $filePath, bool $hasHeader = true): array
    {
        if (!file_exists($filePath)) {
            throw SaverException::forFileNotSaved($filePath);
        }

        $data = [];
        $headers = [];
        $handle = fopen($filePath, 'r');

        if ($handle === false) {
            throw SaverException::forFileNotSaved($filePath);
        }

        $rowIndex = 0;
        while (($row = fgetcsv($handle, 0, $this->delimiter, $this->enclosure, $this->escape)) !== false) {
            // Encoding dönüşümü
            if (strtoupper($this->encoding) !== 'UTF-8') {
                $row = array_map(function ($value) {
                    return mb_convert_encoding($value, 'UTF-8', $this->encoding);
                }, $row);
            }

            if ($rowIndex === 0 && $hasHeader) {
                $headers = $row;
            } else {
                if ($hasHeader && !empty($headers)) {
                    $data[] = array_combine($headers, $row);
                } else {
                    $data[] = $row;
                }
            }

            $rowIndex++;
        }

        fclose($handle);

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function download(): void
    {
        $csv = $this->buildCsv();
        $fileName = $this->getFileName('.csv');

        header('Content-Type: ' . $this->getMimeType());
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        echo $csv;
        exit;
    }

    /**
     * {@inheritDoc}
     */
    public function save(string $path): string
    {
        $this->ensureDirectoryWritable($path);

        $csv = $this->buildCsv();
        $fileName = $this->getFileName('.csv');
        $filePath = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . $fileName;

        if (file_put_contents($filePath, $csv) === false) {
            throw SaverException::forFileNotSaved($filePath);
        }

        return $filePath;
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->buildCsv();
    }

    /**
     * {@inheritDoc}
     */
    protected function getMimeType(): string
    {
        return 'text/csv';
    }
}

