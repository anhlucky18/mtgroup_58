# KẾ HOẠCH NÂNG CẤP & CHUYỂN ĐỔI HỆ THỐNG: MIGRATION SANG LARAVEL 13
> **Mục tiêu trọng tâm:** Chuyển đổi nguyên trạng (1:1) từ hệ thống CMS hiện tại lên **Laravel 13** nhằm đảm bảo hệ thống vận hành ổn định, an toàn, tối ưu hiệu năng và thoát khỏi nợ kỹ thuật.  
> **Phạm vi nghiệp vụ:** **Chỉ tập trung vào CMS cốt lõi hiện có** (Bài viết, Chuyên mục, Trang tĩnh, Media, Menu, Người dùng, SEO URL). **Loại bỏ toàn bộ các tính năng/nghiệp vụ phát sinh** (không E-commerce/Giỏ hàng, không Hotel/Booking phòng).  
> **Nguồn dữ liệu hiện tại:** MySQL `minhthanh2` (Botble CMS / Laravel 5.8 / PHP 7.3)  
> **Nền tảng mục tiêu:** Laravel 13 + Filament v4 (PHP 8.3+)  
> **Ngày cập nhật:** 05/11/2026  

---

## 1. MỤC TIÊU & NGUYÊN TẮC THIẾT KẾ

### 1.1. Mục tiêu cốt lõi
1. **Chấm dứt hoàn toàn nợ công nghệ (Technical Debt):**
   * Thoát khỏi phiên bản lỗi thời PHP 7.3 và nhân đóng gói nguyên khối của *Botble CMS* trên Laravel 5.8.
   * Đưa hệ thống lên **Laravel 13 (PHP 8.3/8.4)** theo chuẩn mã nguồn mở tiêu chuẩn, làm chủ 100% source code, mở đường cho việc nâng cấp tự động định kỳ sau này qua *Laravel Shift*.
2. **Ưu tiên số một: Vận hành ổn định (Stability First):**
   * Giữ nguyên vẹn toàn bộ tính năng và dữ liệu hiện đang chạy trên website `minhthanh3`.
   * Không đưa thêm bất kỳ tính năng phức tạp mới nào vào giai đoạn này nhằm kiểm soát tối đa rủi ro, rút ngắn thời gian bàn giao và kiểm thử.
3. **Bảo tồn toàn vẹn dữ liệu & Thứ hạng SEO (Zero Data/SEO Loss):**
   * **100% Slugs & URLs:** Toàn bộ đường dẫn bài viết, danh mục, trang tĩnh được bảo lưu chính xác tuyệt đối, không gây lỗi 404 trên Google Search Console.
   * **Bảo toàn xác thực:** Giữ nguyên chuỗi mã hóa mật khẩu Bcrypt của thành viên/admin hiện tại, đảm bảo đăng nhập bình thường mà không cần reset mật khẩu.
   * **Bảo toàn cấu trúc Media & Nội dung:** Giữ nguyên ID, liên kết ảnh trong nội dung bài viết và các thư viện tệp tin.
4. **Tối ưu chi phí & Tận dụng tài nguyên:**
   * Tái sử dụng **100% giao diện Blade/HTML/CSS** của theme `minhthanh3` hiện hữu. Chỉ thực hiện bóc tách, chuẩn hóa cú pháp helper của Botble về chuẩn Laravel Blade thuần.

### 1.2. Nguyên tắc triển khai
* **Phát triển trực tiếp trên môi trường Native (Host-direct Development):**
  * Tận dụng hạ tầng có sẵn trên máy chủ: **PHP 8.4** (`PHP 8.4.25 cli/fpm`) và **MySQL 8** (`8.0.46`), phát triển trực tiếp không cần qua Docker.
  * **Tạm gác Docker sang Phase sau:** Toàn bộ việc đóng gói Dockerfile / Docker Compose sẽ chuyển thành giai đoạn chuẩn hóa DevOps sau khi hệ thống đã migrate xong và chạy ổn định.
* **Nguyên tắc "Song song không xâm lấn" (Side-by-Side Zero-Risk):**
  * Dự án mới được đặt trong thư mục độc lập (ví dụ `/var/www/mtgroup/core` hoặc cấu hình virtual host cổng riêng).
  * Website và database cũ (`minhthanh3`) vẫn duy trì hoạt động phục vụ người dùng 100% trong suốt quá trình phát triển và kiểm thử.
