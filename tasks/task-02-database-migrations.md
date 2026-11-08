# TASK-02: THIẾT KẾ DATABASE SCHEMAS CHUẨN CHO CMS CỐT LÕI

> **Trọng tâm:** Thiết kế các migration tables trong Laravel 13 chuẩn hóa hoàn toàn theo cấu trúc chuẩn của Laravel, loại bỏ sự phụ thuộc vào cấu trúc module rườm rà của Botble nhưng bảo toàn 100% các quan hệ, thuộc tính SEO, đa ngôn ngữ và dữ liệu thực tế từ `minhthanh2`.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-02
- **Phụ trách:** Backend Developer / Database Engineer
- **Database:** MySQL 8.0 (InnoDB, `utf8mb4_unicode_ci`)
- **Thời gian ước tính:** 1 ngày
- **Trạng thái:** 🟢 Hoàn thành

---

## 2. CHECKLIST CHI TIẾT CÁC BẢNG MIGRATION

### 2.1. Migration Bảng Categories (Chuyên mục Bài viết, Dịch vụ & Dự án)
- [x] Tạo file migration `create_categories_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(255)
  - `slug`: string(255), unique, indexed
  - `parent_id`: unsignedBigInteger, nullable, foreign key tự tham chiếu `categories(id)` on delete set null
  - `description`: text, nullable
  - `image`: string(500), nullable (ảnh đại diện chuyên mục nếu có)
  - `icon`: string(60), nullable (icon class font)
  - `order`: integer, default 0
  - `is_featured`: boolean, default false
  - `status`: string(30), default 'publish' (draft/publish)
  - `locale`: string(10), default 'vi', indexed (phục vụ đa ngôn ngữ vi/en_US)
  - `seo_title`: string(255), nullable
  - `seo_description`: text, nullable
  - `timestamps`

### 2.2. Migration Bảng Tags & Pivot Post-Tag (Từ khóa Bài viết)
- [x] Tạo file migration `create_tags_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(191)
  - `slug`: string(191), unique, indexed
  - `description`: text, nullable
  - `status`: string(30), default 'publish'
  - `locale`: string(10), default 'vi', indexed
  - `timestamps`

### 2.3. Migration Bảng Posts & Pivot Tables (Bài viết, Tin tức & Sản phẩm)
- [x] Tạo file migration `create_posts_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(255) (tiêu đề bài viết)
  - `slug`: string(255), unique, indexed
  - `description`: text, nullable (mô tả ngắn / tóm tắt)
  - `content`: longText, nullable (nội dung chi tiết bài viết)
  - `image`: string(500), nullable (đường dẫn hình ảnh đại diện, đồng bộ với `$post->image` của theme)
  - `author_id`: unsignedBigInteger, nullable, foreign key `users(id)` on delete set null
  - `status`: string(30), default 'publish'
  - `views`: unsignedBigInteger, default 0
  - `is_featured`: boolean, default false
  - `format_type`: string(30), default 'default' (standard, gallery, video)
  - `locale`: string(10), default 'vi', indexed
  - `gallery`: json, nullable (lưu trữ danh sách ảnh bổ sung từ `gallery_meta` cũ)
  - `seo_title`: string(255), nullable
  - `seo_description`: text, nullable
  - `published_at`: timestamp, nullable
  - `timestamps`
- [x] Tạo file migration `create_category_post_table` (pivot nhiều-nhiều Category - Post):
  - `post_id`: foreign key `posts(id)` on delete cascade
  - `category_id`: foreign key `categories(id)` on delete cascade
  - Primary composite key: `(post_id, category_id)`
- [x] Tạo file migration `create_post_tag_table` (pivot nhiều-nhiều Post - Tag):
  - `post_id`: foreign key `posts(id)` on delete cascade
  - `tag_id`: foreign key `tags(id)` on delete cascade
  - Primary composite key: `(post_id, tag_id)`

### 2.4. Migration Bảng Pages (Trang tĩnh)
- [x] Tạo file migration `create_pages_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(255)
  - `slug`: string(255), unique, indexed
  - `description`: text, nullable
  - `content`: longText, nullable
  - `template`: string(60), default 'default' (`default`, `inquiry`, `products`, `services`, `projects`)
  - `image`: string(500), nullable (đồng bộ `$page->image` của theme)
  - `author_id`: unsignedBigInteger, nullable, foreign key `users(id)` on delete set null
  - `gallery`: json, nullable
  - `status`: string(30), default 'publish'
  - `locale`: string(10), default 'vi', indexed
  - `seo_title`: string(255), nullable
  - `seo_description`: text, nullable
  - `timestamps`

