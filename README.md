# 📚 Library API (Laravel + Sanctum)

API sederhana untuk manajemen buku dan peminjaman menggunakan Laravel 11, Sanctum untuk autentikasi, dan Queue Job untuk notifikasi email.

---

## ✨ Fitur

-   🔑 Authentication (Register & Login dengan Sanctum)
-   📖 CRUD Buku
-   🔍 Search & Filter (by title, author, published_year)
-   📦 Peminjaman & Pengembalian Buku
-   📬 Queue Job untuk kirim notifikasi email (via log driver)
-   🧪 Feature & Unit Test
-   📑 Swagger API Docs

---

## 🛠️ Tech Stack

-   PHP 8.2
-   Laravel 11
-   MySQL
-   Laravel Sanctum
-   Laravel Queue (log driver untuk testing)

---

## 🚀 Instalasi

```bash
# clone repo
git clone <repo-url>
cd project-folder

# install dependencies
composer install

# copy env
cp .env.example .env

# generate key
php artisan key:generate

# migrate + seed
php artisan migrate --seed

# jalankan server
php artisan serve
```

🔑 Authentication

Register → POST /api/register

Login → POST /api/login → dapatkan token Sanctum.

📌 API Endpoints

Method Endpoint Deskripsi

~GET /api/books List semua buku
~POST /api/books Tambah buku
~PUT /api/books/{id} Update buku
~DELETE /api/books/{id} Hapus buku
~POST /api/loans Pinjam buku
~PUT /api/loans/{id}/return Kembalikan buku

Testing

Jalankan semua test dengan:

php artisan test