* **Quy trình ETL tự động (Extract - Transform - Load):**
  * Chuyển đổi dữ liệu thông qua Custom Artisan Command có hỗ trợ chế độ chạy thử (`--dry-run`), rollback theo database transaction và ghi log chi tiết lỗi phát sinh.

---

## 2. KIẾN TRÚC HỆ THỐNG MỤC TIÊU

```
                                  [ INTERNET ]
                                       │
                                       ▼
                       [ Nginx Reverse Proxy (Port 80/443) ]
                                       │
            ┌──────────────────────────┴──────────────────────────┐
            ▼                                                     ▼
 [ Frontend (Standard Laravel Blade) ]             [ Admin Backend (Filament v4 Panel) ]
  - Tái sử dụng trọn vẹn Theme hiện tại             - Dashboard quản trị tinh gọn
  - Blade Components & View Composers               - Quản lý Bài viết & Danh mục (Nested)
  - Layout Header, Footer, Menu động                - Quản lý Trang tĩnh & Khối nội dung
  - Tối ưu SEO Meta & Canonical URL                 - Quản lý Media & Menu đa cấp
            │                                       - Quản lý Thành viên & Phân quyền
            │                                                     │
            └──────────────────────────┬──────────────────────────┘
                                       │
                        [ Laravel 13 Application Layer ]
                             (PHP 8.3 / PHP 8.4)
                                       │
            ┌──────────────────────────┼──────────────────────────┐
            ▼                          ▼                          ▼
 [ MySQL 8.0 (Innodb) ]       [ File / Database Cache ]   [ Local File Storage ]
  - utf8mb4_unicode_ci         - File-based Cache          - Public storage chuẩn
  - Bảng chuẩn hóa Laravel     - File / Database Session   - Tương thích URL ảnh cũ
```

### 2.1. Tech Stack chuẩn hóa
* **Framework:** Laravel 13.x.
* **Môi trường Runtime:** PHP 8.3 hoặc 8.4 (FPM).
* **Quản trị Admin:** FilamentPHP v4 (dựa trên Livewire v3/v4 & Tailwind CSS v4) – hiện đại, nhẹ, bảo mật cao.
* **Cơ sở dữ liệu:** MySQL 8.0 (Engine InnoDB).
* **Caching & Session:** Dùng cơ chế mặc định của Laravel (`file` hoặc `database`), **không cần cài đặt Redis** giúp hạ tầng gọn nhẹ, tối ưu RAM và giảm thiểu điểm lỗi (points of failure).
* **Các thư viện hạt nhân:**
  * `spatie/laravel-permission`: Quản lý vai trò và quyền hạn quản trị viên.
  * `spatie/laravel-medialibrary` hoặc `awcodes/curator`: Quản lý thư viện hình ảnh chuẩn, hỗ trợ tự động WebP thumbnail.
  * `filament/filament:^4.0`: Giao diện Admin CRUD tốc độ cao.

---

## 3. PHÂN TÍCH & KẾ HOẠCH CHUYỂN DỊCH DỮ LIỆU (1:1 MAPPING)

Tất cả bảng dữ liệu chuyển dịch đều là các thực thể nội dung hiện hữu, không có bất kỳ bảng nghiệp vụ phát sinh nào:

