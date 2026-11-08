# TASK-04: XÂY DỰNG GIAO DIỆN QUẢN TRỊ ADMIN CMS (FILAMENT)

> **Trọng tâm:** Phát triển giao diện quản trị Admin CMS hiện đại, tinh gọn và tốc độ cao bằng **Filament v5.11** (trên PHP 8.4 native, kiến trúc Unified Schema). Tập trung tối đa vào trải nghiệm người dùng quản lý nội dung thực tế (bài viết, chuyên mục, từ khóa, trang tĩnh, khối nội dung trang chủ, menu đa cấp, media library, liên hệ khách hàng và cấu hình website), bảo toàn 100% tính tương thích với dữ liệu legacy đã chuyển dịch từ `minhthanh2`.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-04
- **Phụ trách:** Fullstack / Filament Developer
- **Nền tảng:** Filament v5.11.x, Livewire v3, Tailwind CSS (PHP 8.4 Native)
- **Thư mục source code:** `/var/www/mtgroup/laravel13`
- **Thời gian ước tính:** 2 ngày
- **Trạng thái:** 🟢 Đã hoàn thành (100%)

---

## 2. CÁC ĐIỂM TINH CHỈNH KỸ THUẬT QUAN TRỌNG (TECHNICAL REFINEMENTS)

1. **Chuẩn hóa Đường dẫn Hình ảnh (`image` & `gallery`):**
   - Cột `image` trong `posts` và `pages` legacy lưu đường dẫn có tiền tố `/storage/uploads/...` (hoặc `uploads/...`). Trên Filament `FileUpload`, cần xử lý `formatStateUsing()` và `dehydrateStateUsing()` để đảm bảo khi sửa bài viết cũ vẫn preview được ảnh, và khi upload ảnh mới sẽ lưu định dạng chuẩn tương thích với frontend `theme_asset()` / `asset()`.
   - Cột `gallery` JSON của Botble lưu mảng object `[{"img": "/storage/uploads/...", "description": "..."}]`. Sử dụng `Repeater::make('gallery')` với 2 trường con `img` và `description` để bảo toàn 100% cấu trúc dữ liệu cũ thay vì dùng `FileUpload(multiple)` đơn thuần.
2. **Bảo tồn Nội dung HTML & Shortcodes:**
   - Dữ liệu `posts.content` và `pages.content` chứa các class CSS Bootstrap, style inline, bảng biểu và các shortcode (`[services]`, `[products]`, `[contacts]`, `[static-block alias="..."]`). Bộ soạn thảo cần hỗ trợ chế độ xem/sửa mã HTML thô hoặc RichEditor được cấu hình mở rộng để không tự động strip/loại bỏ các thẻ HTML và shortcode khi biên tập viên bấm Lưu.
3. **Cơ chế Đồng bộ Slugs Tự động & Validate Trùng lặp Chéo:**
   - Thay vì tạo 4 Observer riêng lẻ, xây dựng Trait dùng chung `App\Models\Concerns\HasSlug` tự động lắng nghe sự kiện `saved` và `deleted` của Eloquent, đồng bộ bản ghi vào bảng `slugs` qua morph relation `slugModel()`.
   - Vì route `/{slug}` là dynamic catch-all chung cho cả Post, Category, Page, Form kiểm tra unique slug phải validate chéo trên bảng `slugs` (`key`) để tránh trường hợp 1 Post mới trùng slug với 1 Category hoặc Page hiện có.
4. **Chuẩn hóa Menu Locations:**
   - Đồng bộ danh sách vị trí hiển thị khớp 100% với 6 menu thực tế đã map từ ETL: `product-menu`, `project-menu`, `product-menu-1`, `product-en`, `main-menu`, `main-menu-en`.
5. **Content Blocks Đa Ngôn Ngữ & Code Editor:**
   - 13 Content Blocks (7 tiếng Việt `vi`, 6 tiếng Anh `en_US`) lưu trữ toàn bộ markup HTML phức tạp của theme trang chủ (slider, icon, SVG, counter). Sử dụng Textarea Monospace / Code Editor và khóa cột `alias` ở chế độ readonly khi cập nhật.
6. **Media Library Trực tiếp từ Public Storage:**
   - 880 tệp tin ảnh/media cũ đã được copy về `/var/www/mtgroup/laravel13/storage/app/public/uploads`. Xây dựng trang Media Manager duyệt trực tiếp thư mục vật lý này (xem ảnh, tìm kiếm theo tên, upload ảnh mới, xóa file và sao chép nhanh URL `/storage/uploads/...`) thay vì phụ thuộc cứng vào bảng `media` Spatie.
