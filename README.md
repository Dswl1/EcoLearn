# EcoLearn 🌱

Platform pembelajaran interaktif berbasis **UN Sustainable Development Goals (SDGs)**. Dibangun dengan **Laravel 11**, **Breeze** (Blade + Alpine.js + Tailwind CSS 3), dan **SQLite/MySQL**.

## Fitur

- 📚 Manajemen konten edukasi (CRUD oleh admin)
- 🎴 Flashcards interaktif
- 📝 Kuis dengan penilaian otomatis
- 📊 Pelacakan progres belajar
- 👥 Autentikasi pengguna (admin & user)
- 🌐 Dukungan multi-bahasa (EN/ID)
- 🎨 Tema dark modern

## Persyaratan Sistem

- PHP ^8.2
- [Composer](https://getcomposer.org/)
- Node.js & npm
- SQLite (bawaan) atau MySQL

## Cara Clone & Setup

### 1. Clone Repository

```bash
git clone https://github.com/<username>/EcoLearn.git
cd EcoLearn
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan `.env` bila perlu (default sudah pakai SQLite).

### 4. Database & Seeder

```bash
# Buat file database SQLite (lewatkan jika pakai MySQL)
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"

# Migrasi + seed
php artisan migrate --seed
```

Seeder akan membuat:
- **Admin** — `admin123@gmail.com` / `admin123`
- **User** — `user@example.com` / `password`
- **5 kursus SDG** lengkap dengan flashcards & kuis
- **Data konten edukasi**

### 5. Build Frontend

```bash
npm run build
```

### 6. Jalankan Aplikasi

**Semua server sekaligus (recommended):**

```bash
composer dev
```

Atau jalankan manual:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Queue worker
php artisan queue:listen --tries=1

# Terminal 3 - Log viewer
php artisan pail --timeout=0

# Terminal 4 - Vite dev
npm run dev
```

Akses di `http://localhost:8000`.

## Testing

```bash
./vendor/bin/pest
./vendor/bin/pest --filter NamaTest
```

## Perintah Penting

| Perintah | Keterangan |
|---|---|
| `composer dev` | Jalankan dev server + queue + logs + Vite |
| `npm run dev` | Vite hot-reload |
| `npm run build` | Build production frontend |
| `php artisan migrate` | Jalankan migrasi database |
| `php artisan db:seed` | Isi data awal |
| `php artisan queue:listen` | Proses antrian |
| `php artisan pail` | Lihat log real-time |
| `./vendor/bin/pint` | Linting kode |

## Stack Teknologi

- **Laravel 11** — Backend framework
- **Laravel Breeze** — Autentikasi (Blade + Alpine)
- **Alpine.js** — Interaktivitas frontend
- **Tailwind CSS 3** — Styling (dark theme kustom)
- **Vite** — Build tool frontend
- **Pest** — Testing
- **SQLite / MySQL** — Database
- **SweetAlert2** — Notifikasi
- **Material Symbols** — Ikon

## Lisensi

MIT