| Phân hệ | Bảng nguồn (`minhthanh2`) | Bảng đích (Laravel 13) | Giải pháp kỹ thuật & Kiểm soát rủi ro |
| :--- | :--- | :--- | :--- |
| **Quản trị & Thành viên** | `users`, `roles`, `role_users` | `users`, `roles`, `model_has_roles` | Giữ nguyên trường `password` (chuẩn Bcrypt). Map lại vai trò sang chuẩn *Spatie Permission*. |
| **Chuyên mục** | `categories` | `categories` | Giữ nguyên `id`, `name`, `parent_id`, `description`, thứ tự hiển thị. Giữ nguyên cây danh mục đa cấp. |
| **Bài viết** | `posts`, `post_categories` | `posts`, `category_post` | Giữ nguyên `id`, tiêu đề, mô tả tóm tắt, nội dung HTML, tác giả, trạng thái xuất bản, ngày tạo (`created_at`). |
| **Trang tĩnh** | `pages` | `pages` | Giữ nguyên `id`, tiêu đề, nội dung, template hiển thị. |
| **Đường dẫn SEO (Slugs)** | `slugs` (bảng riêng của Botble) | `slugs` hoặc cột `slug` trong từng bảng | Đảm bảo 100% khớp URL gốc. Thiết lập bảng ánh xạ phụ để tự động Redirect 301 nếu cần cấu trúc lại. |
| **Tệp tin Media** | `media_files`, `media_folders` | `media` | Đồng bộ thư mục vật lý từ `public/storage`. Chuẩn hóa đường dẫn tương đối để các bài viết cũ không bị mất ảnh. |
| **Menu điều hướng** | `menus`, `menu_nodes` | `menus`, `menu_items` | Tái cấu trúc cây phân cấp menu đệ quy (`parent_id`, `order`) để hiển thị khớp 100% với header/footer hiện tại. |
| **Khối tĩnh & Cấu hình** | `settings`, `widgets`, `blocks` | `settings`, `content_blocks` | Trích xuất các thông tin cố định (Hotline, Logo, Giới thiệu footer, Mã theo dõi Google Analytics) sang bảng cấu hình hệ thống. |

---

## 4. CHI TIẾN LỆNH ARTISAN MIGRATION (ETL SCRIPT)

Xây dựng lệnh điều khiển chuyên biệt:
```bash
php artisan app:migrate-legacy-data {--dry-run} {--fresh} {--step=}
```

### Các bước thực thi bên trong Script:
1. **Bước 1 - Migrate Users & Permissions:** Đọc từ bảng `users`, map vào bảng `users` mới, đồng bộ phân quyền Admin/Editor.
2. **Bước 2 - Migrate Categories:** Đọc danh mục cha, danh mục con, bảo toàn ID để không mất quan hệ bài viết.
3. **Bước 3 - Migrate Slugs & Pages:** Nhập trang tĩnh và các slug tương ứng.
4. **Bước 4 - Migrate Posts & Category Relations:** Bóc tách nội dung HTML bài viết, làm sạch các shortcode riêng của Botble (nếu có), ánh xạ lại bảng trung gian `category_post`.
5. **Bước 5 - Migrate Media Folders & Files:** Quét file vật lý, tạo bản ghi media chuẩn.
6. **Bước 6 - Migrate Menu Nodes:** Xây dựng lại cấu trúc cây menu.
7. **Bước 7 - Validate & Data Integrity Check:** Kiểm tra số lượng bản ghi giữa database cũ và database mới, cảnh báo các bản ghi bị lệch hoặc thiếu slug.

---

## 5. KẾ HOẠCH XỬ LÝ FRONTEND (THEME REFACTORING)

Giao diện theme hiện tại (`minhthanh3`) hoạt động tốt về mặt thẩm mỹ và bố cục. Kế hoạch di chuyển sẽ làm sạch tầng logic Blade:

### 5.1. Bóc tách và thay thế các hàm đặc thù của Botble
* **Hàm nạp asset & header:**
  * Cũ: `{!! Theme::header() !!}`, `{!! Theme::footer() !!}`
  * Mới: Chuyển thành Blade Layout chuẩn `@yield('styles')`, `@yield('scripts')` hoặc chuẩn Vite `@vite(['resources/css/app.css', 'resources/js/app.js'])`.
* **Hàm nạp Menu:**
  * Cũ: `{!! Menu::renderMenuLocation('main-menu', ...) !!}`
  * Mới: Chuyển thành View Component `<x-navigation-menu location="main-menu" />`.
* **Hàm lấy ảnh Media:**
  * Cũ: `RvMedia::getImageUrl($post->image, 'medium')`
  * Mới: Dùng `$post->featured_image_url` hoặc helper chuẩn `asset('storage/' . $post->image)`.
* **Hàm xử lý SEO:**
  * Cũ: `SeoHelper::render()`
  * Mới: Tích hợp partial `<x-seo-meta :model="$post ?? $page" />`.

