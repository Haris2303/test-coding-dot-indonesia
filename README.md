<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Test Coding DOT Indonesia

Membuat project restful API menggunakan Laravel yang dapat me-management 2 tabel dengan relasi one to many

## Prasyarat

Pastikan Anda telah menginstal [PHP](https://www.php.net/) dan [Composer](https://getcomposer.org/) di komputer Anda.

## Instalasi

1. Clone repositori ini:

    ```bash
    git clone https://github.com/haris2303/test-coding-dot-indonesia.git
    ```

2. Masuk ke direktori proyek:
    ```bash
    cd nama-proyek
    ```
3. Install dependensi menggunakan composer:
    ```bash
    composer install
    ```
4. Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
5. Sesuaikan konfigurasi database di file `.env`.
6. Jalankan migrasi untuk membuat tabel-tabel database:
    ```
    php artisan migrate
    ```
