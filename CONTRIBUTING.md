# Katkıda Bulunma Rehberi

CodeIgniter 4 Saver projesine katkıda bulunmak istediğiniz için teşekkürler! 🎉

## 📋 İçindekiler

- [Davranış Kuralları](#davranış-kuralları)
- [Nasıl Katkıda Bulunabilirim?](#nasıl-katkıda-bulunabilirim)
- [Geliştirme Ortamı](#geliştirme-ortamı)
- [Pull Request Süreci](#pull-request-süreci)
- [Kod Standartları](#kod-standartları)
- [Test Yazma](#test-yazma)
- [Dokümantasyon](#dokümantasyon)

## Davranış Kuralları

Bu proje ve topluluğu bir taciz-free deneyim sağlamayı taahhüt eder. Lütfen birbirinize saygılı olun.

### Beklentilerimiz

✅ **Yapılması Gerekenler:**
- Yapıcı ve saygılı olun
- Farklı görüşlere açık olun
- Yapıcı eleştiri kabul edin
- Topluluk çıkarlarını gözetin

❌ **Yapılmaması Gerekenler:**
- Saldırgan dil kullanmayın
- Kişisel saldırılarda bulunmayın
- Taciz etmeyin
- Profesyonel olmayan davranışlarda bulunmayın

## Nasıl Katkıda Bulunabilirim?

### 🐛 Hata Bildirimi

Hata buldunuz mu? GitHub Issues'da bildirin:

1. **Aramak**: Aynı hata daha önce bildirilmiş mi kontrol edin
2. **Detaylı Açıklama**:
   - Ne yaptınız?
   - Ne bekliyordunuz?
   - Ne oldu?
   - Hata mesajı nedir?
3. **Ortam Bilgisi**:
   - PHP versiyonu
   - CodeIgniter versiyonu
   - İşletim sistemi
   - Paket versiyonu

**Örnek Hata Bildirimi:**

```markdown
### Açıklama
Excel export sırasında Türkçe karakterler bozuluyor.

### Adımlar
1. `$data` içinde Türkçe karakter olan veri oluştur
2. `excel()->setData($data)->download()` çalıştır
3. İndirilen dosyayı aç

### Beklenen
Türkçe karakterlerin düzgün görünmesi

### Gerçekleşen
Türkçe karakterler "?????" şeklinde görünüyor

### Ortam
- PHP: 8.1.12
- CodeIgniter: 4.4.3
- OS: Ubuntu 22.04
- Paket: 1.0.0
```

### 💡 Özellik Önerisi

Yeni özellik mi istiyorsunuz?

1. **Issue Oluştur**: "Feature Request" etiketi ile
2. **Detaylı Açıklayın**:
   - Hangi problemi çözüyor?
   - Nasıl çalışmalı?
   - Örnek kullanım senaryosu

### 📝 Dokümantasyon İyileştirme

- Yazım hataları
- Eksik açıklamalar
- Daha iyi örnekler
- Çeviri

### 💻 Kod Katkısı

1. Fork edin
2. Branch oluşturun
3. Değişiklikleri yapın
4. Test edin
5. Pull Request gönderin

## Geliştirme Ortamı

### Kurulum

```bash
# Projeyi fork edin ve klonlayın
git clone https://github.com/SIZIN-USERNAME/codeigniter4-saver.git
cd codeigniter4-saver

# Bağımlılıkları yükleyin
composer install

# Test için CodeIgniter 4 projesi oluşturun
cd ..
composer create-project codeigniter4/appstarter test-project
cd test-project

# Paketi local olarak ekleyin
composer config repositories.local path ../codeigniter4-saver
composer require yakupeyisan/codeigniter4-saver:@dev
```

### Gereksinimler

- PHP 8.1+
- Composer
- Git
- CodeIgniter 4.x

## Pull Request Süreci

### 1. Branch Oluşturma

```bash
# Feature için
git checkout -b feature/yeni-ozellik

# Bug fix için
git checkout -b fix/hata-aciklamasi

# Dokümantasyon için
git checkout -b docs/dokuman-guncellemesi
```

### 2. Değişiklikleri Yapma

```bash
# Değişikliklerinizi yapın
# Test edin
# Commit edin

git add .
git commit -m "feat: yeni özellik eklendi"
```

### 3. Commit Mesajı Formatı

[Conventional Commits](https://www.conventionalcommits.org/) kullanın:

```
<tip>(<kapsam>): <açıklama>

[opsiyonel gövde]

[opsiyonel footer]
```

**Tipler:**
- `feat`: Yeni özellik
- `fix`: Hata düzeltme
- `docs`: Dokümantasyon
- `style`: Kod formatı (logic değişikliği yok)
- `refactor`: Kod iyileştirme
- `test`: Test ekleme/düzeltme
- `chore`: Build/tool değişiklikleri

**Örnekler:**

```bash
feat(excel): otomatik kolon genişliği eklendi
fix(pdf): Türkçe karakter sorunu çözüldü
docs(readme): kurulum adımları güncellendi
refactor(csv): kod tekrarı azaltıldı
```

### 4. Push ve PR

```bash
# Fork'unuza push edin
git push origin feature/yeni-ozellik

# GitHub'da Pull Request oluşturun
```

### 5. PR Açıklaması

**Şablon:**

```markdown
## Açıklama
Ne değişti ve neden?

## Değişiklik Tipi
- [ ] Bug fix (geriye uyumlu hata düzeltme)
- [ ] Yeni özellik (geriye uyumlu yeni fonksiyon)
- [ ] Breaking change (mevcut fonksiyonu bozan değişiklik)
- [ ] Dokümantasyon güncellemesi

## Nasıl Test Edildi?
Test adımlarınızı açıklayın

## Checklist
- [ ] Kod style kurallarına uygun
- [ ] Kendi kendini açıklayan kod yazdım
- [ ] İlgili dokümantasyonu güncelledim
- [ ] Değişikliklerim uyarı üretmiyor
- [ ] Testler ekledim
- [ ] Tüm testler geçiyor
```

## Kod Standartları

### PSR-12 Kod Stili

```php
<?php

namespace Yakupeyisan\CodeIgniter4Saver\Example;

use Some\Namespace\Class;

class ExampleClass
{
    private string $property;

    public function exampleMethod(string $parameter): string
    {
        if ($condition) {
            // kod
        }

        return $result;
    }
}
```

### Dokümantasyon

Her public method için PHPDoc ekleyin:

```php
/**
 * Kısa açıklama
 *
 * Detaylı açıklama gerekirse buraya yazın.
 *
 * @param string $parameter Parametre açıklaması
 * @return string Dönüş değeri açıklaması
 * @throws ExceptionType Ne zaman exception fırlatır
 */
public function exampleMethod(string $parameter): string
{
    // kod
}
```

### Kod Kalitesi

```bash
# PHP CS Fixer
composer require friendsofphp/php-cs-fixer --dev
vendor/bin/php-cs-fixer fix

# PHPStan
composer require phpstan/phpstan --dev
vendor/bin/phpstan analyse src
```

## Test Yazma

### Test Yapısı

```php
<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use Yakupeyisan\CodeIgniter4Saver\Saver;

class SaverTest extends CIUnitTestCase
{
    public function testExcelExport(): void
    {
        $data = [
            ['Header1', 'Header2'],
            ['Value1', 'Value2'],
        ];

        $saver = new Saver();
        $content = $saver->excel()
            ->setData($data)
            ->setFileName('test.xlsx')
            ->toString();

        $this->assertNotEmpty($content);
    }
}
```

### Test Çalıştırma

```bash
# Tüm testler
composer test

# Belirli bir test
composer test -- --filter testExcelExport

# Coverage raporu
composer test -- --coverage-html coverage
```

## Dokümantasyon

### README Güncellemeleri

- Yeni özellikler eklendiğinde örnek ekleyin
- Breaking changes olduğunda migration guide ekleyin
- API değişikliklerini belgelendirin

### EXAMPLES.md

Yeni özellikler için detaylı örnekler ekleyin:

```markdown
### Yeni Özellik Başlığı

Kısa açıklama

```php
// Basit örnek
$saver->newFeature()
    ->setOption('value')
    ->download();
```

### İleri Düzey Kullanım

```php
// Karmaşık örnek
$saver->newFeature()
    ->setOption1('value1')
    ->setOption2('value2')
    ->customMethod()
    ->download();
```
```

### CHANGELOG

Her değişiklik için CHANGELOG.md'yi güncelleyin:

```markdown
## [Unreleased]

### Added
- Yeni özellik açıklaması (#PR_NUMBER)

### Fixed
- Düzeltilen hata (#ISSUE_NUMBER)

### Changed
- Değiştirilen davranış

### Deprecated
- Kullanımdan kaldırılacak özellik
```

## Review Süreci

PR'ınız gönderildikten sonra:

1. **Otomatik Kontroller**: CI/CD testleri çalışır
2. **Code Review**: Maintainer kodunuzu inceler
3. **Değişiklik İstekleri**: Gerekirse değişiklik yapın
4. **Onay ve Merge**: Onaylanınca merge edilir

### Review Kriterleri

- ✅ Kod kalitesi
- ✅ Test coverage
- ✅ Dokümantasyon
- ✅ Geriye uyumluluk
- ✅ Performans
- ✅ Güvenlik

## Yardım ve Sorular

Sorularınız için:

- 💬 GitHub Discussions
- 📧 Email: yakupeyisan@gmail.com
- 🐛 Issues (teknik sorular için)

## Lisans

Katkılarınız MIT Lisansı altında yayınlanacaktır.

---

**Teşekkürler!** 🙏

Katkılarınız bu projeyi daha iyi hale getirir!