### 5.2. Giữ nguyên toàn bộ CSS, JS và Layout HTML
* Giữ nguyên 100% các file `bootstrap`, `font-awesome`, file CSS/JS tùy biến của theme `minhthanh3` đưa vào thư mục `public/themes/minhthanh3/` hoặc đóng gói qua Vite để giao diện người dùng không thay đổi dù chỉ 1 pixel.

---

## 6. XÂY DỰNG TRANG QUẢN TRỊ ADMIN (FILAMENT v4)

Giao diện quản trị sẽ cực kỳ trực quan, nhẹ và nhanh, chỉ tập trung vào các tính năng quản lý nội dung thực tế:

1. **Dashboard:** Thống kê cơ bản: Tổng số bài viết, Tổng số danh mục, Trang tĩnh, Thành viên quản trị, Nhật ký cập nhật gần nhất.
2. **Quản lý Bài viết (Post Resource):**
   * Danh sách bài viết có tìm kiếm theo tiêu đề, lọc theo danh mục, trạng thái (Xuất bản / Bản nháp).
   * Form viết bài tích hợp bộ soạn thảo Rich Editor (TipTap), hỗ trợ chèn ảnh, định dạng văn bản, cấu hình thẻ Meta SEO và Slug tùy chỉnh.
3. **Quản lý Danh mục (Category Resource):**
   * Quản lý phân cấp danh mục cha - con.
4. **Quản lý Trang tĩnh (Page Resource):**
   * Quản lý các trang: Giới thiệu, Liên hệ, Chính sách bảo mật, Điều khoản dịch vụ.
5. **Quản lý Thư viện Media:**
   * Quản lý file ảnh, upload kéo thả trực quan.
6. **Quản lý Menu:**
   * Kéo thả sắp xếp thứ tự và cấp bậc các mục menu trên Header và Footer.
7. **Cấu hình Website (General Settings):**
   * Quản lý Logo, Favicon, Hotline, Email liên hệ, Địa chỉ, Mã nhúng script Header/Body (Google Analytics, Facebook Pixel).
8. **Quản lý Người dùng & Phân quyền:**
   * Tạo tài khoản quản trị viên, biên tập viên, phân quyền truy cập chức năng.

---

## 7. LỘ TRÌNH THỰC HIỆN CHI TIẾT (TIMELINE RÚT GỌN)

Nhờ **loại bỏ toàn bộ các bài toán nghiệp vụ phát sinh**, tổng thời gian thực hiện giảm từ 4 tuần xuống chỉ còn **khoảng 10 – 12 ngày làm việc (2 tuần)**:

```
[ Tuần 1: Khởi tạo Core Trực tiếp + ETL Data Migration + Xây dựng Admin Filament v4 ]
  ├── Ngày 1 - 2: Khởi tạo Laravel 13 trên PHP 8.4 Host, Cài đặt Filament v4 & Packages
  ├── Ngày 3 - 4: Viết Schema Migrations & Script ETL chuyển đổi dữ liệu
  └── Ngày 5: Hoàn thiện CRUD Admin (Posts, Categories, Pages, Media, Menu)

[ Tuần 2: Chuẩn hóa Frontend Blade + Kiểm thử Toàn vẹn + Go-Live ]
  ├── Ngày 6 - 8: Bóc tách Theme minhthanh3 sang chuẩn Laravel Blade
  ├── Ngày 9 - 10: Kiểm thử đường dẫn SEO (URL/Slugs), Form liên hệ, User Login
  └── Ngày 11: Cutover Nginx chuyển traffic sang bản mới (Zero Downtime)
```

### Bảng phân rã công việc chi tiết:

