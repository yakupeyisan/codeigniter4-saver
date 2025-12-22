# Changelog

Bu dosya projedeki tüm önemli değişiklikleri içerir.

Format [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) standartına dayalıdır.

## [Unreleased]

### Planlanan Özellikler
- [ ] OpenDocument (ODS) desteği
- [ ] RTF (Rich Text Format) desteği
- [ ] XML export desteği
- [ ] JSON export desteği
- [ ] Queue entegrasyonu
- [ ] Otomatik veri tipi algılama
- [ ] Chart/Grafik desteği (Excel ve PDF için)
- [ ] Template motor entegrasyonu
- [ ] Multi-sheet Excel desteği
- [ ] Şifrelenmiş dosya oluşturma

## [1.0.0] - 2024-12-15

### İlk Sürüm

#### Eklenenler
- ✅ Excel export/import (PhpSpreadsheet)
  - Xlsx formatı
  - Xls formatı
  - Stil ve formatlama desteği
  - Otomatik kolon genişliği
  - Başlık stilleri
  - Otomatik filtre
  
- ✅ Word belgesi oluşturma (PhpWord)
  - Başlık ekleme
  - Metin ekleme
  - Tablo ekleme
  - Liste ekleme
  - Resim ekleme
  - Sayfa sonu
  - Özel stil desteği
  
- ✅ PDF oluşturma
  - mPDF desteği
  - TCPDF desteği
  - Sayfa ayarları (yönlendirme, boyut)
  - Başlık ve altbilgi
  - Filigran desteği
  - Özel margin ayarları
  
- ✅ HTML export
  - Varsayılan şablon
  - Özel şablon desteği
  - CSS özelleştirme
  - JavaScript desteği
  - Responsive tasarım
  
- ✅ CSV işlemleri
  - CSV export
  - CSV import
  - Özel delimiter
  - Encoding desteği
  - BOM desteği
  
- ✅ Yardımcı Özellikler
  - Helper fonksiyonları
  - Model export desteği
  - String output (email ekleri için)
  - Dosyaya kaydetme
  - Tarayıcıya indirme
  - Özelleştirilebilir config
  
- ✅ CLI Command
  - Config publish komutu
  
- ✅ Dokümantasyon
  - README.md
  - EXAMPLES.md
  - INSTALLATION.md
  - API dokümantasyonu

#### Güvenlik
- Input validation
- XSS koruması (HTML output)
- Path traversal koruması
- File permission kontrolü

---

## Versiyon Notları

### Semantic Versioning

Bu proje [Semantic Versioning](https://semver.org/) kullanır:
- **MAJOR**: Geriye uyumlu olmayan API değişiklikleri
- **MINOR**: Geriye uyumlu yeni özellikler
- **PATCH**: Geriye uyumlu hata düzeltmeleri

### Breaking Changes

Versiyon güncellemelerinde breaking changes olursa burada belirtilecektir.

### Migration Guide

Versiyon yükseltmelerinde gerekli değişiklikler için kılavuz.

---

## [1.0.0] Detayları

### Desteklenen Formatlar

| Format | Export | Import | Stil | Resim | Tablo |
|--------|--------|--------|------|-------|-------|
| Excel (Xlsx/Xls) | ✅ | ❌ | ✅ | ❌ | ✅ |
| Word (Docx) | ✅ | ❌ | ✅ | ✅ | ✅ |
| PDF | ✅ | ❌ | ✅ | ✅ | ✅ |
| HTML | ✅ | ❌ | ✅ | ✅ | ✅ |
| CSV | ✅ | ✅ | ❌ | ❌ | ✅ |

### Sistem Gereksinimleri

- PHP >= 8.1
- CodeIgniter 4.x
- Composer
- Minimum 128MB RAM (256MB önerilir)
- GD veya Imagick (resim işleme için)

### Bağımlılıklar

```json
{
    "phpoffice/phpspreadsheet": "^1.29|^2.0",
    "phpoffice/phpword": "^1.2",
    "tecnickcom/tcpdf": "^6.6",
    "mpdf/mpdf": "^8.2"
}
```

### Bilinen Sorunlar

1. **Büyük Dosyalar**: 10,000+ satır için performans optimizasyonu gerekebilir.
2. **Bellek Kullanımı**: Büyük veri setleri için memory_limit artırılmalı.
3. **Font Desteği**: TCPDF'de bazı özel fontlar çalışmayabilir.

### Performans Metrikleri

Ortalama işlem süreleri (1000 satır için):
- Excel: ~2 saniye
- PDF: ~3 saniye
- Word: ~4 saniye
- HTML: ~1 saniye
- CSV: ~0.5 saniye

---

## Katkıda Bulunanlar

Teşekkürler:
- Yakup EYİSAN (@yakupeyisan) - İlk geliştirici

---

## Lisans

MIT License - Detaylar için [LICENSE](LICENSE) dosyasına bakın.