### 2.5. Migration Bảng Slugs (Lưu trữ và Ánh xạ Dynamic URL Resolution)
- [x] Tạo file migration `create_slugs_table`:
  - `id`: unsignedBigInteger, primary key
  - `key`: string(255), unique, indexed (đường dẫn slug)
  - `reference_id`: unsignedBigInteger, indexed (ID bản ghi đích)
  - `reference_type`: string(255), indexed (Model class: `App\Models\Post`, `App\Models\Category`, `App\Models\Page`, `App\Models\Tag`)
  - `prefix`: string(50), nullable
  - `locale`: string(10), nullable
  - `timestamps`
  - Index tổ hợp: `index(['reference_type', 'reference_id'])`
  > *Ghi chú:* Bảng này lưu toàn bộ 283 slugs gốc của Botble, đảm bảo 100% khớp URL gốc của toàn bộ Post, Category, Page mà không làm mất index SEO trên Google Search Console.

### 2.6. Migration Bảng Menus & Menu Items (Điều hướng Header/Footer)
- [x] Tạo file migration `create_menus_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(255)
  - `slug`: string(120), unique
  - `location`: string(60), nullable, indexed (`main-menu`, `header-menu`, `footer-menu`)
  - `locale`: string(10), default 'vi'
  - `status`: string(30), default 'publish'
  - `timestamps`
- [x] Tạo file migration `create_menu_items_table`:
  - `id`: unsignedBigInteger, primary key
  - `menu_id`: foreign key `menus(id)` on delete cascade
  - `parent_id`: unsignedBigInteger, nullable, foreign key tự tham chiếu `menu_items(id)` on delete cascade
  - `title`: string(255)
  - `url`: string(500), nullable
  - `reference_id`: unsignedBigInteger, nullable
  - `reference_type`: string(255), nullable
  - `target`: string(20), default '_self'
  - `order`: integer, default 0
  - `icon_font`: string(100), nullable
  - `css_class`: string(120), nullable (ví dụ `rel=nofollow`)
  - `timestamps`

### 2.7. Migration Bảng Content Blocks (13 Khối Tĩnh Giao Diện Trang Chủ & Widget)
- [x] Tạo file migration `create_content_blocks_table`:
  - `id`: unsignedBigInteger, primary key
  - `alias`: string(191), unique (`main-slide`, `about-us`, `services`, `why-choose`, `featured-project`, `partners`, `map`, v.v.)
  - `name`: string(255)
  - `content`: longText, nullable (lưu HTML thô chứa các class CSS, SVG, lưới Bootstrap của theme)
  - `locale`: string(10), default 'vi', indexed
  - `status`: string(30), default 'publish'
  - `timestamps`

### 2.8. Migration Bảng Settings (Cấu hình Website & Tracking Scripts)
- [x] Tạo file migration `create_settings_table`:
  - `id`: unsignedBigInteger, primary key
  - `key`: string(191), unique
  - `value`: longText, nullable
  - `timestamps`

### 2.9. Migration Bảng Contacts (Lưu Trữ Liên Hệ & Khảo Sát / Inquiry Form)
- [x] Tạo file migration `create_contacts_table`:
  - `id`: unsignedBigInteger, primary key
  - `name`: string(120)
  - `email`: string(120)
  - `phone`: string(50), nullable
  - `address`: string(255), nullable
  - `subject`: string(255), nullable
  - `content`: text
  - `status`: string(30), default 'unread' (unread/read/replied)
  - `timestamps`

---

## 3. THỨ TỰ THỰC THI MIGRATIONS (EXECUTION ORDER)

Để đảm bảo không bị lỗi vi phạm ràng buộc khóa ngoại (Foreign Key Constraints) khi chạy lệnh `migrate`, các file migration phải được sắp xếp theo đúng thứ tự thời gian:

1. `create_categories_table`
2. `create_tags_table`
3. `create_posts_table` (tham chiếu `users.id`)
4. `create_category_post_table` (tham chiếu `posts.id`, `categories.id`)
5. `create_post_tag_table` (tham chiếu `posts.id`, `tags.id`)
6. `create_pages_table` (tham chiếu `users.id`)
7. `create_slugs_table`
8. `create_menus_table`
9. `create_menu_items_table` (tham chiếu `menus.id`, `menu_items.id`)
10. `create_content_blocks_table`
11. `create_settings_table`
12. `create_contacts_table`

---

## 4. TIÊU CHÍ HOÀN THÀNH (DoD)
1. Chạy lệnh `php artisan migrate:fresh` thành công 100%, tạo đầy đủ 12 bảng mới, không phát sinh bất kỳ lỗi foreign key hay datatype warning nào.
2. Kiểm tra sơ đồ quan hệ:
   - Category (cha - con đệ quy), Post - Category (nhiều - nhiều), Post - Tag (nhiều - nhiều).
   - Menu - Menu Item (1 - nhiều), Menu Item (cha - con đệ quy).
   - Post - User (nhiều - 1), Page - User (nhiều - 1).
3. 100% các cột phục vụ SEO (`seo_title`, `seo_description`), đa ngôn ngữ (`locale`), thư viện ảnh (`gallery` JSON) và ảnh đại diện (`image`) sẵn sàng tiếp nhận dữ liệu từ script ETL của TASK-03.
