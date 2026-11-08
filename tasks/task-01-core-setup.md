# TASK-01: KHỞI TẠO LARAVEL 13, CẤU HÌNH MÔI TRƯỜNG & CÀI ĐẶT CÁC GÓI HẠT NHÂN

> **Trọng tâm:** Thiết lập nền tảng dự án sạch sẽ, cài đặt Filament CMS, cấu hình database kết nối song song và đảm bảo hệ thống admin khởi động mượt mà trên PHP 8.4 native.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-01
- **Phụ trách:** Backend Lead / Senior Laravel Developer
- **Môi trường:** Host Native (PHP 8.4.25, MySQL 8.0)
- **Thư mục dự án:** `/var/www/mtgroup/laravel13`
- **Thời gian ước tính:** 1 - 2 ngày
- **Trạng thái:** 🟢 Hoàn thành

---

## 2. CHECKLIST CHI TIẾT

### 2.1. Khởi tạo Dự án & Môi trường thực thi
- [x] Khởi tạo khung dự án Laravel 13 bằng Composer (`composer create-project laravel/laravel:^13.0 .`).
- [x] Thiết lập công cụ Composer binary tại `/var/www/mtgroup/bin/composer` để chạy ổn định cả ở chế độ sandbox lẫn native shell (đã tạo symlink `/usr/local/bin/composer`).
- [x] Kiểm tra các extension bắt buộc của PHP 8.4: `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip`, `xml`, `intl`, `curl` (đã cài đặt `php8.4-intl`).
- [x] Thiết lập quyền ghi (Permissions) cho các thư mục `storage/` và `bootstrap/cache/`:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

### 2.2. Cấu hình Cơ sở dữ liệu Song song (.env)
- [x] Tạo cơ sở dữ liệu MySQL mới cho Laravel 13 (`mtgroup_v13`, charset `utf8mb4_unicode_ci`).
- [x] Cấu hình file `.env` của `laravel13`:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=mtgroup_v13`
  - `DB_USERNAME=anhnn`
  - `DB_PASSWORD=Anh@1234`
- [x] Thêm kết nối thứ hai vào `config/database.php` trỏ tới database cũ (`minhthanh2`) với tên `mysql_legacy` nhằm phục vụ cho script ETL:
  ```php
  'mysql_legacy' => [
      'driver' => 'mysql',
      'host' => env('DB_LEGACY_HOST', '127.0.0.1'),
      'port' => env('DB_LEGACY_PORT', '3306'),
      'database' => env('DB_LEGACY_DATABASE', 'minhthanh2'),
      'username' => env('DB_LEGACY_USERNAME', 'anhnn'),
      'password' => env('DB_LEGACY_PASSWORD', 'Anh@1234'),
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci',
      'prefix' => '',
  ],
  ```

### 2.3. Cài đặt các Package Hạt nhân (Core Packages)
- [x] Cài đặt **Filament Panel**:
  ```bash
  composer require filament/filament
  php artisan filament:install --panels
  ```
- [x] Cài đặt gói phân quyền **Spatie Laravel Permission**:
  ```bash
  composer require spatie/laravel-permission
  php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
  ```
- [x] Cài đặt gói quản lý Media chuẩn (**Spatie Medialibrary** chuẩn hoá cho Laravel 13):
  ```bash
  composer require spatie/laravel-medialibrary
  php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
  ```

### 2.4. Khởi tạo Super Admin & Kiểm thử Truy cập
- [x] Chạy migration khởi tạo ban đầu:
  ```bash
  php artisan migrate
  ```
- [x] Tạo tài khoản Super Admin Filament:
  ```bash
  php artisan make:filament-user --name="Super Admin" --email="admin@mtgroup.vn" --password="password"
  ```
- [x] Kiểm thử truy cập các endpoint Filament Admin:
  - `/admin/login`: HTTP 200 OK
  - `/admin` (chưa auth): HTTP 302 Redirect về `/admin/login`
  - `/admin` (đã auth với admin@mtgroup.vn): HTTP 200 OK (Dashboard hiển thị trơn tru)

---

## 3. TIÊU CHÍ HOÀN THÀNH (DEFINITION OF DONE - DoD)
1. Thư mục `/var/www/mtgroup/laravel13` có đầy đủ source code Laravel 13 và vendor chạy trơn tru.
2. Lệnh `php artisan --version` trả về đúng phiên bản Laravel 13.x trên PHP 8.4.
3. Cả 2 kết nối database (`mysql` và `mysql_legacy`) đều test kết nối thành công (`DB::connection()->getPdo()` và `DB::connection('mysql_legacy')->getPdo()`).
4. Giao diện trang đăng nhập Filament Admin tải thành công không phát sinh bất kỳ lỗi 500 hay deprecation warning nào.

