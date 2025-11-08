# Adliye Teftis

**Mahkeme Teftis Asistanı** - Adalet Bakanlığı bünyesinde mahkeme teftis iş akışlarını ve idari görevleri yönetmek için geliştirilmiş web uygulaması.

## 🎯 Amaç

Bu uygulama, teftis personeline aşağıdaki araçları sağlar:
- Teftis kayıtlarını yönetme (Teftis Defterleri)
- Yasal süreçleri takip etme (Karar, İstinaf, Temyiz)
- Personel durumu ve terfilerini hesaplama
- İddianame değerlendirme belgeleri oluşturma
- Dava kesinleşme sürelerini izleme
- Mahkeme harçlarını ve giderlerini hesaplama
- E-posta bildirimleri gönderme

## 🚀 Özellikler

### Ana Modüller
- **📊 Karar Defteri** - Karar kaydı ve takibi
- **📋 İstinaf Defteri** - İstinaf kayıtları yönetimi
- **📄 İddianame Değerlendirme** - İddianame değerlendirme ve belge oluşturma
- **💰 Harç Tahsil Kontrol** - Harç tahsilatı doğrulama
- **⏱️ Kesinleşme Hesaplama** - Dava kesinleşme hesaplama
- **🧮 Yargılama Gideri** - Yargılama gideri hesaplayıcı
- **👤 Personel Hesap** - Personel durumu ve terfi hesaplamaları
- **🤖 JSON Robot** - Otomatik JSON veri işleme

### Teknik Özellikler
- ✅ E-posta bildirimleri (@adalet.gov.tr ile sınırlı)
- ✅ DOCX belge oluşturma
- ✅ Excel dosya içe/dışa aktarma (XLSX)
- ✅ Hız sınırlama ve kötüye kullanım önleme
- ✅ Koyu/Açık tema desteği
- ✅ Duyarlı tasarım (Responsive)
- ✅ Dosya tabanlı JSON veri depolama

## 📋 Gereksinimler

- **PHP:** 8.0+ (8.3 ile test edilmiştir)
- **Web Sunucusu:** Apache veya Nginx
- **PHP Eklentileri:**
  - json, zip, dom, mbstring, openssl, fileinfo

Detaylı gereksinimler için [DEPLOYMENT.md](DEPLOYMENT.md) dosyasına bakın.

## 🔧 Kurulum

```bash
# Depoyu klonlayın
git clone https://github.com/gokhantasci/Adliye-Tefti-Yard-mc-s-.git
cd Adliye-Tefti-Yard-mc-s-

# Ortam değişkenlerini yapılandırın
cp .env.example .env
# .env dosyasını Gmail kimlik bilgilerinizle düzenleyin

# İzinleri ayarlayın
chmod 755 data/
chmod 644 data/*.json

# Web sunucusuna dağıtın
# Detaylı talimatlar için DEPLOYMENT.md dosyasına bakın
```

## 🔒 Güvenlik

- ✅ **Kritik güvenlik açığı yok** (otomatik güvenlik taraması ile doğrulandı)
- ✅ XSS koruması (çıktı kaçış karakterleri ile)
- ✅ E-posta uç noktalarında hız sınırlama
- ✅ Alan adı kısıtlamalı e-posta gönderimi
- ✅ Giriş doğrulama ve sterilizasyon
- ✅ Honeypot koruması

**Güvenlik Skoru:** 8.5/10 - Detaylar için [CODE_REVIEW_REPORT.md](CODE_REVIEW_REPORT.md)

## 📚 Dokümantasyon

- [🚀 Dağıtım Kılavuzu](DEPLOYMENT.md) - Kurulum ve yapılandırma
- [🔍 Kod İnceleme Raporu](CODE_REVIEW_REPORT.md) - Güvenlik ve kalite analizi
- [⚙️ Ortam Değişkenleri](.env.example) - Yapılandırma şablonu

## 🏗️ Mimari

### Arka Uç (PHP)
- `/api` klasöründe API uç noktaları
- Yeniden kullanılabilir bileşenler için partials
- `/data` klasöründe dosya tabanlı JSON depolama
- E-posta işlevselliği için PHPMailer

### Ön Uç (JavaScript)
- Saf JavaScript (framework yok)
- AJAX işlemleri için jQuery
- Excel işleme için XLSX.js
- İkonlar için Material Symbols

### Veri Depolama
- Kalıcılık için JSON dosyaları
- Veritabanı gerekmez
- Eşzamanlı erişim için dosya kilitleme

## 📊 Proje İstatistikleri

- **PHP Dosyaları:** 25
- **JavaScript Dosyaları:** 24
- **Kod Satırı:** ~30.000+
- **Kod Kalitesi:** 8/10
- **Test Kapsamı:** Manuel test

## 🤝 Katkıda Bulunma

Bu dahili bir devlet uygulamasıdır. Sorunlar veya öneriler için:
1. Mevcut sorunları kontrol edin
2. Detaylı hata raporları oluşturun
3. Kod stil kurallarına uyun
4. Göndermeden önce kapsamlı test edin

## 📄 Lisans

Lisans bilgisi için depoya bakın.

## 👨‍💻 Yazar

Gökhan TAŞÇI - [657.com.tr](https://657.com.tr)

## 🔗 İlgili Projeler

- [657 - Devlet Memurları](https://657.com.tr/)
- [Müdürün Dolabı](https://657.com.tr/mudurun-dolabi-adliye-dosya-takip-hatirlatma-programi/)
- [Yargılama Gideri Hesap Makinesi](https://657.com.tr/yargilama-gideri-hesap-makinesi/)
- [Kesinleşme Hesaplama](https://657.com.tr/kesinlesme-hesaplama/)

## 📝 Değişiklik Günlüğü

### Son Güncellemeler
- ✅ Kapsamlı kod inceleme dokümantasyonu eklendi
- ✅ Dağıtım kılavuzu oluşturuldu
- ✅ Ortam yapılandırma şablonu eklendi
- ✅ Daha iyi sürüm kontrolü için .gitignore uygulandı
- ✅ Güvenlik denetimi tamamlandı - Kritik sorun bulunamadı

---

**Durum:** ✅ Üretime Hazır  
**Ortam:** Dahili Devlet Ağı  
**Dil:** Türkçe (TR)  
**Son Güncelleme:** Kasım 2025