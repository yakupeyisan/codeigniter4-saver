<?php

namespace Yakupeyisan\CodeIgniter4Saver\Exceptions;

class PdfException extends SaverException
{
    public static function forInvalidEngine(string $engine): self
    {
        return new self("Geçersiz PDF motoru: {$engine}. Desteklenen: mpdf, tcpdf");
    }

    public static function forInvalidOrientation(string $orientation): self
    {
        return new self("Geçersiz sayfa yönlendirmesi: {$orientation}. Desteklenen: portrait, landscape");
    }

    public static function forInvalidPageSize(string $size): self
    {
        return new self("Geçersiz sayfa boyutu: {$size}");
    }

    public static function forEmptyContent(): self
    {
        return new self("PDF içeriği boş olamaz.");
    }

    public static function forInvalidImagePath(string $path): self
    {
        return new self("Resim dosyası bulunamadı: {$path}");
    }
}

