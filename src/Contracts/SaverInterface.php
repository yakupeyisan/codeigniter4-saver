<?php

namespace Yakupeyisan\CodeIgniter4Saver\Contracts;

interface SaverInterface
{
    /**
     * Veriyi ayarlar
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data): self;

    /**
     * Dosya adını ayarlar
     *
     * @param string $fileName
     * @return self
     */
    public function setFileName(string $fileName): self;

    /**
     * Dosyayı tarayıcıya indirir
     *
     * @return void
     */
    public function download(): void;

    /**
     * Dosyayı belirtilen dizine kaydeder
     *
     * @param string $path
     * @return string Kaydedilen dosyanın tam yolu
     */
    public function save(string $path): string;

    /**
     * İçeriği string olarak döndürür
     *
     * @return string
     */
    public function toString(): string;
}

