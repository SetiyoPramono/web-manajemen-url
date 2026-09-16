# ReviewBoost (Sistem Manajemen Kartu Pintar & URL)

ReviewBoost adalah sistem manajemen tautan (URL) cerdas berbasis **CodeIgniter 4**. Sistem ini dirancang khusus untuk bisnis kartu nama digital atau kartu ulasan pintar (NFC/QR). 

Dengan ReviewBoost, Anda dapat mencetak kartu NFC/QR kosong. Saat pelanggan memindai kartu tersebut untuk pertama kalinya, mereka dapat melakukan **Aktivasi Kartu (Klaim Kartu)** secara mandiri dan sistem akan secara ajaib mengubah URL Google Maps biasa mereka menjadi **Tautan Langsung Ulasan Bintang 5**.

---

## 🌟 Fitur Utama

1. **Aktivasi Kartu Mandiri (Self-Service)**: Admin hanya perlu membuat slug URL (misal: `domain.com/tokokopi`). Klien yang menerima kartu dapat memindainya dan mendaftarkan data mereka sendiri.
2. **Auto-Generator Link Ulasan Google Maps**: Klien cukup menempelkan (*paste*) tautan Google Maps biasa mereka. Sistem akan secara otomatis mengekstrak *Place ID* dan mengubahnya menjadi tautan langsung untuk memberikan ulasan (Review Link).
3. **Dashboard Admin**:
   - Kelola ribuan klien dan URL dengan mudah.
   - Fitur *Live Search* pintar untuk mencari nama klien atau URL.
   - Indikator status (Telah Diklaim / Belum Diklaim).
4. **Dashboard Klien**: Klien dapat masuk ke akun mereka untuk memperbarui URL tujuan atau meminta bantuan *Customer Support*.
5. **Tombol Bantuan WhatsApp (Floating Button)**: Pengaturan nomor WA pusat bantuan yang dapat diatur langsung oleh Admin.

---

## 🛠️ Persyaratan Sistem (Requirements)

Sebelum melakukan instalasi, pastikan server/hosting Anda memiliki:
- **PHP** versi 8.1 atau lebih baru.
- **Ekstensi PHP**: `intl`, `mbstring`, `json`, `mysqlnd`, `curl`.
- **Database**: MySQL versi 5.7+ atau MariaDB versi 10.2+.
- **Composer** (untuk instalasi dependensi CodeIgniter 4).

---

## 🚀 Panduan Instalasi (Untuk Pemula)

Ikuti langkah-langkah di bawah ini untuk menginstal ReviewBoost di server atau komputer lokal Anda secara perlahan.

### Langkah 1: Unduh Kode & Instal Dependensi
1. Unduh (*Download*) atau *Clone* repositori ini ke komputer/server Anda.
2. Buka terminal (atau Command Prompt / PowerShell) dan masuk ke dalam folder proyek ini.
3. Jalankan perintah berikut untuk mengunduh semua sistem bawaan CodeIgniter:
   ```bash
   composer install
   ```
   *(Tunggu hingga proses instalasi selesai).*

### Langkah 2: Pengaturan Database
1. Buka aplikasi database Anda (seperti **phpMyAdmin**).
2. Buat database baru, misalnya dengan nama `reviewboost_db`.
3. Buka folder proyek ini, cari file bernama `env`.
4. Ubah nama file `env` menjadi `.env` (titik di depan).
5. Buka file `.env` menggunakan teks editor (seperti Notepad), lalu cari dan ubah pengaturan berikut:
   
   **Mengaktifkan Mode Development (Saat masih uji coba)**:
   ```env
   CI_ENVIRONMENT = development
   ```
   *(Ubah menjadi `production` jika sudah online/dijual ke publik).*

   **Mengatur URL Website**:
   ```env
   app.baseURL = 'http://localhost:8080/'
   ```
   *(Ganti dengan nama domain Anda jika dihosting, contoh: `https://kartuku.com/`)*

   **Menyambungkan Database**:
   ```env
   database.default.hostname = localhost
   database.default.database = reviewboost_db
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```
   *(Sesuaikan `username` dan `password` dengan pengaturan database hosting Anda).*

### Langkah 3: Membuat Tabel Database (Migrasi & Seeding)
Setelah database tersambung, Anda tidak perlu mengimpor SQL secara manual. Cukup ketikkan dua perintah sakti ini di terminal Anda (pastikan Anda berada di folder proyek):

1. **Membuat tabel otomatis**:
   ```bash
   php spark migrate
   ```
2. **Membuat akun Admin pertama kali**:
   ```bash
   php spark db:seed AdminSeeder
   ```

### Langkah 4: Selesai! Menjalankan Website
Jika Anda menjalankan di komputer lokal, ketik perintah ini untuk menyalakan server:
```bash
php spark serve
```
Website Anda sudah bisa diakses di `http://localhost:8080/`.

---

## 🔑 Akun Default Admin

Setelah instalasi selesai (dan menjalankan `AdminSeeder`), Anda dapat masuk ke Dashboard Admin menggunakan data berikut:

- **Halaman Login**: `http://localhost:8080/login`
- **Username**: `admin`
- **Password**: `admin123`

*(Sangat disarankan untuk segera mengganti password admin setelah berhasil masuk).*

---

## 🌐 Cara Upload ke Web Hosting (CPanel / CyberPanel)

Jika Anda ingin meng-online-kan website ini ke internet (Hosting asli), ikuti aturan penting CodeIgniter 4 berikut:

1. **Jangan arahkan domain ke folder utama**. Arahkan *Document Root* dari domain Anda langsung menunjuk ke folder `public`.
   - **Salah**: `/home/user/public_html/` (Jika file diletakkan di sini, sistem tidak aman).
   - **Benar**: `/home/user/public_html/public/`
2. Pastikan file `.env` sudah diatur menjadi `CI_ENVIRONMENT = production` agar pesan error sistem (jejak error) tidak terlihat oleh pengunjung biasa demi alasan keamanan.
3. Pastikan ekstensi `mod_rewrite` aktif di server Apache agar URL bersih tanpa `index.php` dapat bekerja (sudah ada file `.htaccess` bawaan di dalam folder `public`).

---

## 💡 Alur Penggunaan Aplikasi (Cara Kerja)

**Sebagai Admin:**
1. Login ke Dashboard Admin.
2. Buat URL baru, misal ketik slug: `kopisenja`.
3. Cetak URL `domain.com/kopisenja` ke dalam stiker NFC atau QR Code, lalu serahkan ke pelanggan Anda.
4. Di tabel Admin, statusnya akan terlihat **Belum Diklaim**.

**Sebagai Pelanggan / Klien (Pemilik Kartu):**
1. Saat Klien memindai kartu (NFC/QR) untuk pertama kalinya, mereka akan otomatis diarahkan ke halaman **Aktivasi Kartu**.
2. Klien mengisi Username Login, Password, Nama Bisnis, No WA, dan **Tautan Google Maps** mereka yang biasa.
3. Setelah klik Simpan, Klien akan langsung memiliki akun, dan sistem *secara ajaib* telah mengubah tautan Google Maps tersebut menjadi Tautan Ulasan (Review Link) resmi.
4. Mulai saat ini, siapa pun yang menempelkan kartu (NFC) tersebut, akan langsung diarahkan ke Halaman Ulasan Google Maps Klien!

---
*Dikembangkan secara khusus dan eksklusif. Hak Cipta dilindungi.*
