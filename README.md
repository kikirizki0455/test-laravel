📚 Laravel Library API

API sederhana untuk sistem peminjaman buku menggunakan Laravel 11 dengan fitur authentication, search & filter, queue email notification, testing, dan dokumentasi API (Swagger).

🚀 Features

Authentication: Laravel Sanctum (register, login, logout).

Book Management: Tambah, update, hapus, list buku.

Search & Filter: Cari buku berdasarkan author, year, atau kata kunci title.

Borrowing System: User dapat meminjam buku. Jika stok habis → gagal.

Queue & Job: Mengirim email notifikasi (via log driver).

Seeder: Generate data dummy (10 user, 30 buku).

API Resource: Response data konsisten dengan Laravel Resource.

Testing: Unit & Feature Test untuk:

Tambah buku.

Pinjam buku.

Tidak bisa pinjam jika stok habis.

API Docs: Swagger (OpenAPI).

🛠️ Tech Stack

Laravel 11

Laravel Sanctum

MySQL
 / SQLite (testing)

L5-Swagger

Railway
 (Deploy)

📦 Installation

Clone repository:

git clone https://github.com/username/library-api.git
cd library-api


Install dependencies:

composer install
cp .env.example .env
php artisan key:generate


Atur database di .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=


Migrasi & seed:

php artisan migrate --seed


Jalankan server:

php artisan serve

🔑 Authentication (Sanctum)

Register: POST /api/register

Login: POST /api/login → return token

Logout: POST /api/logout

Gunakan token untuk akses endpoint lain.

📖 API Endpoints
Books

GET /api/books → list buku (support filter & search).

POST /api/books → tambah buku (admin only).

PUT /api/books/{id} → update buku.

DELETE /api/books/{id} → hapus buku.

Borrowing

POST /api/borrow/{book_id} → pinjam buku.

✉️ Queue & Job

Saat user meminjam buku, sistem mengirim email notifikasi ke log:

tail -f storage/logs/laravel.log

🧪 Testing

Jalankan test:

php artisan test


Unit Test:

Tambah buku.

Pinjam buku.

Tidak bisa pinjam jika stok habis.

📑 API Documentation

Swagger tersedia di:

/api/documentation


Generate ulang docs:

php artisan l5-swagger:generate
