<?php

use Yakupeyisan\CodeIgniter4Saver\Saver;

if (!function_exists('saver')) {
    /**
     * Saver helper function
     *
     * @return Saver
     */
    function saver(): Saver
    {
        return new Saver();
    }
}

if (!function_exists('export_excel')) {
    /**
     * Hızlı Excel export
     *
     * @param array $data
     * @param string $fileName
     * @param string $format
     * @return void
     */
    function export_excel(array $data, string $fileName, string $format = 'Xlsx'): void
    {
        Saver::exportExcel($data, $fileName, $format);
    }
}

if (!function_exists('export_csv')) {
    /**
     * Hızlı CSV export
     *
     * @param array $data
     * @param string $fileName
     * @return void
     */
    function export_csv(array $data, string $fileName): void
    {
        Saver::exportCsv($data, $fileName);
    }
}

if (!function_exists('export_pdf')) {
    /**
     * Hızlı PDF export
     *
     * @param string $html
     * @param string $fileName
     * @param string $engine
     * @return void
     */
    function export_pdf(string $html, string $fileName, string $engine = 'mpdf'): void
    {
        Saver::exportPdf($html, $fileName, $engine);
    }
}

if (!function_exists('export_html')) {
    /**
     * Hızlı HTML export
     *
     * @param array $data
     * @param string $fileName
     * @param string $title
     * @return void
     */
    function export_html(array $data, string $fileName, string $title = 'Document'): void
    {
        Saver::exportHtml($data, $fileName, $title);
    }
}

if (!function_exists('export_word')) {
    /**
     * Hızlı Word export
     *
     * @param array $data
     * @param string $fileName
     * @return void
     */
    function export_word(array $data, string $fileName): void
    {
        saver()->word()
            ->setData($data)
            ->setFileName($fileName)
            ->download();
    }
}

