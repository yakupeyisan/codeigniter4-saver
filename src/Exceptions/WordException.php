<?php

namespace Yakupeyisan\CodeIgniter4Saver\Exceptions;

class WordException extends SaverException
{
    public static function forInvalidImagePath(string $path): self
    {
        return new self("Resim dosyası bulunamadı: {$path}");
    }

    public static function forInvalidTableStructure(): self
    {
        return new self("Tablo yapısı geçersiz. Her satırda aynı sayıda kolon olmalı.");
    }

    public static function forEmptyContent(): self
    {
        return new self("Word içeriği boş olamaz.");
    }
}

