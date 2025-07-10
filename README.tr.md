# Alpzo - Yerel Web Geliştirme Ortamı Yöneticisi

<p align="center">
  <strong>Windows için geliştirilmiş, ücretsiz ve açık kaynaklı web geliştirme araçları yönetim platformu</strong>
</p>

> **Language**: Türkçe | [İngilizce](README.md)


## 📋 İçindekiler

- [Giriş](#giriş)
- [Neden Alpzo?](#neden-alpzo)
- [Temel Özellikler](#temel-özellikler)
- [Kurulum ve Başlangıç](#kurulum-ve-başlangıç)
- [Kullanım Kılavuzu](#kullanım-kılavuzu)
    - [PHP Yönetimi](#php-yönetimi)
    - [NodeJS Yönetimi](#nodejs-yönetimi)
    - [Proje Yönetimi](#proje-yönetimi)
    - [Path Yönetimi](#path-yönetimi)
- [Güvenlik ve İzinler](#güvenlik-ve-izinler)
- [Sıkça Sorulan Sorular](#sıkça-sorulan-sorular)
- [Yol Haritası](#yol-haritası)
- [Lisans](#lisans)

## 🎯 Giriş

Alpzo, web geliştiricilerin yerel geliştirme ortamlarını kolayca yönetebilmeleri için tasarlanmış kapsamlı bir araçtır. PHP, MySQL, Nginx ve NodeJS gibi temel teknolojileri tek bir arayüzden yönetmenizi sağlar. Şu anda sadece Windows işletim sistemi için geliştirilmiştir.

## 💡 Neden Alpzo?

Alpzo'nun geliştirilme motivasyonu, mevcut çözümlerdeki eksikliklerden kaynaklanmaktadır:

### Mevcut Çözümlerin Sorunları:
- **Güncellik Sorunu**: Piyasadaki alternatiflerin çoğu güncel değil veya düzenli güncelleme almıyor
- **Yüksek Maliyet**: Güncel ve işlevsel olan uygulamalar genellikle ücretli
- **Yetersiz Destek**: Ücretsiz alternatiflerin teknik desteği sınırlı veya hiç yok
- **Kötü Kullanıcı Deneyimi**: Karmaşık arayüzler ve kullanıcı dostu olmayan tasarımlar
- **Özelleştirme Eksikliği**: Kullanıcı ihtiyaçlarına göre uyarlanamayan katı yapılar

### Alpzo'nun Çözümü:
- ✅ **Tamamen Ücretsiz**: Hiçbir gizli ücret veya premium özellik yok
- ✅ **Açık Kaynak**: Kodlar herkese açık, ihtiyaçlarınıza göre özelleştirebilirsiniz
- ✅ **Modern ve Kullanıcı Dostu**: Basit ve anlaşılır arayüz tasarımı
- ✅ **Aktif Geliştirme**: Düzenli güncellemeler ve yeni özellikler

## ✨ Temel Özellikler

### 🚀 Otomatik Başlatma Sistemi

Alpzo her açılışta şu işlemleri otomatik olarak gerçekleştirir:

1. **Sistem Kontrolü**
    - Kritik dizinlerin varlığını kontrol eder
    - Eksik dizinleri otomatik oluşturur
    - Yapılandırma dosyalarını doğrular

2. **Servis Başlatma**
    - MySQL veritabanı servisini başlatır
    - Yüklü PHP sürümlerini aktif hale getirir
    - Her PHP sürümüne otomatik port atar (9000'den başlayarak)
    - Nginx web sunucusunu başlatır

3. **Proje Tarama**
    - Tanımlı dizinlerdeki projeleri tarar
    - Yeni projeleri otomatik tespit eder
    - Varsayılan ayarlara göre gerekli yapılandırmaları oluşturur

> **İpucu**: Otomatik tarama özelliği performans için ayarlar sayfasından devre dışı bırakılabilir.

### 📦 PHP Yönetimi

PHP yönetimi, Alpzo'nun en güçlü özelliklerinden biridir:

#### Desteklenen Özellikler:

**1. Sürüm Yönetimi**
- PHP 7.x ve üzeri tüm sürümleri destekler
- Tek tıkla PHP sürümü indirme ve kurulum
- Birden fazla PHP sürümünü aynı anda kullanabilme

**2. Yapılandırma Yönetimi**
- Görsel php.ini editörü
- Her değişiklikten önce otomatik yedekleme
- Değişiklik geçmişi ve geri alma özelliği

**3. Port Yönetimi**
- Otomatik port ataması (9000'den başlar, her sürüm için +1)
- Port çakışmalarını otomatik tespit ve çözüm

**4. Servis Kontrolü**
- PHP-FPM servislerini başlatma/durdurma
- Toplu servis yönetimi
- Aktif PHP modüllerini görüntüleme

**5. Proje İlişkilendirme**
- Her proje için farklı PHP sürümü kullanabilme
- PHP sürümüne bağlı projeleri listeleme
- Güvenli silme (kullanımda olan sürümler silinemez)

### 🟢 NodeJS Yönetimi

NodeJS yönetimi basit ve etkilidir:

- Tüm NodeJS sürümlerini listeleme
- İstediğiniz sürümü indirme ve kurma
- Sürümler arası hızlı geçiş
- Kullanılmayan sürümleri temizleme

### 📁 Proje Yönetimi

Alpzo'nun proje yönetimi özellikleri, modern web geliştirme ihtiyaçlarına göre tasarlanmıştır:

#### Desteklenen Proje Tipleri:
- **Backend**: Laravel, Blank PHP
- **Frontend**: Vue, React, Angular, Svelte
- **Full-Stack**: Nuxt.js, Next.js
- **Build Tools**: Vite

#### Proje Özellikleri:

**1. Proje Oluşturma**
- Tanımlı dizinlerde tek tıkla proje oluşturma
- Otomatik proje yapılandırması
- Türkçe karakter kontrolü (URL uyumluluğu için)

**2. URL Yönetimi**
- Proje adına göre otomatik URL oluşturma
- Windows hosts dosyasına otomatik ekleme
- SSL sertifikası desteği (geliştirme amaçlı)

**3. Favori Sistemi**
- Sık kullanılan projeleri favorilere ekleme
- Ana sayfada favori projelerin hızlı erişimi

**4. Log Yönetimi**
- Her proje için ayrı Nginx logları
- Error ve access loglarını ayrı dosyalarda saklama
- Log dosyalarını görüntüleme ve temizleme

**5. Proje Silme**
- Güvenli silme onayı
- Proje dosyalarını tamamen kaldırma
- Nginx yapılandırmasını temizleme
- İlgili log dosyalarını silme

**6. Filtreleme ve Arama**
- Proje adına göre arama
- PHP/NodeJS sürümüne göre filtreleme
- Favori durumuna göre listeleme
- Path'e göre gruplama

### 🗂️ Path (Dizin) Yönetimi

Path yönetimi, farklı konumlardaki projelerinizi organize etmenizi sağlar:

**Özellikler:**
- Birden fazla proje dizini tanımlama
- Varsayılan dizin belirleme
- Dizinleri listeleme ve düzenleme
- İlk kurulumda otomatik www klasörü oluşturma

**Kullanım Senaryoları:**
- Farklı diskler veya bölümlerdeki projeleri yönetme
- Müşteri projelerini ayrı dizinlerde organize etme
- Test ve production benzeri ortamları ayırma



## 🔧 Kurulum ve Başlangıç

> **Gereksinim**: Windows 10 (64-bit) veya üzeri

### Kurulum Adımları:

1. **İndirme**
    - [Releases](https://github.com/alpzo/alpzo/releases) sayfasından en son sürümü indirin
    - `.exe` kurulum dosyasını çalıştırın

2. **Kurulum Sihirbazı**
    - Kurulum dizinini seçin
    - Başlat menüsü kısayollarını oluşturun
    - Masaüstü kısayolu ekleyin (isteğe bağlı)

3. **İlk Çalıştırma**
    - Alpzo'yu **yönetici olarak** çalıştırın
    - İlk kurulum sihirbazı açılacaktır
    - Varsayılan proje dizininizi belirleyin

## 📖 Kullanım Kılavuzu

### PHP Yönetimi

#### Yeni PHP Sürümü Ekleme:
1. Sol menüden "PHP Yönetimi" sekmesine gidin
2. "Yeni Sürüm Ekle" butonuna tıklayın
3. İstediğiniz PHP sürümünü seçin
4. "İndir ve Kur" butonuna tıklayın
5. Kurulum tamamlandıktan sonra PHP otomatik başlayacaktır

#### php.ini Düzenleme:
1. Düzenlemek istediğiniz PHP sürümünün yanındaki "Ayarlar" ikonuna tıklayın
2. "php.ini Düzenle" seçeneğini seçin
3. Görsel editörde değişikliklerinizi yapın
4. "Kaydet" butonuna tıklayın (otomatik yedekleme alınır)

### NodeJS Yönetimi

1. "NodeJS Yönetimi" sekmesine gidin
2. Mevcut sürümleri görüntüleyin
3. Yeni sürüm eklemek için "Sürüm Ekle" butonunu kullanın
4. Aktif sürümü değiştirmek için ilgili sürümü seçin

### Proje Yönetimi

#### Yeni Proje Oluşturma:
1. "Projeler" sekmesine gidin veya ana sayfadaki hızlı erişimi kullanın
2. "Yeni Proje" butonuna tıklayın
3. Proje bilgilerini girin:
    - Proje adı (Türkçe karakter kullanmayın)
    - Proje tipi (Laravel, React, vb.)
    - PHP/NodeJS sürümü
    - Proje dizini
4. "Oluştur" butonuna tıklayın

#### Proje Yönetimi İpuçları:
- Favori projelerinizi yıldız ikonuyla işaretleyin
- Filtreleme özelliklerini kullanarak projelerinizi hızlıca bulun
- Log dosyalarını düzenli kontrol edin

### Path Yönetimi

1. "Ayarlar" sayfasına gidin
2. "Path Yönetimi" bölümünü bulun
3. "Yeni Path Ekle" ile dizin ekleyin
4. Varsayılan dizini belirlemek için yıldız ikonunu kullanın

## 🔒 Güvenlik ve İzinler

### Yönetici İzinleri

Alpzo'nun bazı özellikleri Windows sistem dosyalarına erişim gerektirdiği için **yönetici olarak çalıştırılmalıdır**:

- **hosts dosyası düzenleme**: Proje URL'lerini eklemek için
- **Servis yönetimi**: MySQL ve Nginx servislerini başlatmak için
- **Port dinleme**: Web sunucusu için gerekli portları açmak için

### Güvenlik Önerileri:
1. Alpzo'yu sadece güvendiğiniz kaynaklardan indirin
2. Projelerinizi düzenli yedekleyin
3. Üretim ortamı için kullanmayın (sadece geliştirme amaçlı)

## ❓ Sıkça Sorulan Sorular

**S: Alpzo ücretsiz mi?**
C: Evet, Alpzo tamamen ücretsiz ve açık kaynaklıdır. Hiçbir gizli ücret yoktur.

**S: Mac veya Linux desteği var mı?**
C: Şu anda sadece Windows desteklenmektedir. Diğer platformlar için destek yol haritamızda bulunmaktadır.

**S: Birden fazla PHP sürümü aynı anda çalışabilir mi?**
C: Evet, her PHP sürümü farklı portlarda çalışır ve aynı anda aktif olabilir.

**S: Mevcut projelerimi Alpzo'ya aktarabilir miyim?**
C: Evet, projelerinizi tanımlı dizinlere kopyalamanız yeterlidir. Alpzo otomatik olarak algılayacaktır.

## 🗺️ Yol Haritası

### Yakında Gelecek Özellikler
- ✨ Manuel port değiştirme desteği

> **Not**: Yol haritası, geliştirme sürecine ve topluluk geri bildirimlerine göre güncellenebilir.

## 📄 Lisans

Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır. Detaylı bilgi için LICENSE dosyasına bakınız.

---

<p align="center">
  <strong>Alpzo</strong> ile geliştirme ortamınızı profesyonelce yönetin! 🚀
</p>

<p align="center">
  <a href="https://github.com/alpzo/alpzo/issues">Sorun Bildir</a> •
  <a href="https://github.com/alpzo/alpzo/discussions">Tartışmalar</a> •
  <a href="https://discord.gg/alpzo">Discord</a>
</p>
