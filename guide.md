# Hướng Dẫn Triển Khai Hệ Thống Docker Lên Server Production

Tài liệu này hướng dẫn chi tiết quy trình đưa ứng dụng Laravel 5.8 / Botble CMS cùng stack Docker (PHP 7.3 FPM, Nginx, MySQL 8.0) từ môi trường local lên server Production (VPS / Cloud Server như DigitalOcean, Linode, AWS EC2, v.v.).

---

## MỤC LỤC
1. [Chuẩn bị Server Production](#1-chuẩn-bị-server-production)
2. [Đưa mã nguồn lên Server](#2-đưa-mã-nguồn-lên-server)
3. [Điều chỉnh cấu hình cho Production](#3-điều-chỉnh-cấu-hình-cho-production)
   - [3.1. Cấu hình file .env](#31-cấu-hình-file-env)
   - [3.2. Cấu hình docker-compose.yml (Bảo mật & Cổng kết nối)](#32-cấu-hình-docker-composeyml-bảo-mật--cổng-kết-nối)
   - [3.3. Cấu hình Nginx & Tên miền](#33-cấu-hình-nginx--tên-miền)
4. [Phân quyền thư mục](#4-phân-quyền-thư-mục)
5. [Build và Khởi chạy Docker Container](#5-build-và-khởi-chạy-docker-container)
6. [Khởi tạo / Nhập Cơ sở dữ liệu (Database)](#6-khởi-tạo--nhập-cơ-sở-dữ-liệu-database)
7. [Tối ưu hóa Laravel trên Production](#7-tối-ưu-hóa-laravel-trên-production)
8. [Cấu hình HTTPS / SSL (Let's Encrypt)](#8-cấu-hình-https--ssl-lets-encrypt)
9. [Quy trình cập nhật mã nguồn (CI/CD / Update)](#9-quy-trình-cập-nhật-mã-nguồn-cicd--update)
10. [Sao lưu định kỳ (Backup Database)](#10-sao-lưu-định-kỳ-backup-database)

---

## 1. Chuẩn bị Server Production

### 1.1. Cập nhật hệ thống và cài đặt Docker & Docker Compose
Đăng nhập SSH vào server Ubuntu/Debian với quyền `root` hoặc `sudo`:

```bash
sudo apt update && sudo apt upgrade -y

# Cài đặt các gói hỗ trợ
sudo apt install -y curl git ufw ca-certificates gnupg lsb-release

# Cài đặt Docker Engine chính thức
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Cài đặt Docker Compose plugin
sudo apt install -y docker-compose-plugin

# Cấp quyền chạy docker cho user hiện tại (nếu dùng user non-root)
sudo usermod -aG docker $USER
```

### 1.2. Thiết lập tường lửa (Firewall UFW)
Chỉ mở các cổng cần thiết cho web và SSH:

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp    # Cổng SSH (hoặc cổng SSH bạn đã đổi)
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

> [!CAUTION]
> **Tuyệt đối không mở port 3306 hoặc 3307 trên tường lửa ra ngoài Internet.** Database MySQL chỉ nên cho phép truy cập nội bộ trong mạng Docker hoặc qua SSH Tunnel.

---

## 2. Đưa mã nguồn lên Server

Bạn có thể đưa mã nguồn lên server qua **Git** hoặc qua **Rsync**.

### Cách 1: Sử dụng Git (Khuyên dùng)
Tạo thư mục trên server và clone dự án về:

```bash
sudo mkdir -p /var/www/mtgroup
sudo chown -R $USER:$USER /var/www/mtgroup
cd /var/www/mtgroup

# Clone từ Git Repository của bạn
git clone <URL_GIT_REPO> .
```

### Cách 2: Sử dụng Rsync đồng bộ trực tiếp từ máy local lên server
Từ máy local chạy lệnh:

```bash
rsync -avz --exclude 'minhthanh3/vendor' \
           --exclude 'minhthanh3/storage/logs/*.log' \
           --exclude 'minhthanh3/node_modules' \
           /var/www/mtgroup/ user@your-server-ip:/var/www/mtgroup/
```

---

## 3. Điều chỉnh cấu hình cho Production

### 3.1. Cấu hình file `.env`
Tạo hoặc sửa file `/var/www/mtgroup/minhthanh3/.env` trên server:

```ini
APP_NAME="Minh Thanh Group"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

APP_KEY=base64:jPDTWLGPka60Lg927hn8Qcxt9ZiuAiXT3XZAf8mwWu4=
LOG_CHANNEL=daily

# Cấu hình Database nội bộ Docker
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=minhthanh2
DB_USERNAME=mtgroup_prod_user
DB_PASSWORD=MatKhauDatabaseManh_RandomString123!
DB_STRICT=false

ADMIN_DIR=admin
```

> [!IMPORTANT]
> - `APP_ENV=production` và `APP_DEBUG=false`: Bắt buộc tắt debug để tránh lộ cấu hình và database credentials khi phát sinh lỗi.
> - Thay đổi `DB_USERNAME` và `DB_PASSWORD` sang chuỗi mật khẩu mạnh ngẫu nhiên, không dùng mật khẩu mặc định `123456`.

---

### 3.2. Cấu hình `docker-compose.yml` (Bảo mật & Cổng kết nối)

Trên server Production, chỉnh sửa `/var/www/mtgroup/docker-compose.yml`:
1. Đổi cổng của `webserver` sang cổng `80:80` (và `443:443`).
2. **Loại bỏ việc expose port DB ra ngoài Internet**: Bỏ mục `ports:` ở service `db` hoặc chỉ bind vào localhost `127.0.0.1:3307:3306`.
3. Cập nhật `MYSQL_PASSWORD` và `MYSQL_ROOT_PASSWORD` trùng khớp với file `.env`.
4. Không mount đè file SQL dump tự động nếu bạn muốn kiểm soát việc import bằng tay.

Mẫu file `docker-compose.yml` chuẩn cho Production:

```yaml
services:
  # 1. PHP Application
  app:
    build:
      context: ./php
      dockerfile: Dockerfile
    container_name: laravel_app
    restart: always
    environment:
      SERVICE_NAME: app
      SERVICE_TAGS: prod
    working_dir: /var/www/minhthanh3
    volumes:
      - ./:/var/www
      - ./php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - laravel_net

  # 2. Nginx Web Server
  webserver:
    image: nginx:alpine
    container_name: laravel_webserver
    restart: always
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./nginx:/etc/nginx/conf.d/
      # Mount thêm thư mục chứng chỉ SSL nếu cấu hình SSL trực tiếp trong Nginx
      - ./nginx/ssl:/etc/nginx/ssl:ro
    networks:
      - laravel_net
    depends_on:
      - app

  # 3. MySQL 8 Database
  db:
    image: mysql:8.0
    container_name: laravel_db
    restart: always
    environment:
      MYSQL_DATABASE: minhthanh2
      MYSQL_ROOT_PASSWORD: RootPasswordManh_KhongDe123456!
      MYSQL_USER: mtgroup_prod_user
      MYSQL_PASSWORD: MatKhauDatabaseManh_RandomString123!
    command: --default-authentication-plugin=mysql_native_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - laravel_net

networks:
  laravel_net:
    driver: bridge

volumes:
  dbdata:
    driver: local
```

---

### 3.3. Cấu hình Nginx & Tên miền

Cập nhật file `/var/www/mtgroup/nginx/laravel.conf`:
- Điền `server_name yourdomain.com www.yourdomain.com;` thay vì để mặc định.
- Tăng giới hạn upload client: `client_max_body_size 100M;`.

Ví dụ:
```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    root /var/www/minhthanh3/public;
    index index.php index.html;

    client_max_body_size 100M;

    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\..+)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

---

## 4. Phân quyền thư mục

Để ứng dụng không bị lỗi `Permission Denied` khi ghi session/cache/log:

```bash
cd /var/www/mtgroup

# Cấp quyền ghi cho thư mục storage và bootstrap/cache
chmod -R 775 minhthanh3/storage minhthanh3/bootstrap/cache
```

Nếu server chạy process dưới user non-root (ví dụ `ubuntu`), bạn có thể gán quyền sở hữu:
```bash
sudo chown -R $USER:www-data /var/www/mtgroup/minhthanh3
sudo chmod -R 775 /var/www/mtgroup/minhthanh3/storage /var/www/mtgroup/minhthanh3/bootstrap/cache
```

---

## 5. Build và Khởi chạy Docker Container

Tại thư mục `/var/www/mtgroup`:

```bash
# 1. Build image PHP
docker compose build --no-cache

# 2. Khởi chạy toàn bộ container dưới chế độ nền (daemon)
docker compose up -d

# 3. Kiểm tra trạng thái các container
docker compose ps
```

---

## 6. Khởi tạo / Nhập Cơ sở dữ liệu (Database)

Nếu database trên server chưa có dữ liệu, hãy nhập file `minhthanh2.sql` vào container `laravel_db`:

```bash
# Nhập file SQL vào MySQL container
docker exec -i laravel_db mysql -u anhnn -p'123456' minhthanh2 < /var/www/mtgroup/minhthanh3/minhthanh2.sql

# Hoặc dùng tài khoản root nếu cần:
docker exec -i laravel_db mysql -u root -p'RootPasswordManh_KhongDe123456!' minhthanh2 < /var/www/mtgroup/minhthanh3/minhthanh2.sql
```

> [!TIP]
> Sau khi đã nạp database thành công lên Production, hãy **xóa hoặc chuyển file `minhthanh2.sql` ra khỏi thư mục web public** để đảm bảo an toàn dữ liệu:
> ```bash
> mv /var/www/mtgroup/minhthanh3/minhthanh2.sql /root/backup_minhthanh2.sql
> ```

---

## 7. Tối ưu hóa Laravel trên Production

Chạy các lệnh Artisan tối ưu hóa bên trong container PHP:

```bash
# 1. Cài đặt các package composer (nếu chưa có vendor trên server)
docker compose exec app composer install --no-dev --optimize-autoloader

# 2. Tạo symlink cho thư mục lưu trữ media
docker compose exec app php artisan storage:link

# 3. Xóa cache cũ
docker compose exec app php artisan config:clear
docker compose exec app php artisan route:clear
docker compose exec app php artisan view:clear

# 4. Tạo cache tối ưu cho production
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
```

---

## 8. Cấu hình HTTPS / SSL (Let's Encrypt)

Có 2 phương án phổ biến và an toàn nhất để bật SSL:

### Phương án A: Dùng Cloudflare (Nhanh, Tiện, Miễn phí)
1. Trỏ DNS tên miền về IP server thông qua Cloudflare (bật Proxy vàng).
2. Trong Cloudflare SSL/TLS: chọn chế độ **Full** hoặc **Flexible**.
3. Không cần cấu hình chứng chỉ phức tạp trên Nginx.

### Phương án B: Cài Certbot trên Host để cấp chứng chỉ cho Docker
1. Cài đặt Certbot trên host:
   ```bash
   sudo apt install -y certbot
   ```
2. Tạm dừng Nginx trong Docker:
   ```bash
   docker compose stop webserver
   ```
3. Cấp chứng chỉ SSL standalone:
   ```bash
   sudo certbot certonly --standalone -d yourdomain.com -d www.yourdomain.com
   ```
4. Chứng chỉ sẽ nằm ở: `/etc/letsencrypt/live/yourdomain.com/`.
5. Mount thư mục chứng chỉ vào `webserver` trong `docker-compose.yml`:
   ```yaml
   volumes:
     - /etc/letsencrypt:/etc/letsencrypt:ro
   ```
6. Cập nhật `laravel.conf` lắng nghe cổng 443 và bật `ssl_certificate`.
7. Khởi động lại webserver:
   ```bash
   docker compose up -d webserver
   ```

---

## 9. Quy trình cập nhật mã nguồn (CI/CD / Update)

Mỗi khi có code mới cần update lên server Prod:

```bash
cd /var/www/mtgroup

# 1. Kéo code mới về
git pull origin main

# 2. Chạy migrate (nếu có migration mới)
docker compose exec app php artisan migrate --force

# 3. Cập nhật cache
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache

# 4. Restart nhẹ PHP worker
docker compose restart app
```

---

## 10. Sao lưu định kỳ (Backup Database)

Tạo một cron job để tự động backup database mỗi đêm:

1. Mở crontab:
   ```bash
   crontab -e
   ```
2. Thêm dòng sau (chạy lúc 02:00 sáng mỗi ngày, nén thành file `.sql.gz`):
   ```cron
   0 2 * * * docker exec laravel_db mysqldump -u root -p'RootPasswordManh_KhongDe123456!' minhthanh2 | gzip > /var/backups/minhthanh2_$(date +\%F).sql.gz
   ```
3. Tạo thư mục chứa backup:
   ```bash
   sudo mkdir -p /var/backups
   ```

---
*Hoàn tất! Hệ thống Docker của bạn đã sẵn sàng hoạt động an toàn và ổn định trên môi trường Production.*

