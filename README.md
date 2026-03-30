# PANTAU CUACA

Aplikasi web sederhana untuk memantau cuaca di berbagai kota menggunakan Laravel dan API cuaca eksternal.

## Fitur

- 🏠 Halaman utama dengan informasi cuaca umum
- 📊 Dashboard untuk monitoring cuaca
- 🌤️ Detail cuaca per kota (Jakarta, dll.)
- 🔄 Update cuaca real-time menggunakan API
- 📱 Responsive design dengan Bootstrap

## Tech Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Bootstrap, Vite
- **Database**: SQLite (default)
- **API**: Weather API (memerlukan API key)

## Instalasi

1. **Clone repository**:
   ```bash
   git clone https://github.com/your-username/pantau-cuaca.git
   cd pantau-cuaca
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi API Key**:
   - Daftar di [Weather API Provider](https://example.com) untuk mendapatkan API key
   - Masukkan API key ke `.env`:
     ```
     WEATHER_API_KEY=your_api_key_here
     ```

5. **Setup database** (opsional, default SQLite):
   ```bash
   php artisan migrate
   ```

6. **Build assets**:
   ```bash
   npm run build
   # atau untuk development:
   npm run dev
   ```

7. **Jalankan aplikasi**:
   ```bash
   php artisan serve
   ```

   Akses di: `http://localhost:8000`

## Testing

Jalankan test dengan:
```bash
php artisan test
```

## Struktur Proyek

- `app/Http/Controllers/WeatherController.php` - Controller untuk logika cuaca
- `resources/views/` - Template Blade
- `routes/web.php` - Routing aplikasi
- `config/` - Konfigurasi Laravel

## Kontribusi

Feel free to fork dan contribute! 🚀

## Lisensi

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
