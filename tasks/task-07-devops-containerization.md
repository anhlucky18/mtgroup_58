# TASK-07: ĐÓNG GÓI DOCKER & CHUẨN HÓA DEVOPS (POST-MIGRATION PHASE)

> **Trọng tâm:** Giai đoạn chuẩn hóa hạ tầng sau khi hệ thống Laravel 13 đã Go-Live và vận hành ổn định trên máy chủ. Đóng gói mã nguồn thành Docker container độc lập, chuẩn hóa môi trường triển khai (CI/CD) và thiết lập cơ chế sao lưu tự động định kỳ.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-07
- **Phụ trách:** DevOps Engineer
- **Giai đoạn:** Post-migration (Thực hiện sau khi hệ thống mới vận hành ổn định)
- **Thời gian ước tính:** 1 - 2 ngày
- **Trạng thái:** ⚪ Dự kiến

---

## 2. CHECKLIST CHI TIẾT

### 2.1. Đóng gói Dockerfile Chuẩn Production
- [ ] Viết `Dockerfile` cho PHP 8.4 FPM:
  - Multi-stage build (tách biệt build dependencies và production runtime).
  - Cài đặt đầy đủ các PHP extensions: `pdo_mysql`, `mbstring`, `gd`, `zip`, `xml`, `opcache`, `intl`.
  - Cấu hình file `php.ini` tối ưu cho production (`upload_max_filesize = 64M`, `post_max_size = 64M`, `memory_limit = 512M`, `opcache.validate_timestamps = 0`).
  - Thiết lập user không quyền root (`www-data`) để đảm bảo an toàn bảo mật.

### 2.2. Chuẩn hóa `docker-compose.yml`
- [ ] Xây dựng file compose tối ưu:
  - Service `app`: PHP 8.4 FPM container.
  - Service `web`: Nginx Alpine container, mount volumes tĩnh và cấu hình caching static assets.
  - Service `db`: MySQL 8.0 container (hoặc cấu hình dùng RDS bên ngoài).
- [ ] Đảm bảo chỉ expose port 80/443 ra ngoài Internet, port 3306 chỉ mở trong internal Docker network.

### 2.3. Quy trình Triển khai Tự động (CI/CD Pipeline)
- [ ] Viết workflow GitHub Actions / GitLab CI:
  - Tự động chạy Unit Test / Feature Test: `php artisan test`.
  - Tự động lint code: `vendor/bin/pint --test`.
  - Tự động build Docker Image và đẩy lên Docker Registry.
  - Tự động SSH vào production server pull image và reload service với Zero Downtime.

### 2.4. Kế hoạch Sao lưu Tự động (Automated Backup)
- [ ] Thiết lập cronjob sao lưu hàng ngày:
  - Dump database MySQL (`mysqldump`) nén định dạng `.sql.gz`.
  - Đồng bộ file media `public/storage` lên Cloud Storage (Amazon S3, Google Cloud Storage, hoặc backup server phụ).
  - Tự động xóa các bản sao lưu cũ hơn 30 ngày để tiết kiệm dung lượng đĩa cứng.

---

## 3. TIÊU CHÍ HOÀN THÀNH (DoD)
1. Lệnh `docker compose up -d --build` khởi chạy thành công toàn bộ hệ thống trên server mới.
2. Ứng dụng chạy mượt mà bên trong container với hiệu năng tương đương chạy native.
3. Bản sao lưu database tự động được tạo và kiểm tra khôi phục thử nghiệm thành công (Disaster Recovery Test).

