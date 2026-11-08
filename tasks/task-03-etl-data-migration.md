# TASK-03: VIẾT & THỰC THI ETL MIGRATION SCRIPT (1:1 DATA MAPPING)

> **Trọng tâm:** Xây dựng Artisan Command chuyên dụng để trích xuất (Extract), chuẩn hóa (Transform) và nạp (Load) toàn bộ dữ liệu từ database MySQL cũ (`minhthanh2`) sang database Laravel 13 mới mà không làm mất bất kỳ bài viết, chuyên mục, tag, trang tĩnh, khối nội dung, menu hoặc liên kết ảnh nào.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-03
- **Phụ trách:** Senior Backend Engineer
- **Database nguồn:** MySQL `minhthanh2` (kết nối `mysql_legacy`)
- **Database đích:** MySQL `mtgroup_v13` (kết nối mặc định `mysql`)
- **Thời gian ước tính:** 2 ngày
- **Trạng thái:** 🟢 Hoàn thành

---

## 2. NĂM ĐIỂM TINH CHỈNH KỸ THUẬT QUAN TRỌNG (TECHNICAL REFINEMENTS)
1. **Bảng `slugs` nguồn:** Tên cột trong `minhthanh2.slugs` là `reference` (chứ không phải `reference_type`), và chứa giá trị chuỗi rút gọn `'post'`, `'category'`, `'page'`, `'tag'`. Khi nạp sang bảng `slugs` mới, map:
   - `'post'` ➔ `App\Models\Post`
   - `'category'` ➔ `App\Models\Category`
   - `'page'` ➔ `App\Models\Page`
   - `'tag'` ➔ `App\Models\Tag`
2. **Cấu trúc SEO Meta trong `meta_boxes`:** Cột `meta_value` với `meta_key = 'seo_meta'` lưu dưới dạng mảng JSON `[{"seo_title": "...", "seo_description": "..."}]` (152 bản ghi có SEO thực tế). Bắt buộc giải mã và đọc từ phần tử đầu tiên `$meta[0]['seo_title']`.
3. **Thư mục tệp Storage nguồn:** `minhthanh3/public/storage` là symlink hỏng từ máy chủ cũ. Thư mục dữ liệu vật lý thật là `/var/www/mtgroup/minhthanh3/storage/app/public/uploads`. Toàn bộ 880 tệp tin ảnh/media đã được copy trực tiếp từ đây sang `/var/www/mtgroup/laravel13/storage/app/public/uploads`.
4. **Chuẩn hóa Menu Locations:** `menu_locations` cũ liên kết 1 menu với nhiều vị trí. Trong bảng `menus` mới, gán vị trí chính xác: Menu 5 (Main Menu tiếng Việt) gán `location = 'main-menu'`, Menu 6 (Main Menu tiếng Anh) gán `location = 'main-menu-en'`, đồng thời tự động resolve URL cho các `custom-link` như "Trang chủ" / "Home" thành `/`.
5. **Trạng thái Bài viết & Cột `published_at`:** Bảo tồn 194 bài viết `publish` và 42 bài viết `draft`. Gán `$published_at = ($status === 'publish') ? $created_at : null`.

---

## 3. CHECKLIST CHI TIẾT

### 3.1. Chuẩn bị Eloquent Models Cốt lõi
- [x] Tạo các Model chuẩn trong `app/Models/` với `$fillable`, `$casts`, và các quan hệ:
  - `Category`: `parent()`, `children()`, `posts()`
  - `Tag`: `posts()`
  - `Post`: `categories()`, `tags()`, `author()`, `gallery` JSON cast
  - `Page`: `author()`, `gallery` JSON cast
  - `Slug`: `reference()` polymorphic relation
  - `Menu`: `items()`
  - `MenuItem`: `menu()`, `parent()`, `children()`
  - `ContentBlock`: `alias`, `content`, `locale`
  - `Setting`: `key`, `value`
  - `Contact`: `name`, `email`, `phone`, `content`, `status`

### 3.2. Cấu trúc Lệnh Artisan ETL
- [x] Tạo file lệnh `app/Console/Commands/MigrateLegacyDataCommand.php`:
  ```bash
  php artisan make:command MigrateLegacyDataCommand
  ```
