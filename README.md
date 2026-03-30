 🌦️ PANTAU CUACA

 📌 Deskripsi
Pantau Cuaca adalah aplikasi web berbasis Laravel yang digunakan untuk memantau kondisi cuaca secara real-time di berbagai ibu kota provinsi di Indonesia. Aplikasi ini memanfaatkan layanan API cuaca eksternal untuk menampilkan informasi terkini seperti suhu, kondisi cuaca, dan kelembaban udara.

Aplikasi ini dirancang sebagai solusi sederhana untuk membantu pengguna mendapatkan informasi cuaca secara cepat, akurat, dan mudah diakses melalui web.


 �️ Screenshot Aplikasi

![Screenshot Pantau Cuaca](./public/image/CUACA1.png)

📌 Sudah ada file screenshot di `public/image/CUACA1.png`. Jika ingin ganti, pakai nama file lain yang sama.


 �🚀 Fitur Utama

* 🌍 Menampilkan cuaca di beberapa kota (ibu kota provinsi)
* 🌡️ Informasi suhu secara real-time
* 🌤️ Kondisi cuaca (cerah, hujan, berawan, dll.)
* 💧 Kelembaban udara
* 🔄 Update data otomatis dari API cuaca
* 📱 Tampilan responsif (mobile-friendly)


 🛠️ Teknologi yang Digunakan

* **Backend**: Laravel 11
* **Frontend**: Blade Template, Bootstrap, Vite
* **HTTP Client**: Laravel HTTP (API Integration)
* **Database**: SQLite (default, opsional)
* **API Cuaca**: WeatherAPI


 ⚙️ Instalasi

 1. Clone Repository

```bash
git clone https://github.com/AwaludinMajid/pantau-cuaca.git
cd pantau-cuaca



 2. Install Dependency

```bash
composer install
npm install



 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate



 4. Konfigurasi API Key

Daftar di:
👉 [https://www.weatherapi.com/](https://www.weatherapi.com/)

Lalu masukkan API key ke file `.env`:

```env
WEATHER_API_KEY=your_api_key_here
```



 5. Konfigurasi Database (Opsional)

```bash
php artisan migrate




 6. Build Frontend

```bash
npm run build


Untuk development:

```bash
npm run dev


---

### 7. Jalankan Aplikasi

#### 🚀 **Opsi 1: Server Stabil (Direkomendasikan)**
```bash
# Menggunakan PHP built-in server (lebih stabil)
php -S 127.0.0.1:8000 -t public
```

#### 🚀 **Opsi 2: Laravel Artisan (Development)**
```bash
php artisan serve
```

#### 🚀 **Opsi 3: Auto-Restart Server (Windows)**
```bash
# Jalankan script PowerShell untuk auto-restart
.\start_server.ps1
```
Atau klik file `start_server.bat`

#### 🚀 **Opsi 4: Menggunakan Web Server (Production)**
- **Apache/Nginx**: Konfigurasi virtual host
- **Docker**: Gunakan Laravel Sail
- **Laragon**: Import project ke Laragon

---

### 📡 Akses Aplikasi
Buka browser dan akses:
```
http://127.0.0.1:8000
```

### 🔧 Troubleshooting Server

#### ❌ "Can't reach this page" / Server sering mati?
**Solusi:**

1. **Gunakan PHP Built-in Server:**
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

2. **Auto-Restart Script:**
   Jalankan `start_server.ps1` untuk server yang auto-restart saat crash

3. **Cek Port Conflict:**
   ```bash
   netstat -ano | findstr :8000
   ```

4. **Increase Memory Limit:**
   Edit `php.ini`:
   ```ini
   memory_limit = 512M
   max_execution_time = 300
   ```

5. **Gunakan Web Server:**
   - **Laragon**: Import project
   - **XAMPP**: Konfigurasi Apache
   - **Docker**: `composer require laravel/sail`

#### ⚡ Tips Optimasi:
- Clear cache: `php artisan view:clear`
- Restart server setiap perubahan signifikan
- Monitor memory usage saat development




🧪 Testing

bash
php artisan test




📂 Struktur Proyek


app/
 └── Http/
     └── Controllers/
         └── WeatherController.php

resources/
 └── views/
     └── dashboard.blade.php

routes/
 └── web.php
```



📈 Pengembangan Selanjutnya

* 🌍 Menambahkan seluruh ibu kota provinsi di Indonesia
* 🔍 Fitur pencarian kota
* 📊 Grafik perubahan cuaca
* 🔄 Auto refresh tanpa reload (AJAX)
* 🌐 Integrasi API BMKG





