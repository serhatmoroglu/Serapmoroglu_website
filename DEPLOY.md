# Yayına alma kılavuzu (İHS paylaşımlı hosting)

Bu kılavuz, siteyi İHS panelinden **kod bilmeden** yayına almak için yazıldı. Eski WordPress siteniz bu sürede olduğu gibi çalışmaya devam eder.

## Gerekenler
- İHS panelinde **PHP 8.3 veya üzeri** seçilebilmeli (Panel → *PHP Ayarları*).
- Bir MySQL veritabanı (Panel → *Mysql Yönetimi*).
- Hazır paket: `serapozdamar-site.zip` (bu paketi Claude hazırlar ve size verir).

## A) Önce deneme adresinde yayınlayın (önerilir)
1. **Subdomain oluşturun:** Panel → *Subdomain Yönetimi* → `yeni` adında bir alt alan adı ekleyin (`yeni.drserapozdamar.com`). Bu alt alan adının **klasörünü** not edin (örn. `yeni`).
2. **PHP sürümünü ayarlayın:** Panel → *PHP Ayarları* → 8.3 (veya üstü) seçin.
3. **Veritabanı oluşturun:** Panel → *Mysql Yönetimi* → yeni veritabanı + kullanıcı. Üç bilgiyi bir yere yazın: **veritabanı adı, kullanıcı adı, şifre**. (Eski WordPress veritabanına dokunmayın.)
4. **Dosyaları yükleyin:** Panel → *Dosya Yönetimi* → `yeni` klasörünü açın → *Yükle* ile `serapozdamar-site.zip` dosyasını yükleyin → zip'e sağ tıklayıp **Aç / Extract** deyin. Zip silinebilir.
   - Klasörün içinde `index.php`, `.htaccess`, `css`, `js`, `uploads` ve `laravel` görmelisiniz.
   - `.htaccess` gizli dosyadır: dosya yöneticisinde *gizli dosyaları göster* seçeneğini açın.
5. **Ayar dosyasını düzenleyin:** `laravel/.env` dosyasını açıp şu 3 satırı kendi bilgilerinizle değiştirin, kaydedin:
   ```
   DB_DATABASE=...   DB_USERNAME=...   DB_PASSWORD=...
   APP_URL=https://yeni.drserapozdamar.com
   ```
6. **Kurulumu çalıştırın:** tarayıcıda şu adresi açın (anahtarı `laravel/.env` içindeki `INSTALL_TOKEN` değerinden alın):
   ```
   https://yeni.drserapozdamar.com/kur?token=INSTALL_TOKEN_DEGERI
   ```
   "Kurulum tamamlandı" yazısını ve **geçici yönetici şifresini** göreceksiniz. Şifreyi not edin, bir daha gösterilmez.
7. **Yönetim paneline girin:** `https://yeni.drserapozdamar.com/yonetim` → e-posta: `serhatmorogluis@gmail.com` + geçici şifre. İlk girişte yeni şifre belirlemeniz istenir.
8. **E-posta ayarını yapın:** Panel → *Bildirim ve e-posta (SMTP)* sayfasındaki adımları izleyin, **Test e-postası gönder** ile deneyin.
9. Siteyi gezin, bir test talebi gönderin, panelde göründüğünü ve e-postanın geldiğini kontrol edin.

## B) Ana alan adına geçiş
Deneme adresinde her şeyden memnun kaldıktan sonra:
1. Eski WordPress dosyalarının **yedeğini alın** (bkz. aşağıda).
2. Ana alan adının klasörüne (genelde `public_html`) eski WordPress dosyalarını bir `eski-wordpress` klasörüne taşıyın.
3. Yeni paketi aynı şekilde `public_html` içine yükleyip açın, `laravel/.env` içinde `APP_URL=https://drserapozdamar.com` yapın.
4. **Aynı veritabanı** kullanılacaksa kurulumu yeniden çalıştırmanıza gerek yoktur; yeni bir veritabanı için `/kur` adresini yeniden açın (yeni bir `INSTALL_TOKEN` gerekir).
5. Eski sayfa adresleri (`?page_id=...`) otomatik yeni adreslere yönlenir; Google sıralaması korunur.

## Güvenlik notları
- `laravel` klasörü içinde şifreler bulunur. Pakette bu klasör `.htaccess` ile dışarıya kapalıdır. Daha da güvenlisi, dosya yöneticisi izin veriyorsa `laravel` klasörünü **web kökünün bir üstüne** taşımaktır (`public_html` ile yan yana); site otomatik olarak orayı bulur.
- Kurulum bittikten sonra `.env` içindeki `INSTALL_TOKEN` satırını silin.
- Yönetim şifrenizi kimseyle paylaşmayın. Yönetim paneli: `/yonetim`.

## Eski WordPress yedeği
- Eklenti ile: *Eklentiler → Yeni ekle → UpdraftPlus → Şimdi yedekle*, ardından her parçayı indirin.
- Panelden: *Mysql Yönetimi → phpMyAdmin → Dışa Aktar (SQL)* ve *Dosya Yönetimi → wp-content → Sıkıştır → İndir*.
- Yedek dosyalarını GitHub'a **yüklemeyin**, kişisel veri içerir.

## Güncelleme
Kod değiştiğinde yeni bir zip hazırlanır. Yeni zip'i aynı klasöre açarken `laravel/.env` ve `uploads` klasörünü **üzerine yazmayın** (içerik ve ayarlar veritabanında ve bu klasörlerde tutulur).