- [x] Thiết lập cú pháp lệnh hỗ trợ cờ an toàn:
  ```bash
  php artisan app:migrate-legacy-data {--dry-run : Chạy thử kiểm tra logic và lỗi, tự động rollback không ghi DB} {--fresh : Làm sạch dữ liệu trước khi nạp} {--step= : Chạy riêng lẻ từng bước (users, categories, tags, pages, posts, slugs, blocks, menus, settings, media)}
  ```
- [x] Cơ chế Transaction & In-Memory Preloading:
  - Preload toàn bộ `slugs`, `language_meta`, `meta_boxes`, `gallery_meta` vào mảng PHP bộ nhớ trong để nạp dữ liệu siêu tốc (< 5 giây).
  - Tạm ngắt kiểm tra khóa ngoại: `Schema::disableForeignKeyConstraints()`.
  - Trong chế độ `--dry-run`, sử dụng `DB::beginTransaction()` và `DB::rollBack()` (dùng `DELETE FROM` thay vì `TRUNCATE` để tránh MySQL implicit commit).

### 3.3. Chi tiết các Bước Chuyển dịch Dữ liệu

#### Bước 1: Migrate Users (Người dùng Quản trị)
- [x] Đọc từ `minhthanh2.users` (1 super user: `phancuongcdth12@gmail.com`).
- [x] Ghép `first_name` và `last_name` thành cột `name` (`Minh Thành`).
- [x] Giữ nguyên 100% cột `password` (chuỗi Bcrypt hash).
- [x] Xử lý an toàn: Insert hoặc Update admin cũ song song với admin test (`admin@mtgroup.vn`).
- [x] Gán vai trò `super_admin` qua Spatie Permission (`$user->assignRole('super_admin')`).

#### Bước 2: Migrate Categories (15 Chuyên mục)
- [x] Đọc từ `minhthanh2.categories`.
- [x] Nạp `slug` từ bảng `minhthanh2.slugs` (theo `reference = 'category'`).
- [x] Nạp `locale` từ bảng `minhthanh2.language_meta` (13 bản ghi `vi`, 2 bản ghi `en_US`, default `vi`).
- [x] Nạp `seo_title`, `seo_description` từ bảng `minhthanh2.meta_boxes` (`meta_key = 'seo_meta'`).
- [x] Chuẩn hóa `parent_id = 0` thành `null`.
- [x] Nạp vào bảng `categories`.

#### Bước 3: Migrate Tags (25 Từ khóa Bài viết)
- [x] Đọc từ bảng `minhthanh2.tags`.
- [x] Nạp `slug` tương ứng từ `minhthanh2.slugs` (`reference = 'tag'`).
- [x] Nạp `locale` từ `minhthanh2.language_meta` (25 bản ghi `vi`).
- [x] Nạp vào bảng `tags` mới.

#### Bước 4: Migrate Pages (7 Trang tĩnh)
- [x] Đọc từ bảng `minhthanh2.pages`.
- [x] Nạp `slug` từ `minhthanh2.slugs` (`reference = 'page'`).
- [x] Nạp `locale` từ `minhthanh2.language_meta` (6 bản ghi `vi`, 1 bản ghi `en_US`).
- [x] Nạp `seo_title`, `seo_description` từ `minhthanh2.meta_boxes`.
- [x] Trích xuất `gallery_meta` nạp vào JSON column `gallery`.
- [x] Giữ nguyên `template` (`default`, `services`, `products`, `projects`, `inquiry`).

#### Bước 5: Migrate Posts & Quan hệ Pivot (236 Bài viết)
- [x] Đọc từ bảng `minhthanh2.posts`.
- [x] Nạp `slug` từ `minhthanh2.slugs` (`reference = 'post'`).
- [x] Nạp `locale` từ `minhthanh2.language_meta` (220 `vi`, 14 `VI` -> chuẩn hóa `vi`, 2 `en_US`).
- [x] Nạp `seo_title`, `seo_description` từ `minhthanh2.meta_boxes` (phần tử `[0]`).
- [x] Trích xuất `gallery_meta` nạp vào JSON column `gallery`.
- [x] Chuẩn hóa cột `image` (giữ nguyên đường dẫn tương đối `/storage/uploads/...`).
- [x] Gán `published_at` (`created_at` nếu `publish`, `null` nếu `draft`).
- [x] Nạp vào bảng `posts`.
- [x] Đọc từ bảng `minhthanh2.post_categories` (244 dòng) -> nạp vào pivot `category_post`.
- [x] Đọc từ bảng `minhthanh2.post_tags` (25 dòng) -> nạp vào pivot `post_tag`.

