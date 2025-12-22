<?php

namespace Yakupeyisan\CodeIgniter4Saver\Exceptions;

use Exception;

class SaverException extends Exception
{
    public static function forInvalidDriver(string $driver): self
    {
        return new self("Geçersiz sürücü: {$driver}");
    }

    public static function forEmptyData(): self
    {
        return new self("Veri boş olamaz.");
    }

    public static function forInvalidFileName(): self
    {
        return new self("Dosya adı belirtilmedi.");
    }

    public static function forDirectoryNotWritable(string $path): self
    {
        return new self("Dizin yazılabilir değil: {$path}");
    }

    public static function forFileNotSaved(string $path): self
    {
        return new self("Dosya kaydedilemedi: {$path}");
    }

    public static function forInvalidTemplate(string $template): self
    {
        return new self("Şablon bulunamadı: {$template}");
    }

    public static function forInvalidFormat(string $format): self
    {
        return new self("Geçersiz format: {$format}");
    }

    public static function forMissingExtension(string $extension): self
    {
        return new self("Gerekli PHP eklentisi yüklü değil: {$extension}");
    }
}

