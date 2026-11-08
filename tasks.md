# DANH SÁCH NHIỆM VỤ DỰ ÁN (PROJECT TASKS)
## CHUYỂN ĐỔI HỆ THỐNG CMS MINHTHANH SANG LARAVEL 13

> **Phiên bản Laravel mục tiêu:** Laravel 13.x (PHP 8.4)  
> **Thư mục dự án:** `/var/www/mtgroup/laravel13`  
> **Kế hoạch chi tiết:** Xem tại [laravel13_plan.md](file:///var/www/mtgroup/laravel13_plan.md)  
> **Thư mục task chi tiết:** [tasks/](file:///var/www/mtgroup/tasks/)  
> **Trạng thái cập nhật:** 05/11/2026  

---

## TỔNG QUAN TIẾN ĐỘ

| Mã Task | Tên Giai Đoạn / Công Việc | Chi tiết Task | Ước tính | Trạng thái |
| :--- | :--- | :--- | :---: | :---: |
| **TASK-01** | Khởi tạo Laravel 13, Cấu hình Môi trường & Cài đặt Thư viện Hạt nhân | [task-01-core-setup.md](file:///var/www/mtgroup/tasks/task-01-core-setup.md) | 1-2 ngày | 🟢 Hoàn thành |
| **TASK-02** | Thiết kế Database Schemas chuẩn cho CMS cốt lõi | [task-02-database-migrations.md](file:///var/www/mtgroup/tasks/task-02-database-migrations.md) | 1 ngày | 🟢 Hoàn thành |
| **TASK-03** | Viết & Thực thi Lệnh ETL Chuyển Đổi Dữ Liệu 1:1 từ `minhthanh2` | [task-03-etl-data-migration.md](file:///var/www/mtgroup/tasks/task-03-etl-data-migration.md) | 2 ngày | 🟢 Hoàn thành |
| **TASK-04** | Xây dựng Giao diện Quản trị Admin CMS (Filament) | [task-04-admin-filament.md](file:///var/www/mtgroup/tasks/task-04-admin-filament.md) | 2 ngày | 🟢 Hoàn thành |
| **TASK-05** | Bóc tách & Chuẩn hóa Frontend Theme `minhthanh3` sang Blade thuần | [task-05-frontend-theme-refactor.md](file:///var/www/mtgroup/tasks/task-05-frontend-theme-refactor.md) | 3 ngày | 🟢 Hoàn thành |
| **TASK-06** | Kiểm thử Toàn vẹn SEO/URL, Hiệu năng & Chuyển giao (Cutover Nginx) | [task-06-testing-seo-cutover.md](file:///var/www/mtgroup/tasks/task-06-testing-seo-cutover.md) | 2 ngày | 🟢 Hoàn thành |
| **TASK-07** | Đóng gói Docker & Chuẩn hóa DevOps (Phase sau Go-Live) | [task-07-devops-containerization.md](file:///var/www/mtgroup/tasks/task-07-devops-containerization.md) | Post-Live | ⚪ Dự kiến |

---

## CHI TIẾT DANH MỤC CÔNG VIỆC

### [TASK-01: Khởi tạo Core & Cấu hình Hạ tầng](file:///var/www/mtgroup/tasks/task-01-core-setup.md)
- [x] Khởi tạo khung dự án Laravel 13 trong thư mục `laravel13` (Framework 13.40.0, PHP 8.4)
- [x] Thiết lập Composer binary nội bộ phục vụ môi trường native host
- [x] Cấu hình file `.env` kết nối MySQL nguồn (`minhthanh2`) và MySQL đích (`mtgroup_v13`)
- [x] Cài đặt Filament CMS (`filament/filament` v5.11)
- [x] Cài đặt gói quản lý phân quyền Spatie Permission (`spatie/laravel-permission` v8.4)
- [x] Cài đặt thư viện Media Manager (`spatie/laravel-medialibrary` v11.23)
- [x] Tạo tài khoản Super Admin đầu tiên và kiểm tra truy cập Admin Panel (`/admin` -> 200 OK)

### [TASK-02: Thiết kế Database Migrations](file:///var/www/mtgroup/tasks/task-02-database-migrations.md)
- [x] Migration bảng `categories` (cây danh mục đệ quy `parent_id`, `order`, `locale`, SEO meta)
- [x] Migration bảng `posts` & bảng pivot `category_post` (bổ sung `locale`, `gallery` JSON)
- [x] Migration bảng `tags` & bảng pivot `post_tag` (bảo toàn 25 từ khóa bài viết)
- [x] Migration bảng `pages` (trang tĩnh, `template`, `gallery` JSON, `locale`)
- [x] Migration bảng `slugs` (bảo tồn ánh xạ 283 URL gốc phục vụ Dynamic Routing)
- [x] Migration bảng `menus` & `menu_items` (cấu trúc cây menu đa cấp, `css_class`, `icon_font`)
- [x] Migration bảng `content_blocks` (13 khối tĩnh giao diện trang chủ: `main-slide`, `about-us`, `services`, `why-choose`, `featured-project`)
- [x] Migration bảng `settings` (cấu hình website, mã nhúng GTM, hotline, địa chỉ)
- [x] Migration bảng `contacts` (lưu trữ thông tin gửi từ form Inquiry / Liên hệ)

### [TASK-03: Viết & Thực thi ETL Migration Script (1:1)](file:///var/www/mtgroup/tasks/task-03-etl-data-migration.md)
- [x] Xây dựng Artisan Command `php artisan app:migrate-legacy-data` kết nối `mysql_legacy` với các cờ: `--dry-run`, `--fresh`, `--step=`
- [x] Bước ETL 1: Chuyển đổi người dùng (`users`), bảo toàn mật khẩu Bcrypt & phân quyền `super_admin`
- [x] Bước ETL 2: Chuyển đổi 15 danh mục (`categories`), giữ nguyên ID & cây phân cấp, join `slugs`, `meta_boxes` (SEO), `language_meta`
- [x] Bước ETL 3: Chuyển đổi 25 từ khóa (`tags`), join `slugs` và `language_meta`
- [x] Bước ETL 4: Chuyển đổi 7 trang tĩnh (`pages`), join `slugs`, `gallery_meta`, `meta_boxes`
- [x] Bước ETL 5: Chuyển đổi 236 bài viết (`posts`), nạp `gallery_meta`, nạp pivot `category_post` (244 dòng) và `post_tag` (25 dòng)
- [x] Bước ETL 6: Chuyển đổi toàn bộ 283 đường dẫn (`slugs`) sang Model namespace mới của Laravel 13
- [x] Bước ETL 7: Chuyển đổi 13 khối nội dung tĩnh (`blocks` -> `content_blocks`)
- [x] Bước ETL 8: Chuyển đổi 6 menu & 18 mục điều hướng (`menus`, `menu_nodes` -> `menu_items`), resolve URL/Title
- [x] Bước ETL 9: Chuyển đổi cấu hình trang (`settings`: GTM, logo, email, copyright)
- [x] Bước ETL 10: Đồng bộ cây thư mục tệp tin từ `storage/app/public/uploads` cũ và xác minh symlink `storage:link`
- [x] Bước ETL 11: Script kiểm tra đối soát số lượng và tính toàn vẹn dữ liệu (Data Integrity Check: 100% Khớp)

### [TASK-04: Xây dựng Giao diện Quản trị Filament](file:///var/www/mtgroup/tasks/task-04-admin-filament.md)
- [x] Dashboard tổng quan: Thống kê số lượng bài viết, danh mục, trang tĩnh, liên hệ mới chưa đọc
- [x] Post Resource: Quản lý bài viết, bộ soạn thảo Rich Editor, chọn tag, gallery ảnh, SEO Meta, tự động đồng bộ `slugs`
- [x] Category Resource: Quản lý danh mục bài viết đa cấp, tự động đồng bộ `slugs`
- [x] Tag Resource: Quản lý danh mục từ khóa bài viết, tự động đồng bộ `slugs`
- [x] Page Resource: Quản lý trang tĩnh (`default`, `inquiry`, `products`, `services`, `projects`), tự động đồng bộ `slugs`
- [x] Content Block Resource: Quản lý 13 khối giao diện trang chủ, Code Editor bảo toàn 100% markup HTML/CSS
- [x] Menu Builder Resource: Kéo thả phân cấp Header / Footer menu đa cấp
- [x] Contact Resource: Xem và xử lý các yêu cầu liên hệ / khảo sát từ khách hàng
- [x] Media Manager Resource: Quản lý tệp tin, hình ảnh, upload kéo thả
- [x] Settings Page: Cấu hình Logo, Favicon, Hotline, Email, Địa chỉ 4 văn phòng, Mã nhúng GTM Header/Footer
- [x] User & Role Resource: Phân quyền Super Admin và Editor (Spatie Permission)

### [TASK-05: Bóc tách & Chuẩn hóa Frontend Theme](file:///var/www/mtgroup/tasks/task-05-frontend-theme-refactor.md)
- [x] Di chuyển toàn bộ assets CSS/JS/Fonts/Images của theme `ripple` vào `public/themes/ripple/` (tạo symlink `public/themes/minhthanh3` -> `ripple`)
- [x] Dựng Master Layout Blade chuẩn (`layouts/master.blade.php`, `header.blade.php`, `footer.blade.php`, preloader, hotline rung lắc, FB chat)
- [x] Xây dựng Bộ xử lý Shortcode (`[static-block]`, `[services]`, `[products]`, `[projects]`, `[contacts]`, `[google-map]`, `[youtube-video]`)
- [x] Xây dựng View Component `<x-navigation-menu />` render đệ quy menu đa cấp
- [x] Xây dựng Helper chuẩn `get_object_image()` xử lý an toàn tiền tố `/storage/` và fallback ảnh
- [x] Xây dựng View Component `<x-seo-meta />` tự động sinh thẻ Title, Meta, Canonical và OpenGraph
- [x] Thiết lập Dynamic Catch-all Route `/{slug}` tra cứu bảng `slugs` điều hướng tự động sang Post, Category, hoặc Page (Zero 404)
- [x] Tích hợp giao diện Trang chủ động (render 5 static blocks qua Component)
- [x] Tích hợp giao diện Danh mục bài viết (phân trang chuẩn 12 bài/trang)
- [x] Tích hợp giao diện Chi tiết bài viết (nội dung, tags, bài trước/sau, chia sẻ MXH)
- [x] Tích hợp giao diện Trang tĩnh & Form khảo sát liên hệ (`views/inquiry.blade.php`) gửi POST tới `route('public.send.contact')`
- [x] Tích hợp giao diện Từ khóa bài viết (`views/tag.blade.php`) và Tìm kiếm (`views/search.blade.php`)
- [x] Đa ngôn ngữ giao diện: Tích hợp từ điển `lang/vi.json` và bộ chuyển đổi ngôn ngữ `<x-language-switcher />`

### [TASK-06: Kiểm thử, Tối ưu hóa & Chuyển giao (Cutover)](file:///var/www/mtgroup/tasks/task-06-testing-seo-cutover.md)
- [x] Kiểm thử đăng nhập với các tài khoản user/admin cũ (Bcrypt hash)
- [x] Quét đối soát 100% URL/Slug so với website cũ (Zero 404 Error: 241/241 published 200 OK)
- [x] Thiết lập Middleware kiểm tra & Redirect 301 tự động cho các URL cũ (`RedirectLegacySlugMiddleware`)
- [x] Tối ưu hóa bộ nhớ đệm: `config:cache`, `route:cache`, `view:cache`, `event:cache`
- [x] Cấu hình Nginx Virtual Host trỏ DocumentRoot sang `/var/www/mtgroup/laravel13/public`
- [x] Xây dựng quy trình Rollback tức thì (< 1 phút) khi có sự cố phát sinh

### [TASK-07: Chuẩn hóa Container DevOps (Phase sau)](file:///var/www/mtgroup/tasks/task-07-devops-containerization.md)
- [ ] Viết Dockerfile chuẩn cho PHP 8.4 FPM với đầy đủ OPcache & extensions
- [ ] Cập nhật `docker-compose.yml` hoàn chỉnh cho production
- [ ] Thiết lập script backup tự động database và public storage định kỳ

