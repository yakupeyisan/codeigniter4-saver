# Upgrade Guide

Bu dokümanda paket versiyonları arasındaki değişiklikler ve yükseltme adımları açıklanmaktadır.

## 1.0.0'dan Sonraki Versiyonlara Geçiş

Bu bölüm gelecekteki versiyonlar için rezerve edilmiştir.

## İlk Kurulum (1.0.0)

### Yeni Kurulum

```bash
composer require yakupeyisan/codeigniter4-saver
```

### Config Yayınlama

```bash
php spark saver:publish
```

### Dizin Oluşturma

```bash
mkdir -p writable/uploads/temp
mkdir -p writable/uploads/saved
chmod -R 775 writable/uploads
```

## Versiyon Notları

### 1.0.0 (2024-12-15)

**İlk Stabil Sürüm**

Özellikler:
- ✅ Excel export (Xlsx, Xls)
- ✅ Word belgesi oluşturma (Docx)
- ✅ PDF oluşturma (mPDF, TCPDF)
- ✅ HTML export
- ✅ CSV export/import
- ✅ Helper fonksiyonları
- ✅ Model export desteği
- ✅ CLI command

**Gereksinimler:**
- PHP >= 8.1
- CodeIgniter >= 4.0
- Composer

**Kurulum:**
```bash
composer require yakupeyisan/codeigniter4-saver
```

**Breaking Changes:** Yok (ilk sürüm)

---

## Gelecek Versiyonlar İçin Notlar

### Breaking Changes Nasıl İşlenir

Breaking change içeren güncellemeler için:

1. **CHANGELOG.md**: Değişiklikler detaylı açıklanır
2. **UPGRADE.md**: Yükseltme adımları eklenir
3. **Deprecation Warnings**: Eski metodlar için uyarılar
4. **Migration Scripts**: Gerekirse otomatik migration

### Deprecation Policy

Bir özellik kaldırılacaksa:

1. **Minor Version**: Deprecation warning eklenir
2. **Dokümantasyon**: Alternatif çözüm açıklanır
3. **Major Version**: Özellik kaldırılır

Örnek:
```php
// v1.5.0 - Deprecated
/**
 * @deprecated 1.5.0 Use newMethod() instead
 */
public function oldMethod()
{
    trigger_error('oldMethod() is deprecated. Use newMethod() instead.', E_USER_DEPRECATED);
    return $this->newMethod();
}

// v2.0.0 - Removed
// oldMethod() artık mevcut değil
```

---

## Sürüm Kontrolü

### Semantic Versioning

Bu proje [Semantic Versioning](https://semver.org/) kullanır:

**X.Y.Z** formatında:
- **X (Major)**: Breaking changes
- **Y (Minor)**: Yeni özellikler (geriye uyumlu)
- **Z (Patch)**: Bug fixes (geriye uyumlu)

### Örnekler

- `1.0.0` → `1.0.1`: Bug fix, güvenle güncellenebilir
- `1.0.1` → `1.1.0`: Yeni özellik, güvenle güncellenebilir
- `1.1.0` → `2.0.0`: Breaking changes, dikkatli güncelleme gerekir

---

## Güncelleme Kontrolü

### Mevcut Versiyon

```php
// composer.json
{
    "require": {
        "yakupeyisan/codeigniter4-saver": "^1.0"
    }
}
```

### Yeni Versiyon Kontrolü

```bash
# Mevcut versiyon
composer show yakupeyisan/codeigniter4-saver

# Güncellemeleri kontrol et
composer outdated yakupeyisan/codeigniter4-saver
```

### Güncelleme

```bash
# Minor/patch güncellemeler için
composer update yakupeyisan/codeigniter4-saver

# Major version için
composer require yakupeyisan/codeigniter4-saver:^2.0
```

---

## Test Etme

Güncelleme sonrası test:

```bash
# 1. Autoload yenile
composer dump-autoload

# 2. Cache temizle
php spark cache:clear

# 3. Test sayfasını çalıştır
php spark serve
# http://localhost:8080/export adresine gidin
```

---

## Sorun Giderme

### Güncelleme Sonrası Hatalar

**"Class not found"**
```bash
composer dump-autoload
php spark cache:clear
```

**"Config not found"**
```bash
php spark saver:publish
# Config dosyasını yeniden yayınlayın
```

**"Method not found"**
```
CHANGELOG.md'yi kontrol edin
Breaking changes olabilir
```

### Rollback

Eski versiyona dönmek için:

```bash
# Belirli versiyonu yükle
composer require yakupeyisan/codeigniter4-saver:1.0.0

# Autoload yenile
composer dump-autoload
```

---

## Yardım

Güncelleme sırasında sorun mu yaşıyorsunuz?

- 📖 **Dokümantasyon**: README.md, EXAMPLES.md
- 🐛 **Issues**: https://github.com/yakupeyisan/codeigniter4-saver/issues
- 📧 **Email**: yakupeyisan@gmail.com

---

**Son Güncelleme**: 2024-12-15

