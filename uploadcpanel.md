# Panduan Super Praktis Deploy CodeIgniter 4 ke cPanel

Panduan ini dibuat sedetail mungkin agar Anda bisa langsung praktik tanpa kebingungan. Kita akan menggunakan **Metode Aman**, yaitu memisahkan file sistem (core) agar tidak bisa diakses publik.

---

## TAHAP 1: Persiapan di Komputer Lokal (Laptop/PC Anda)

### 1. Sesuaikan File `.env`
Buka file `.env` di VSCode/editor Anda dan ubah bagian ini:
```env
# Ubah menjadi production
CI_ENVIRONMENT = production

# Sesuaikan dengan domain Anda (PENTING: gunakan https:// jika sudah ada SSL)
app.baseURL = 'https://domain-anda.com/'
app.forceGlobalSecureRequests = true 

# Nanti kita isi ini di Tahap 3, biarkan dulu tidak apa-apa atau isi sementara
database.default.hostname = localhost
database.default.database = nama_database_cpanel
database.default.username = nama_user_cpanel
database.default.password = password_database_cpanel
```

### 2. Export Database Lokal
1. Buka browser, akses `http://localhost/phpmyadmin`
2. Klik database **`url_manager`** di sebelah kiri.
3. Klik menu/tab **Export** di bagian atas.
4. Klik tombol **Export** (atau Go) di bawah. File `url_manager.sql` akan terdownload.

### 3. Membungkus (Zip) File Proyek
Kita akan membuat **DUA file ZIP** agar mudah di-upload.
1. Buka folder proyek Anda di Windows Explorer (folder: `Web Manajemen URL`).
2. **ZIP PERTAMA (Isi Publik):**
   - Masuk ke dalam folder `public`.
   - Blok/Pilih **semua file** di dalamnya (termasuk `index.php`, `.htaccess`, dsb).
   - Klik Kanan -> *Send to* -> *Compressed (zipped) folder*.
   - Beri nama file zip tersebut **`isi_public_html.zip`**. Pindahkan file zip ini ke Desktop agar mudah dicari.
3. **ZIP KEDUA (Sistem Inti):**
   - Kembali ke folder utama proyek Anda.
   - Blok/Pilih **SEMUA FOLDER & FILE** (seperti `app`, `vendor`, `writable`, `.env`, dll).
   - **KECUALI** folder `public` (JANGAN DIPILIH/di-uncheck).
   - Klik Kanan -> *Send to* -> *Compressed (zipped) folder*.
   - Beri nama **`sistem_ci4.zip`**. Pindahkan juga ke Desktop.

---

## TAHAP 2: Upload ke cPanel

1. Login ke cPanel hosting Anda.
2. Cari dan klik menu **File Manager**.

### 1. Upload Sistem Inti (File Core)
1. Pastikan Anda berada di halaman awal File Manager (biasanya ada gambar rumah / tulisan `home`). Anda **TIDAK BOLEH** berada di dalam folder `public_html`.
2. Klik tombol **+ Folder** (di kiri/atas). Beri nama folder baru: **`aplikasi_url`**.
3. Masuk/klik dua kali ke folder **`aplikasi_url`** tersebut.
4. Klik tombol **Upload** di bagian atas.
5. Upload file **`sistem_ci4.zip`** dari Desktop Anda.
6. Setelah 100%, kembali ke File Manager.
7. Klik Kanan pada `sistem_ci4.zip` -> pilih **Extract**. Klik *Extract Files*.

### 2. Upload Isi Publik (Tampilan Web)
1. Di File Manager, masuk ke folder **`public_html`**.
2. Klik tombol **Upload**.
3. Upload file **`isi_public_html.zip`** dari Desktop Anda.
4. Setelah 100%, kembali, lalu Klik Kanan pada `isi_public_html.zip` -> pilih **Extract**. Klik *Extract Files*.

---

## TAHAP 3: Menghubungkan Keduanya (Sangat Penting)

Karena kita memisahkan file sistem dan file publik, kita harus memberi tahu file publik di mana letak sistemnya.

1. Masih di dalam folder **`public_html`**, cari file **`index.php`**.
2. Klik Kanan pada `index.php` -> pilih **Edit**.
3. Cari baris ini (sekitar baris 34):
   ```php
   $pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
   ```
4. Ubah menjadi (sesuaikan dengan nama folder yang kita buat di Tahap 2 langkah 2):
   ```php
   $pathsPath = realpath(FCPATH . '../aplikasi_url/app/Config/Paths.php');
   ```
5. Klik **Save Changes** di pojok kanan atas. Tutup editor.

---

## TAHAP 4: Database & Versi PHP cPanel

### 1. Atur Versi PHP
1. Kembali ke halaman utama (Dashboard) cPanel.
2. Cari menu **Select PHP Version** (atau **MultiPHP Manager**).
3. Pastikan versi PHP untuk domain Anda diatur ke **PHP 8.2** atau **PHP 8.3**.

### 2. Buat Database cPanel
1. Di halaman utama cPanel, klik **MySQL Databases**.
2. Di bagian *Create New Database*, masukkan nama (misal: `urlku`), klik Create. (Nama lengkap akan jadi sesuatu seperti `namahost_urlku`). Catat nama ini.
3. Scroll ke bawah ke bagian *Add New User*. Masukkan nama user (misal: `admin`) dan password yang kuat. Klik Create. Catat nama user & password ini.
4. Scroll ke bawah lagi ke bagian *Add User To Database*. Pilih User dan Database yang baru dibuat tadi. Klik **Add**.
5. Centang **ALL PRIVILEGES**. Klik **Make Changes**.

### 3. Import Database & Update `.env`
1. Ke halaman utama cPanel, buka **phpMyAdmin**.
2. Klik nama database cPanel Anda di sebelah kiri.
3. Klik tab **Import** di atas. Pilih file `url_manager.sql` (dari Tahap 1 Langkah 2) di komputer Anda, klik **Go**.
4. Terakhir, buka **File Manager** cPanel lagi. Masuk ke folder **`aplikasi_url`**.
5. Klik kanan file **`.env`** -> pilih **Edit**.
6. Masukkan detail database yang baru saja Anda catat ke bagian ini:
   ```env
   database.default.hostname = localhost
   database.default.database = namahost_urlku
   database.default.username = namahost_admin
   database.default.password = password_database_anda_tadi
   ```
7. Klik **Save Changes**.

---

**SELESAI!**
Sekarang Anda tinggal membuka nama domain Anda di browser. Web Manajemen URL Anda sudah online dengan aman!
