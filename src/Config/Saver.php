<?php

namespace Yakupeyisan\CodeIgniter4Saver\Config;

use CodeIgniter\Config\BaseConfig;

class Saver extends BaseConfig
{
    /**
     * Varsayılan sürücü
     *
     * @var string
     */
    public string $defaultDriver = 'excel';

    /**
     * Geçici dosya yolu
     *
     * @var string
     */
    public string $tempPath = WRITEPATH . 'uploads/temp/';

    /**
     * Kaydedilen dosyaların varsayılan yolu
     * .env'den SAVER_DEFAULT_SAVE_PATH ile özelleştirilebilir
     *
     * @var string
     */
    public string $savePath = WRITEPATH . 'attachments/';

    /**
     * Otomatik kaydetme modu
     * true ise download yerine otomatik kaydeder
     * .env'den SAVER_AUTO_SAVE ile özelleştirilebilir
     *
     * @var bool
     */
    public bool $autoSave = false;

    /**
     * Constructor - .env değerlerini yükler
     */
    public function __construct()
    {
        parent::__construct();

        // .env'den varsayılan kayıt yolu
        $envSavePath = env('SAVER_DEFAULT_SAVE_PATH', null);
        if ($envSavePath !== null) {
            // Mutlak yol veya WRITEPATH'e göre yol
            if (str_starts_with($envSavePath, '/') || preg_match('/^[A-Z]:\\\\/', $envSavePath)) {
                // Mutlak yol
                $this->savePath = rtrim($envSavePath, '/\\') . DIRECTORY_SEPARATOR;
            } else {
                // WRITEPATH'e göre yol
                $this->savePath = WRITEPATH . trim($envSavePath, '/\\') . DIRECTORY_SEPARATOR;
            }
        } else {
            // Varsayılan: writable/attachments
            $this->savePath = WRITEPATH . 'attachments' . DIRECTORY_SEPARATOR;
        }

        // .env'den geçici dosya yolu
        $envTempPath = env('SAVER_TEMP_PATH', null);
        if ($envTempPath !== null) {
            if (str_starts_with($envTempPath, '/') || preg_match('/^[A-Z]:\\\\/', $envTempPath)) {
                $this->tempPath = rtrim($envTempPath, '/\\') . DIRECTORY_SEPARATOR;
            } else {
                $this->tempPath = WRITEPATH . trim($envTempPath, '/\\') . DIRECTORY_SEPARATOR;
            }
        }

        // .env'den otomatik kaydetme modu
        $this->autoSave = (bool) env('SAVER_AUTO_SAVE', false);

        // .env'den varsayılan sürücü
        $envDriver = env('SAVER_DEFAULT_DRIVER', null);
        if ($envDriver !== null) {
            $this->defaultDriver = $envDriver;
        }
    }

    /**
     * Excel ayarları
     *
     * @var array
     */
    public array $excel = [
        'default_format' => 'Xlsx', // Xlsx, Xls, Csv
        'creator' => 'CodeIgniter 4 Saver',
        'last_modified_by' => 'CodeIgniter 4 Saver',
        'title' => '',
        'subject' => '',
        'description' => '',
        'keywords' => '',
        'category' => '',
    ];

    /**
     * PDF ayarları
     *
     * @var array
     */
    public array $pdf = [
        'engine' => 'mpdf', // mpdf veya tcpdf
        'default_orientation' => 'portrait', // portrait veya landscape
        'default_page_size' => 'A4',
        'default_font' => 'dejavusans',
        'default_font_size' => 11,
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 15,
        'margin_bottom' => 15,
    ];

    /**
     * Word ayarları
     *
     * @var array
     */
    public array $word = [
        'default_font' => 'Arial',
        'default_font_size' => 11,
        'creator' => 'CodeIgniter 4 Saver',
        'title' => '',
        'description' => '',
        'subject' => '',
    ];

    /**
     * HTML ayarları
     *
     * @var array
     */
    public array $html = [
        'template_path' => APPPATH . 'Views/saver/templates/',
        'default_template' => 'default',
        'charset' => 'UTF-8',
        'doctype' => '<!DOCTYPE html>',
    ];

    /**
     * CSV ayarları
     *
     * @var array
     */
    public array $csv = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'encoding' => 'UTF-8',
        'bom' => false, // Byte Order Mark ekle
    ];
}