| Giai đoạn | Nhiệm vụ kỹ thuật | Thời gian ước tính | Tiêu chí hoàn thành (DoD) |
| :--- | :--- | :---: | :--- |
| **Giai đoạn 1: Khởi tạo & Core** | - Tận dụng PHP 8.4 và MySQL 8 có sẵn trên server, tạo thư mục project Laravel 13 độc lập.<br>- Cài đặt khung Laravel 13 chuẩn.<br>- Cài đặt & cấu hình Filament v4, Spatie Permission. | **1 - 2 ngày** | Chạy được màn hình đăng nhập Admin Filament trên host nội bộ. |
| **Giai đoạn 2: Di chuyển dữ liệu** | - Viết Database Migrations cho các bảng nội dung.<br>- Viết Artisan Command đọc từ MySQL `minhthanh2` và import.<br>- Kiểm tra đối soát số lượng bài viết, user, danh mục. | **2 ngày** | 100% dữ liệu cũ xuất hiện đầy đủ, nguyên vẹn trên database mới. |
| **Giai đoạn 3: Admin Resources** | - Xây dựng các Resource trong Filament: Posts, Categories, Pages, Menus, Media, Settings.<br>- Cấu hình SEO meta fields. | **2 ngày** | Biên tập viên có thể tạo/sửa bài viết, upload ảnh, sửa menu mượt mà. |
| **Giai đoạn 4: Frontend Refactor** | - Chuyển đổi toàn bộ view từ `minhthanh3` sang Blade chuẩn.<br>- Xóa bỏ các helper cũ của Botble, thay bằng Component chuẩn.<br>- Kết nối dữ liệu động: Trang chủ, Danh mục, Chi tiết bài viết, Trang tĩnh. | **3 ngày** | Giao diện ngoài hiển thị giống 100% bản cũ, dữ liệu tải nhanh và chuẩn. |
| **Giai đoạn 5: Testing & Go-Live** | - Rà soát 100% link SEO, đảm bảo không có link nào sinh mã lỗi 404.<br>- Kiểm tra đăng nhập với các tài khoản user cũ.<br>- Tối ưu cache (`route:cache`, `view:cache`, `config:cache`).<br>- Cập nhật cấu hình Nginx Virtual Host trỏ DocumentRoot sang bản mới. | **2 ngày** | Website chính thức vận hành trên Laravel 13 với hiệu năng cao và ổn định. |
| **Giai đoạn sau (Post-migration Phase): Container hóa (DevOps)** | - Đóng gói Dockerfile chuẩn cho PHP 8.4 FPM + Nginx.<br>- Viết `docker-compose.yml` hoàn chỉnh cho production.<br>- Thiết lập quy trình CI/CD tự động deploy và backup định kỳ. | *(Thực hiện sau khi hệ thống mới chạy ổn định)* | Toàn bộ hệ thống được container hóa chuẩn chỉnh, dễ dàng nhân bản sang server khác. |

---

## 8. QUẢN TRỊ RỦI RO & PHƯƠNG ÁN AN TOÀN

1. **Rủi ro ảnh hưởng hệ thống đang chạy:**
   * **Biện pháp:** Bản mới chạy hoàn toàn tách biệt trong thư mục và database riêng. Hệ thống cũ vẫn hoạt động 100% cho đến khi nghiệm thu đạt chuẩn.
2. **Rủi ro gãy link SEO (404 Error):**
   * **Biện pháp:** Chạy script crawler quét đối chiếu toàn bộ sitemap cũ với sitemap mới trước ngày chuyển giao. Cấu hình middleware kiểm tra tự động redirect 301 nếu có đường dẫn cần chuẩn hóa.
3. **Rủi ro mã hóa mật khẩu:**
   * **Biện pháp:** Botble và Laravel thuần đều dùng chung cơ chế Bcrypt (`Hash::check()`), đảm bảo người dùng cũ đăng nhập bình thường mà không phát sinh lỗi.
4. **Kế hoạch Rollback tức thì (Dưới 1 phút):**
   * Nếu có bất kỳ sự cố phát sinh ngoài dự kiến trong ngày Go-Live, chỉ cần trỏ cấu hình Nginx upstream quay lại port của hệ thống cũ là xong ngay lập tức.

---

## 9. TỔNG KẾT
Kế hoạch này đưa dự án quay về **bản chất cốt lõi nhất**: Nâng cấp công nghệ sạch sẽ, ổn định và an toàn từ Laravel 5.8 / PHP 7.3 lên Laravel 13 / PHP 8.3+, không ôm đồm các phân hệ ngoài lề. Sau khi hệ thống vận hành ổn định trên nền tảng mới, bất kỳ tính năng kinh doanh nào khác (nếu cần trong tương lai) đều có thể mở rộng một cách dễ dàng và bài bản.