7. **Kiến trúc Filament v5.11 Unified Schema:**
   - Tuân thủ cấu trúc của Filament v5: Sử dụng `Filament\Schemas\Schema` cho Form/Infolist, các layout components từ `Filament\Schemas\Components\*`, inputs từ `Filament\Forms\Components\*`, và Table Actions từ `Filament\Actions\*`.

---

## 3. CHECKLIST CHI TIẾT

### 3.1. Admin Panel Branding & Dashboard Overview
- [x] **Tùy biến Branding Admin Panel ([AdminPanelProvider.php](file:///var/www/mtgroup/laravel13/app/Providers/Filament/AdminPanelProvider.php)):**
  - Title: "Hệ Thống Quản Trị - Minh Thành Group".
  - Brand Logo: `/storage/uploads/gst-logo-01.png` (chiều cao 45px).
  - Favicon: `/storage/uploads/gst-logo-01.png`.
  - Màu chủ đạo: Primary Amber / Indigo sang trọng, hỗ trợ Dark Mode toggle.
  - Phân nhóm Navigation rõ ràng: *Quản lý nội dung*, *Điều hướng & Khối tĩnh*, *Tương tác khách hàng*, *Hệ thống*.
- [x] **Xây dựng Dashboard Widgets:**
  - `StatsOverviewWidget`:
    - Tổng số bài viết (`Post::count()`).
    - Tổng số chuyên mục (`Category::count()`).
    - Tổng số trang tĩnh (`Page::count()`).
    - Liên hệ mới chưa đọc (`Contact::where('status', 'unread')->count()`) kèm badge cảnh báo màu đỏ.
  - `LatestPostsWidget`: Bảng danh sách 5 bài viết mới nhất và 5 bài viết nhiều lượt xem nhất.
  - `LatestContactsWidget`: Bảng 5 liên hệ mới nhất gửi từ website.

### 3.2. Trait Tự Động Đồng Bộ Slugs (`HasSlug`)
- [x] Tạo Trait `app/Models/Concerns/HasSlug.php`:
  - Hook vào sự kiện Eloquent `saved`: Tự động tạo hoặc cập nhật bản ghi trong bảng `slugs` (`key = $model->slug`, `reference_id = $model->id`, `reference_type = get_class($model)`, `locale = $model->locale`).
  - Hook vào sự kiện Eloquent `deleted`: Tự động xóa bản ghi tương ứng trong bảng `slugs`.
  - Tích hợp Trait vào 4 Model: `Post`, `Category`, `Tag`, `Page`.

### 3.3. Post Resource (Quản lý Bài viết & Tin tức)
- [x] Tạo Resource: `php artisan make:filament-resource Post`
- [x] **Cấu hình Table:**
  - Cột: `image` (ImageColumn thumbnail vuông 50x50), `name` (Tiêu đề, searchable, sortable, limit 60 ký tự), `categories` (Badge tags), `locale` (Badge `vi` xanh lá / `en_US` xanh dương), `views` (Số lượt xem), `is_featured` (Toggle nhanh), `status` (Select/Toggle Published / Draft), `published_at` (Datetime formatted).
  - Bộ lọc (Filters):
    - Lọc theo Chuyên mục (`SelectFilter::make('categories')`).
    - Lọc theo Ngôn ngữ (`SelectFilter::make('locale')`: `vi`, `en_US`).
    - Lọc theo Trạng thái (`SelectFilter::make('status')`: `publish`, `draft`).
    - Lọc theo Cờ nổi bật (`TernaryFilter::make('is_featured')`).
  - Actions: EditAction, DeleteAction, BulkAction xóa bài viết.
- [x] **Cấu hình Form (Schema):**
  - Section Thông tin cơ bản:
    - Tiêu đề bài viết (`name`): Bắt buộc, tự động tạo slug khi gõ nếu tạo mới.
    - Slug (`slug`): Bắt buộc, kiểm tra unique trên `posts` và validate không trùng với bảng `slugs` (ngoại trừ chính nó).
    - Ngôn ngữ (`locale`): Select `vi` (Tiếng Việt) / `en_US` (Tiếng Anh), default `vi`.
    - Chọn chuyên mục (`categories`): Multi-select relation picker.
    - Chọn từ khóa (`tags`): Multi-select relation picker hỗ trợ tạo nhanh tag mới.
    - Mô tả tóm tắt (`description`): Textarea 3 dòng.
  - Section Nội dung chi tiết:
    - Trình soạn thảo Rich Editor (TipTap / Code View) hỗ trợ chèn ảnh, bảng biểu, format HTML đầy đủ và không strip các thẻ inline hoặc shortcode.
  - Section Hình ảnh & Thư viện:
    - Ảnh đại diện (`image`): FileUpload vào disk `public` thư mục `uploads`, hỗ trợ load ảnh legacy có tiền tố `/storage/`.
    - Thư viện ảnh (`gallery`): `Repeater` gồm 2 field `img` (FileUpload/TextInput) và `description` (TextInput), bảo toàn mảng JSON của Botble.
  - Section Cấu hình SEO & Xuất bản:
    - `seo_title`: TextInput (tối đa 70 ký tự, gợi ý counter).
    - `seo_description`: Textarea (tối đa 160 ký tự).
    - `status`: Select (`publish`, `draft`).
    - `is_featured`: Toggle bài viết nổi bật.
    - `published_at`: DateTimePicker.

### 3.4. Category Resource (Quản lý Chuyên mục Đa cấp)
- [x] Tạo Resource: `php artisan make:filament-resource Category`
- [x] **Cấu hình Table:**
  - Cột: `name` (Tên danh mục), `slug`, `parent.name` (Danh mục cha), `locale` (Badge), `order` (Số thứ tự sắp xếp), `posts_count` (Số lượng bài viết đính kèm), `status` (Toggle).
  - Filters: Lọc theo ngôn ngữ `locale`, lọc theo trạng thái `status`.
  - Actions: EditAction, DeleteAction.
- [x] **Cấu hình Form (Schema):**
  - Tên chuyên mục (`name`): Bắt buộc, auto-fill slug.
  - Slug (`slug`): Bắt buộc, validate unique chéo.
  - Danh mục cha (`parent_id`): Select phân cấp (loại trừ chính nó khi đang edit để tránh vòng lặp đệ quy vô tận).
  - Ngôn ngữ (`locale`): `vi` / `en_US`.
  - Thứ tự hiển thị (`order`): NumberInput (default 0).
  - Biểu tượng Font Icon (`icon`): TextInput (ví dụ `ti-layers`, `fi flaticon-worker`).
  - Mô tả chuyên mục (`description`): Textarea.
  - Section SEO Meta: `seo_title`, `seo_description`.
  - Trạng thái (`status`): Select `publish` / `draft`.

### 3.5. Tag Resource (Quản lý Từ khóa Bài viết)
- [x] Tạo Resource: `php artisan make:filament-resource Tag`
- [x] **Cấu hình Table:**
  - Cột: `name` (Tên thẻ tag), `slug`, `locale`, `posts_count` (Số bài viết liên kết), `created_at`.
  - Filters: Lọc theo `locale`.
- [x] **Cấu hình Form (Schema):**
  - Tên từ khóa (`name`), Slug (`slug`), Ngôn ngữ (`locale`), Mô tả ngắn (`description`).

### 3.6. Page Resource (Quản lý Trang tĩnh)
- [x] Tạo Resource: `php artisan make:filament-resource Page`
- [x] **Cấu hình Table:**
  - Cột: `name` (Tiêu đề trang), `slug`, `template` (Badge template: `default`, `services`, `products`, `projects`, `inquiry`), `locale`, `status`, `updated_at`.
- [x] **Cấu hình Form (Schema):**
  - Tiêu đề trang (`name`), Slug (`slug`, validate unique chéo).
  - Chọn giao diện hiển thị (`template`): Select (`default` - Mặc định, `services` - Dịch vụ, `products` - Sản phẩm, `projects` - Dự án, `inquiry` - Form liên hệ khảo sát).
  - Ngôn ngữ (`locale`): `vi` / `en_US`.
  - Nội dung HTML chi tiết (`content`): Rich Editor / Code Editor hỗ trợ shortcode.
  - Ảnh đại diện (`image`) & Thư viện gallery (`gallery` repeater).
  - Section SEO Meta: `seo_title`, `seo_description`.
  - Trạng thái (`status`): `publish` / `draft`.

### 3.7. Content Block Resource (Quản lý 13 Khối Tĩnh Giao Diện Trang Chủ)
- [x] Tạo Resource: `php artisan make:filament-resource ContentBlock`
- [x] **Cấu hình Table:**
  - Cột: `alias` (Khóa định danh tĩnh), `name` (Tên khối gợi nhớ), `locale` (Badge), `status` (Toggle), `updated_at`.
  - Filters: Lọc theo ngôn ngữ (`vi` / `en_US`).
- [x] **Cấu hình Form (Schema):**
  - Khóa định danh (`alias`): TextInput, readonly khi Edit để tránh gãy giao diện theme frontend.
  - Tên gợi nhớ (`name`): TextInput mô tả công dụng (ví dụ: *Banner Slide Trang Chủ*, *Về Chúng Tôi*).
  - Ngôn ngữ (`locale`): Select `vi` / `en_US`.
  - Bộ soạn thảo HTML thô (`content`): Textarea font monospace / HTML Code Editor có syntax highlight, giữ nguyên 100% thẻ `<svg>`, `<div>`, lưới `col-lg-*`, class `odometer`, slider.
  - Trạng thái (`status`): `publish` / `draft`.

### 3.8. Menu Resource & Menu Items Builder (Cây Điều hướng Đa cấp)
- [x] Tạo Resource: `php artisan make:filament-resource Menu`
- [x] **Cấu hình Menu Table:**
  - Cột: `name` (Tên menu), `slug`, `location` (Vị trí hiển thị), `locale`, `items_count` (Số mục con).
  - Danh sách 6 vị trí chuẩn:
    - `main-menu` (Menu chính Tiếng Việt)
    - `main-menu-en` (Menu chính Tiếng Anh)
    - `product-menu` (Menu danh mục sản phẩm)
    - `project-menu` (Menu dự án)
    - `product-menu-1` (Menu phụ sản phẩm 1)
    - `product-en` (Menu sản phẩm tiếng Anh)
- [x] **Cấu hình Menu Items Management (Relation Manager hoặc Custom Builder):**
  - Danh sách Menu Items phân cấp theo `order` và `parent_id`.
  - Form thêm/sửa Menu Item:
    - Tiêu đề hiển thị (`title`).
    - Loại liên kết: Custom Link (nhập URL tự do) hoặc liên kết nhanh (chọn từ Category / Page có sẵn tự động sinh URL).
    - Đường dẫn (`url`): Hỗ trợ `/`, `#` hoặc slug đầy đủ.
    - Cấp cha (`parent_id`): Select menu cha trong cùng menu.
    - Thứ tự (`order`): Integer.
    - Mở trong tab mới (`target`): Select `_self` / `_blank`.
    - Biểu tượng Font (`icon_font`): Flaticon / Themify icon class.
    - Tùy chỉnh CSS class (`css_class`): Hỗ trợ `rel=nofollow` hoặc class đặc thù.

### 3.9. Contact Resource (Quản lý Khách hàng Liên hệ & Khảo sát)
- [x] Tạo Resource: `php artisan make:filament-resource Contact`
- [x] **Cấu hình Table:**
  - Cột: `name` (Họ và tên khách hàng), `email`, `phone` (Số điện thoại), `subject` (Tiêu đề liên hệ / dịch vụ cần tư vấn), `status` (Badge: `unread` - Đỏ, `read` - Vàng, `replied` - Xanh lá), `created_at` (Thời gian gửi).
  - Filters: Lọc theo trạng thái `status`.
  - Actions:
    - ViewAction: Mở Modal hoặc Infolist xem đầy đủ nội dung liên hệ, địa chỉ và ghi chú.
    - Action đánh dấu đã đọc (`mark_as_read`) / đã phản hồi (`mark_as_replied`).
    - DeleteAction.
- [x] Vô hiệu hóa tính năng Create (bản ghi chỉ được tạo tự động từ form frontend ngoài trang chủ/inquiry).

### 3.10. Media Manager Custom Page (Quản lý Thư mục & Tệp tin Tĩnh)
- [x] Tạo Custom Filament Page: `php artisan make:filament-page MediaManager`
- [x] **Chức năng Media Browser:**
  - Đọc trực tiếp danh sách tệp tin trong `storage/app/public/uploads` (hơn 880 ảnh thực tế đã migrate).
  - Lưới hiển thị ảnh trực quan (Thumbnail preview, tên file, kích thước file, ngày upload).
  - Bộ lọc tìm kiếm nhanh theo tên file.
  - Upload tệp tin kéo thả (hỗ trợ JPG, PNG, WebP, SVG, PDF).
  - Nút thao tác một chạm: "Sao chép URL" (`/storage/uploads/...` hoặc full asset URL) để dán trực tiếp vào bộ soạn thảo bài viết.
  - Xóa ảnh không sử dụng (kèm cảnh báo xác nhận an toàn).

### 3.11. General Settings Page (Cấu hình Toàn Trang)
- [x] Tạo Custom Settings Page: `php artisan make:filament-page GeneralSettings`
- [x] **Giao diện Cấu hình gom theo Tabs:**
  - **Tab 1: Nhận diện Thương hiệu & SEO Chung:**
    - Tên website (`site_title`): Mặc định "Minh Thành Group".
    - Slogan website (`site_slogan`).
    - Tiêu đề SEO trang chủ (`seo_title`).
    - Mô tả SEO trang chủ (`seo_description`).
    - Bản quyền chân trang (`theme-ripple-copyright`).
    - Logo chính (`theme-ripple-logo` / `logo`): FileUpload hoặc chọn ảnh.
    - Favicon website (`favicon`).
  - **Tab 2: Thông tin Liên hệ & 4 Văn phòng:**
    - Hotline chính (`hotline_main`): `0963189981`.
    - Email hỗ trợ (`admin_email` / `email_support`): `quantrac.mtg@gmail.com`.
    - Mã số thuế (`tax_code`): `2301052220`.
    - Trụ sở chính Bắc Ninh (`office_bac_ninh`): "Số 32 Nguyễn Khuyến, Phường Võ Cường, TP. Bắc Ninh".
    - VPĐD Hà Nội (`office_ha_noi`): "Tòa nhà Lạc Hồng, Ngõ 27 Lê Văn Lương, Nhân Chính, Thanh Xuân, Hà Nội".
    - Chi nhánh Miền Trung (`office_thanh_hoa`): "Số 46 Đặng Văn Hỷ, An Hưng, TP. Thanh Hóa".
    - VPĐD TP. Hồ Chí Minh (`office_hcm`): "Số 20 Đường 1 KDC CityLand Park Hills, Gò Vấp, TP. HCM".
  - **Tab 3: Mã Nhúng Tracking & Tiện Ích:**
    - Google Tag Manager ID (`gtm_id`): `GTM-MHFCDPPW`.
    - Facebook Chat Plugin Page ID (`fb_page_id`): `100367992250913`.
    - Mã nhúng Header tùy biến (`custom_header_scripts`).
    - Mã nhúng Footer tùy biến (`custom_footer_scripts`).
- [x] Cơ chế lưu trữ: Tự động lưu/cập nhật vào bảng `settings` qua `Setting::updateOrCreate(['key' => $k], ['value' => $v])`.

### 3.12. User & Role Management (Phân quyền Quản trị Spatie)
- [x] Cập nhật phương thức `canAccessPanel()` trên Model [User.php](file:///var/www/mtgroup/laravel13/app/Models/User.php#L23) kiểm tra quyền truy cập Panel theo vai trò Spatie (`super_admin`, `editor`).
- [x] Tạo Resource: `php artisan make:filament-resource User`
  - Quản lý danh sách thành viên quản trị (Tên, Email, Vai trò Spatie, Ngày tạo).
  - Phân quyền theo vai trò:
    - **Super Admin:** Toàn quyền hệ thống (Post, Category, Page, Block, Menu, Settings, Users).
    - **Editor:** Chỉ quản lý Bài viết, Chuyên mục, Từ khóa, Trang tĩnh, Media, Liên hệ khách hàng. Ẩn UserResource, ContentBlockResource và GeneralSettingsPage bằng phương thức `canAccess(): bool`.

---

## 4. TIÊU CHÍ HOÀN THÀNH (DEFINITION OF DONE - DoD)
1. ✅ Toàn bộ các Resource (`Post`, `Category`, `Tag`, `Page`, `ContentBlock`, `Menu`, `Contact`, `User`) và 2 Custom Pages (`MediaManager`, `GeneralSettings`) hoạt động trơn tru 100% trên giao diện Filament v5.11, không phát sinh bất kỳ lỗi 500 hay deprecation warning nào.
2. ✅ Trait `HasSlug` tự động tạo/sửa/xóa bản ghi trong bảng `slugs` khi thao tác CRUD trên Post, Category, Tag, Page; Form validate ngăn chặn thành công việc tạo slug trùng lặp chéo giữa các thực thể.
3. ✅ Dữ liệu ảnh đại diện và thư viện `gallery` JSON của các bài viết cũ hiển thị chính xác trên form Edit, bài viết mới lưu đúng định dạng tương thích với frontend theme `ripple`.
4. ✅ Bộ soạn thảo nội dung của `Post`, `Page` và `ContentBlock` bảo lưu nguyên vẹn các thẻ HTML phức tạp và shortcode cũ mà không bị strip mã.
5. ✅ Quản trị viên có thể truy cập `MediaManager` để duyệt ảnh cũ, upload ảnh mới và copy đường dẫn một cách thuận tiện.
6. ✅ Trang Cấu hình `GeneralSettings` lưu và đọc chính xác các thông số nhận diện, hotline, địa chỉ 4 văn phòng và script tracking GTM/Facebook.
7. ✅ Phân quyền Spatie Permission hoạt động chuẩn mực: Super Admin nắm toàn quyền, Editor bị giới hạn đúng phạm vi nghiệp vụ.
