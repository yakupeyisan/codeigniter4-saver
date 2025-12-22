<?php

namespace Yakupeyisan\CodeIgniter4Saver\Exceptions;

class ExcelException extends SaverException
{
    public static function forInvalidSheetName(string $name): self
    {
        return new self("Geçersiz sayfa adı: {$name}");
    }

    public static function forInvalidCellReference(string $cell): self
    {
        return new self("Geçersiz hücre referansı: {$cell}");
    }

    public static function forInvalidColumnWidth(string $column): self
    {
        return new self("Geçersiz kolon genişliği: {$column}");
    }

    public static function forInvalidImagePath(string $path): self
    {
        return new self("Resim dosyası bulunamadı: {$path}");
    }
}

