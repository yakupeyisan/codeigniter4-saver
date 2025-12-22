<?php

namespace Yakupeyisan\CodeIgniter4Saver\Drivers;

use Yakupeyisan\CodeIgniter4Saver\Config\Saver as SaverConfig;
use Yakupeyisan\CodeIgniter4Saver\Contracts\SaverInterface;
use Yakupeyisan\CodeIgniter4Saver\Exceptions\SaverException;

abstract class BaseDriver implements SaverInterface
{
    /**
     * Veri
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Dosya adı
     *
     * @var string
     */
    protected string $fileName = '';

    /**
     * Config
     *
     * @var SaverConfig
     */
    protected SaverConfig $config;

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
     * {@inheritDoc}
     */
    public function setData(array $data): self
    {
        if (empty($data)) {
            throw SaverException::forEmptyData();
        }

        $this->data = $data;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Dosya adını döndürür (uzantı ile)
     *
     * @param string $extension
     * @return string
     */
    protected function getFileName(string $extension = ''): string
    {
        if (empty($this->fileName)) {
            throw SaverException::forInvalidFileName();
        }

        $fileName = $this->fileName;
        
        // Eğer dosya adında uzantı yoksa ekle
        if ($extension && !str_ends_with(strtolower($fileName), strtolower($extension))) {
            $fileName .= $extension;
        }

        return $fileName;
    }

    /**
     * Dizinin yazılabilir olup olmadığını kontrol eder
     *
     * @param string $path
     * @return void
     * @throws SaverException
     */
    protected function ensureDirectoryWritable(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw SaverException::forDirectoryNotWritable($path);
            }
        }

        if (!is_writable($path)) {
            throw SaverException::forDirectoryNotWritable($path);
        }
    }

    /**
     * Varsayılan kayıt yolunu döndürür
     *
     * @return string
     */
    protected function getDefaultSavePath(): string
    {
        return $this->config->savePath;
    }

    /**
     * Dosyayı varsayılan yola kaydeder
     *
     * @return string Kaydedilen dosyanın tam yolu
     */
    public function saveToDefault(): string
    {
        return $this->save($this->getDefaultSavePath());
    }

    /**
     * Mime type'ı döndürür
     *
     * @return string
     */
    abstract protected function getMimeType(): string;
}