#### Bước 6: Migrate Slugs (283 Đường dẫn Gốc)
- [x] Đọc toàn bộ 283 bản ghi từ `minhthanh2.slugs`.
- [x] Ánh xạ `reference` từ chuỗi cũ sang Laravel 13 Model Class:
  - `post` -> `App\Models\Post`
  - `category` -> `App\Models\Category`
  - `page` -> `App\Models\Page`
  - `tag` -> `App\Models\Tag`
- [x] Nạp vào bảng `slugs` đích để phục vụ Dynamic Catch-all Route.

#### Bước 7: Migrate Content Blocks (13 Khối Tĩnh Giao Diện Trang Chủ)
- [x] Đọc từ bảng `minhthanh2.blocks` (13 bản ghi với 13 alias riêng biệt).
- [x] Nạp `locale` từ bảng `minhthanh2.language_meta` (7 `vi`, 6 `en_US`).
- [x] Giữ nguyên 100% nội dung HTML thô vào cột `content` của bảng `content_blocks`.

#### Bước 8: Migrate Menus & Menu Items (Điều hướng Header/Footer)
- [x] Đọc từ `minhthanh2.menus` (6 bản ghi) và map `location` chuẩn.
- [x] Đọc từ bảng `minhthanh2.menu_nodes` (18 bản ghi).
- [x] Tự động resolve `title` và `url` từ category / page slug; resolve `/` nếu `custom-link` có URL rỗng ("Trang chủ", "Home").
- [x] Map `parent_id` (`0` -> `null`), `position` -> `order`, `css_class`, `icon_font`, `target`.
- [x] Nạp vào bảng `menu_items`.

#### Bước 9: Migrate Settings (Cấu hình Website & Mã nhúng)
- [x] Đọc từ bảng `minhthanh2.settings` (35 bản ghi).
- [x] Nạp toàn bộ 35 tham số: `site_title`, `theme-ripple-logo`, `admin_logo`, `admin_email`, `google_analytics`, `analytics_service_account_credentials` (GTM script), `seo_title`, `seo_description` vào bảng `settings`.

#### Bước 10: Đồng bộ Thư mục Storage & Tệp tin Media
- [x] Đồng bộ toàn bộ tệp tin thực tế từ `/var/www/mtgroup/minhthanh3/storage/app/public/uploads` sang `/var/www/mtgroup/laravel13/storage/app/public/uploads`.
- [x] Đảm bảo symlink `php artisan storage:link` hoạt động trơn tru tại `/var/www/mtgroup/laravel13/public/storage`.
- [x] Đảm bảo 880 tệp tin (`media_files`) có thể truy cập trực tiếp qua HTTP.

### 3.4. Báo cáo Đối soát Dữ liệu (Validation & Integrity Check)
- [x] Tự động đối chiếu số lượng bản ghi giữa database nguồn và database đích:
  - Users: 1 nguồn -> 2 đích (bảo tồn admin cũ ID 1 và test admin ID 2)
  - Categories: 15 vs 15 (100% MATCH)
  - Tags: 25 vs 25 (100% MATCH)
  - Posts: 236 vs 236 (100% MATCH)
  - Category - Post Pivot: 244 vs 244 (100% MATCH)
  - Tag - Post Pivot: 25 vs 25 (100% MATCH)
  - Pages: 7 vs 7 (100% MATCH)
  - Slugs: 283 vs 283 (100% MATCH)
  - Content Blocks: 13 vs 13 (100% MATCH)
  - Menu Items: 18 vs 18 (100% MATCH)
  - Settings: 35 vs 35 (100% MATCH)
  - Media Files: 3,729 vs 3,729 (100% MATCH)

---

## 4. TIÊU CHÍ HOÀN THÀNH (DoD)
1. ✅ Lệnh `php artisan app:migrate-legacy-data --dry-run` chạy thành công 100%, kiểm tra dữ liệu đối chiếu chính xác và rollback sạch sẽ.
2. ✅ Lệnh thực thi chính thức `php artisan app:migrate-legacy-data` nạp đủ 100% số lượng bài viết, danh mục, tags, trang tĩnh, khối nội dung, slug, menu.
3. ✅ Không có lỗi vi phạm khóa ngoại (foreign key constraints) hoặc bản ghi mồ côi.
4. ✅ Hình ảnh đại diện và ảnh trong nội dung bài viết hiển thị đúng đường dẫn, không bị vỡ ảnh.
