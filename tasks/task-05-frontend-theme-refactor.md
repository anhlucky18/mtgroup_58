# TASK-05: BÓC TÁCH & CHUẨN HÓA FRONTEND THEME SANG BLADE THUẦN

> **Trọng tâm:** Chuyển đổi và tái sử dụng 100% giao diện HTML/CSS/JS của theme đang kích hoạt thực tế (**Theme `ripple`**), loại bỏ sạch sẽ các hàm helper đặc thù của Botble CMS, thay thế bằng Laravel Blade Components và Views chuẩn mực. Giao diện sau khi chuyển đổi phải đảm bảo giống 100% nguyên trạng, hiển thị sắc nét, bảo toàn liên kết SEO và hỗ trợ đa ngôn ngữ hoàn chỉnh.

---

## 1. THÔNG TIN TASK
- **Mã Task:** TASK-05
- **Phụ trách:** Frontend / Fullstack Developer
- **Theme nguồn:**
  - Views/Partials/Layouts: `/var/www/mtgroup/minhthanh3/platform/themes/ripple/`
  - Thư mục Assets thực tế: `/var/www/mtgroup/minhthanh3/public/themes/ripple/`
- **Thư mục Assets đích:** `/var/www/mtgroup/laravel13/public/themes/ripple/`
- **Công nghệ đích:** Laravel 13 Blade Components, Raw CSS/JS Assets, Dynamic Slug Route Resolution & Localization
- **Thời gian ước tính:** 3 ngày
- **Trạng thái:** 🟢 Hoàn thành (100%)

---

## 2. CÁC ĐIỂM TINH CHỈNH KỸ THUẬT QUAN TRỌNG (TECHNICAL REFINEMENTS)

1. **Chuẩn hóa Thư mục Nguồn Assets Thực tế:**
   - Không lấy assets từ `platform/themes/ripple/public/` (chỉ chứa file mẫu ban đầu), mà phải sao chép từ **`/var/www/mtgroup/minhthanh3/public/themes/ripple/`** – nơi chứa trọn bộ CSS/JS tùy biến của Minh Thành Group (`style_custom.css`, `style.integration.css`, `jquery-plugin-collection.js`, `script.js`, thư mục `images_mt/`, v.v.).
   - Sao chép toàn bộ thư mục `plugins/` (chứa Bootstrap, FontAwesome, jQuery) và thư mục cờ quốc gia từ `/var/www/mtgroup/minhthanh3/public/vendor/core/images/flags/` sang `/var/www/mtgroup/laravel13/public/vendor/core/images/flags/` để hiển thị trên bộ chuyển ngôn ngữ.
   - Thiết lập symlink `public/themes/minhthanh3` -> `public/themes/ripple` nhằm tránh 404 cho bất kỳ ảnh cũ nào hardcode URL theo đường dẫn theme cũ.

2. **Cơ chế Đa ngôn ngữ (`vi` & `en_US`) Toàn diện:**
   - Sao chép file ngôn ngữ gốc `/var/www/mtgroup/minhthanh3/platform/themes/ripple/lang/vi.json` (hơn 110 chuỗi dịch giao diện tĩnh) sang `/var/www/mtgroup/laravel13/lang/vi.json`.
   - Hỗ trợ routing đa ngôn ngữ: Tiếng Việt (mặc định `/` hoặc `/{slug}`) và Tiếng Anh (prefix `/en` hoặc `/en/{slug}`).
   - Khi lọc `ContentBlock` theo `alias`, **bắt buộc phải kèm điều kiện `locale`** (`where('alias', $alias)->where('locale', app()->getLocale())`), bởi vì cả 7 khối tiếng Việt và 6 khối tiếng Anh đều dùng chung alias định danh (`main-slide`, `about-us`, `services`, `why-choose`, `featured-project`, `partner`).
   - Xây dựng component `<x-language-switcher />` hiển thị cờ, tên ngôn ngữ và tự động sinh link chuyển đổi tương ứng giữa bản ghi Tiếng Việt và Tiếng Anh.

3. **Khắc phục Bẫy Kỹ thuật trong Helper Xử lý Ảnh (`get_object_image`):**
   - Tuyệt đối không dùng `ltrim($path, '/storage/')` vì hàm `ltrim()` trong PHP hoạt động theo danh sách ký tự đơn lẻ (charlist), dẫn tới nguy cơ cắt cụt tên thư mục nếu bắt đầu bằng các chữ cái `s, t, o, r, a, g, e` (ví dụ `storage/services/...` bị cắt thành `ervices/...`).
   - Thay vào đó sử dụng `Str::replaceFirst('/storage/', '', $imagePath)` hoặc Regex `preg_replace('#^/?storage/#', '', ltrim($imagePath, '/'))`, đồng thời tự động trả về ảnh placeholder mặc định `/themes/ripple/images/no-image.jpg` nếu giá trị ảnh rỗng.

4. **Sửa các Lỗi Tiềm Ẩn Fatal/Null-pointer trong Blade Gốc của Botble:**
   - **User/Author:** Blade cũ gọi `$post->user->username` và `$post->user->getFullName()`. Bảng `users` và Model `Post` mới dùng quan hệ `author()` và trường `name`. Cần chuẩn hóa thành `$post->author?->name` (hoặc bổ sung alias method trên Model để tương thích).
   - **Null Category:** Thêm kiểm tra an toàn `@if($post->categories->isNotEmpty())` trước khi gọi `$post->categories->first()->slug` để tránh lỗi 500 khi gặp bài viết chưa phân loại.
   - **Lỗi biến `$post` ngoài vòng lặp ở `search.blade.php`:** Trong sidebar của trang tìm kiếm cũ, code tham chiếu `$post->categories` và `$post->tags` ngoài vòng lặp `@foreach`. Cần thay thế bằng việc query trực tiếp danh mục và tag hoạt động từ database.
   - **SweetAlert Error Popup:** Trong `footer.blade.php`, tiêu đề hiển thị lỗi đang bị hardcode nhầm thành `{{ __('Success') }}`. Cần sửa lại thành `{{ __('Error') }}`.

5. **Đồng bộ Thông số Động với Trang Quản trị `GeneralSettings` (TASK-04):**
   - Không hardcode tĩnh các thông tin doanh nghiệp, hotline, địa chỉ 4 văn phòng, Google Tag Manager ID và Facebook Customer Chat ID trong header/footer.
   - Đọc trực tiếp từ bảng `settings` thông qua helper `setting('key', 'default')`:
     - `hotline_main` (`0963189981`), `admin_email` (`quantrac.mtg@gmail.com`), `tax_code` (`2301052220`)
     - `office_bac_ninh`, `office_ha_noi`, `office_thanh_hoa`, `office_hcm`
     - `gtm_id` (`GTM-MHFCDPPW`), `fb_page_id` (`100367992250913`)
     - `theme-ripple-logo`, `favicon`, `theme-ripple-copyright`

6. **Chuẩn hóa Form Liên hệ & Sửa Bẫy AJAX trong `script.js`:**
   - Trong `inquiry.blade.php`, trường nội dung sử dụng `name="note"`. Controller cần chấp nhận linh hoạt cả `content` và `note` (`$request->input('content') ?? $request->input('note')`).
   - Trong `public/themes/ripple/js/script.js` (dòng 577), code JS cũ gửi AJAX cố định tới file `mail-contact.php`. Cần cấu hình form gửi đúng tới `route('public.send.contact')` kèm CSRF token, hỗ trợ trả về JSON khi là request AJAX hoặc redirect kèm Flash Session (`success_msg`) khi submit thông thường.

7. **Shortcode Engine Hỗ trợ Render Đa Tầng (Recursive Parser):**
   - Hỗ trợ phân tích cú pháp shortcode lồng nhau: một trang tĩnh (`Page`) có thể chứa shortcode `[static-block alias="..."]`, `[contacts][/contacts]`, `[services][/services]`, `[products][/products]`. Bộ parser cần hỗ trợ biên dịch đệ quy các shortcode này sang HTML hoàn chỉnh.

---

## 3. CHECKLIST CHI TIẾT

### 3.1. Di chuyển & Quản lý Assets (CSS, JS, Fonts, Images)
- [x] Sao chép toàn bộ thư mục tĩnh từ `/var/www/mtgroup/minhthanh3/public/themes/ripple/` sang `/var/www/mtgroup/laravel13/public/themes/ripple/`:
  - `public/themes/ripple/css/` (`bootstrap.min.css`, `themify-icons.css`, `flaticon.css`, `animate.css`, `owl.carousel.css`, `slick.css`, `style_custom.css`, `style.integration.css`, v.v.)
  - `public/themes/ripple/js/` (`jquery-plugin-collection.js`, `script.js`, `ripple.js`, `custom.min.js`, `sweetalert.min.js`, v.v.)
  - `public/themes/ripple/fonts/` (Flaticon, Themify, Glyphicons, Slick)
  - `public/themes/ripple/images/` (`GST_logo-01.png`, `404_error.jpg`, thư mục `images_mt/`, v.v.)
  - `public/themes/ripple/plugins/` (Bootstrap, FontAwesome, Ionicons, jQuery Plugins)
- [x] Sao chép thư mục cờ quốc gia từ `/var/www/mtgroup/minhthanh3/public/vendor/core/images/flags/` sang `/var/www/mtgroup/laravel13/public/vendor/core/images/flags/`.
- [x] Sao chép file ngôn ngữ giao diện từ `/var/www/mtgroup/minhthanh3/platform/themes/ripple/lang/vi.json` sang `/var/www/mtgroup/laravel13/lang/vi.json`.
- [x] Tạo symlink `public/themes/minhthanh3` -> `public/themes/ripple` nhằm đảm bảo các ảnh cũ trong database trỏ theo URL theme cũ không bị 404.
- [x] Xây dựng các helper toàn cục trong `app/Helpers/theme.php`:
  ```php
  if (!function_exists('theme_asset')) {
      function theme_asset(string $path): string {
          return asset('themes/ripple/' . ltrim($path, '/'));
      }
  }

  if (!function_exists('setting')) {
      function setting(string $key, $default = null) {
          return \App\Models\Setting::get($key, $default);
      }
  }

  if (!function_exists('get_object_image')) {
      function get_object_image(?string $imagePath, string $size = 'medium'): string {
          if (empty($imagePath)) {
              return theme_asset('images/no-image.jpg');
          }
          if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
              return $imagePath;
          }
          $cleanPath = preg_replace('#^/?storage/#', '', ltrim($imagePath, '/'));
          return asset('storage/' . $cleanPath);
      }
  }
  ```
- [x] Đăng ký file helper `app/Helpers/theme.php` vào mục `autoload.files` trong `composer.json` và chạy `composer dump-autoload`.

---

### 3.2. Dựng Cấu trúc Master Layout & Partials Blade Chuẩn
- [x] Xây dựng `resources/views/layouts/master.blade.php`:
  - Thẻ `<head>`: Canonical URL, thẻ Meta SEO qua `<x-seo-meta />`, nạp Font Roboto, nạp favicon từ `setting('favicon', '/storage/uploads/gst-logo-01.png')`.
  - Nhúng Google Tag Manager (`setting('gtm_id', 'GTM-MHFCDPPW')`) trong `<head>` và `<noscript>` ngay sau thẻ `<body>`.
  - Nạp đầy đủ CSS tĩnh theo đúng thứ tự của theme: `bootstrap.min.css`, `font-awesome.min.css`, `themify-icons.css`, `flaticon.css`, `animate.css`, `owl.carousel.css`, `owl.theme.css`, `slick.css`, `slick-theme.css`, `swiper.min.css`, `odometer-theme-default.css`, `owl.transitions.css`, `jquery.fancybox.css`, `style_custom.css`, `style.integration.css` kèm `@stack('styles')`.
  - Khối Header: `@include('layouts.partials.header')`.
  - Khu vực Preloader bánh răng SVG.
  - Khu vực Nội dung chính: `@yield('content')`.
  - Khối CTA & Footer: `@include('layouts.partials.footer')`.
  - Bộ thẻ nạp Scripts tĩnh theo đúng thứ tự: `plugins/jquery/jquery.min.js`, `plugins/bootstrap/js/bootstrap.min.js`, `plugins/overflow-text.js`, `plugins/jquery.parallax-1.1.3.js`, `custom.min.js`, `ripple.js`, `sweetalert.min.js`, `jquery-plugin-collection.js`, `script.js` kèm `@stack('scripts')`.
  - Khối tiện ích nổi: Nút gọi điện rung lắc CSS (`#phone`, hotline lấy từ `setting('hotline_main', '0963189981')`), Facebook Customer Chat Plugin lấy từ `setting('fb_page_id', '100367992250913')`.
  - Xử lý thông báo SweetAlert từ Flash Session (`success_msg`, `error_msg`).

- [x] Xây dựng `resources/views/layouts/partials/header.blade.php`:
  - Logo thương hiệu lấy từ `setting('theme-ripple-logo', '/storage/uploads/gst-logo-01.png')`.
  - Khu vực menu điều hướng: Gọi View Component `<x-navigation-menu />`.
  - Khu vực Search & Hotline: Hotline hiển thị từ `setting('hotline_main', '0963189981')`, Quick Search Form trỏ tới `route('public.search')`.
  - Nút chuyển đổi ngôn ngữ: Gọi View Component `<x-language-switcher />`.

- [x] Xây dựng `resources/views/layouts/partials/footer.blade.php`:
  - Khối CTA Section:
    - Địa chỉ 4 văn phòng lấy từ cài đặt: `setting('office_bac_ninh')`, `setting('office_ha_noi')`, `setting('office_thanh_hoa')`, `setting('office_hcm')`.
    - Thông tin liên hệ: `setting('hotline_main')`, `setting('admin_email')`, `setting('tax_code')`.
  - Khối Lower Footer: Dòng bản quyền copyright động từ `setting('theme-ripple-copyright')`.

---

### 3.3. Xây dựng Hệ thống Blade Components Cốt Lõi

#### 1. Bộ Xử lý Shortcode Động (`<x-content-render :content="$content" />`)
- [x] Xây dựng Blade Component hoặc Service `App\Services\ShortcodeParser`:
  - Xử lý `[static-block alias="..."]`: Tìm kiếm trong bảng `content_blocks` theo `alias` và `locale` hiện tại (`app()->getLocale()`). Trả về HTML thô và đệ quy parse nếu khối có chứa shortcode con.
  - Xử lý `[services][/services]`: Render view `views/services.blade.php`.
  - Xử lý `[products][/products]`: Render view `views/products.blade.php`.
  - Xử lý `[projects][/projects]`: Render view `views/projects.blade.php`.
  - Xử lý `[contacts][/contacts]`: Render form liên hệ `views/inquiry.blade.php`.
  - Xử lý `[google-map]` và `[youtube-video]`.

#### 2. Component Menu Điều Hướng Đa Cấp (`<x-navigation-menu />`)
- [x] Xây dựng Blade Component `App\View\Components\NavigationMenu`:
  - Tự động xác định menu theo ngôn ngữ hiện tại: `main-menu` (cho Tiếng Việt) hoặc `main-menu-en` (cho Tiếng Anh).
  - Tải cây phân cấp `MenuItem` (`rootItems` kèm eager-loading `children`).
  - Render cấu trúc HTML khớp 100% với giao diện theme:
    - Cấp cha `<ul class="nav navbar-nav">` và cấp con `<ul class="sub-menu">`.
    - Hỗ trợ class `menu-item-has-children`, `$item->css_class`, `$item->icon_font` và trạng thái `active` theo URL hiện tại.

#### 3. Component Chuyển đổi Ngôn ngữ (`<x-language-switcher />`)
- [x] Xây dựng Blade Component `App\View\Components\LanguageSwitcher`:
  - Hiển thị cờ quốc gia (`vi.png`, `us.png`) và tên ngôn ngữ hiện tại.
  - Chuyển hướng URL thông minh:
    - Nếu đang ở trang chủ: Chuyển giữa `/` và `/en`.
    - Nếu đang ở bài viết/danh mục/trang tĩnh: Tra cứu slug tương ứng của ngôn ngữ đích để chuyển đổi chính xác (tránh 404).

#### 4. Component SEO & OpenGraph Meta (`<x-seo-meta />`)
- [x] Xây dựng Blade Component `App\View\Components\SeoMeta`:
  - Đọc các thông số: Tiêu đề (`seo_title` ?? `name`), Mô tả (`seo_description` ?? `description`), Ảnh đại diện (`image`), Canonical URL.
  - Tự động xuất đầy đủ thẻ chuẩn SEO: `<title>`, `<meta name="description">`, `<link rel="canonical">`, `<meta property="og:title">`, `<meta property="og:description">`, `<meta property="og:image">`.

---

### 3.4. Thiết lập Hệ thống Routing & Dynamic URL Resolution
- [x] Cấu hình trong `routes/web.php` hỗ trợ cả 2 ngôn ngữ:
  ```php
  use App\Http\Controllers\HomeController;
  use App\Http\Controllers\PublicController;
  use App\Http\Controllers\ContactController;

  // Route xử lý gửi liên hệ
  Route::post('/send-contact', [ContactController::class, 'send'])->name('public.send.contact');
  Route::post('/contact/send', [ContactController::class, 'send']); // Alias tương thích

  // Group định tuyến hỗ trợ đa ngôn ngữ
  Route::group(['prefix' => '{locale?}', 'where' => ['locale' => 'en']], function () {
      Route::get('/', [HomeController::class, 'index'])->name('public.index');
      Route::get('/search', [PublicController::class, 'search'])->name('public.search');
      Route::get('/tag/{slug}', [PublicController::class, 'tag'])->name('public.tag');
      Route::get('/author/{username}', [PublicController::class, 'author'])->name('public.author');
      
      // Dynamic Catch-All bảo toàn 100% 283 slugs gốc của Botble
      Route::get('/{slug}', [PublicController::class, 'handleSlug'])->name('public.single');
  });
  ```
- [x] Viết hàm `handleSlug($slug)` trong `PublicController`:
  - Tra cứu `$slug` trong bảng `slugs`.
  - Nếu là `App\Models\Post` -> gọi hiển thị chi tiết bài viết.
  - Nếu là `App\Models\Category` -> gọi hiển thị danh mục bài viết.
  - Nếu là `App\Models\Page` -> gọi hiển thị trang tĩnh theo `template`.
  - Nếu là `App\Models\Tag` -> gọi hiển thị bài viết theo thẻ tag.
  - Nếu không tìm thấy -> trả về HTTP 404 (`errors.404`).

- [x] Viết Controller `ContactController@send`:
  - Validate dữ liệu (`name`, `email`, `phone`, `note`/`content`).
  - Lưu vào bảng `contacts` với trạng thái `unread`.
  - Trả về JSON thành công nếu request là AJAX, hoặc redirect back kèm `session()->flash('success_msg', 'Gửi thông tin liên hệ thành công!')`.

---

### 3.5. Ghép Nối Dữ Liệu Động Cho Từng Màn Hình

#### 1. Trang Chủ (`views/index.blade.php`)
- [x] Chuyển đổi `views/index.blade.php`:
  - Render các khối tĩnh qua `<x-content-render />`:
    - `[static-block alias="main-slide"]`
    - `[static-block alias="about-us"]`
    - `[static-block alias="services"]`
    - `[static-block alias="why-choose"]`
    - `[static-block alias="featured-project"]`
    - `[static-block alias="partner"]` (nếu có)
  - Đảm bảo hiển thị đầy đủ hiệu ứng: Swiper hero banner, hiệu ứng counter số đếm Odometer, slider đối tác và dự án nổi bật.

#### 2. Chi tiết Bài viết (`views/post.blade.php`)
- [x] Chuyển đổi `views/post.blade.php`:
  - Hiển thị tiêu đề bài viết, ngày xuất bản (`date_from_database` / `$post->created_at->format('M d, Y')`), chuyên mục đầu tiên (kèm null-safe check).
  - Tăng lượt xem bài viết (`$post->increment('views')`).
  - Render nội dung bài viết HTML thô (`{!! $post->content !!}`).
  - Hiển thị danh sách Tags liên kết với `route('public.tag')`.
  - Hiển thị 2 bài viết liên quan (Previous / Next Post) trong cùng chuyên mục.

#### 3. Danh mục Bài viết (`views/category.blade.php`)
- [x] Chuyển đổi `views/category.blade.php`:
  - Banner tiêu đề chuyên mục trong khối Page Title.
  - Danh sách bài viết hiển thị theo lưới Service Grids (`grid`), ảnh đại diện xử lý qua `get_object_image()`.
  - Phân trang chuẩn 12 bài/trang (`$posts->links()`), giữ nguyên markup CSS của theme.
  - Xử lý trạng thái rỗng ("Không có dữ liệu để hiển thị").

#### 4. Trang tĩnh & Form Khảo sát Liên hệ (`views/page.blade.php` & `views/inquiry.blade.php`)
- [x] Chuyển đổi `views/page.blade.php`:
  - Render tiêu đề và nội dung trang tĩnh thông qua `<x-content-render :content="$page->content" />`.
  - Tự động gọi view tương ứng nếu trang có `template` đặc thù (`inquiry`, `products`, `services`, `projects`).
- [x] Chuyển đổi `views/inquiry.blade.php`:
  - Form liên hệ gồm: Họ tên, Email, Điện thoại, Chủ đề (Subject), Nội dung/Ghi chú (`note`).
  - Form action trỏ tới `route('public.send.contact')` kèm `@csrf`.
  - Hiển thị thông tin liên hệ và bản đồ Google Maps nhúng.

#### 5. Trang Từ Khóa, Trang Tác Giả & Trang Tìm Kiếm
- [x] Chuyển đổi `views/tag.blade.php`: Hiển thị danh sách bài viết theo thẻ tag và phân trang.
- [x] Chuyển đổi `views/author.blade.php`: Hiển thị thông tin tác giả và danh sách bài viết của tác giả.
- [x] Chuyển đổi `views/search.blade.php`:
  - Hiển thị kết quả tìm kiếm theo từ khóa bài viết (`q`).
  - Sửa lại khối sidebar: Query danh mục và tags hoạt động thực tế từ database (thay vì code lỗi gọi `$post` ngoài foreach cũ).

#### 6. Trang Lỗi 404 & 500
- [x] Chuyển đổi `views/errors/404.blade.php` và `views/errors/500.blade.php`: Sử dụng layout `error-master` đồng bộ với giao diện của website.

---

## 4. TIÊU CHÍ HOÀN THÀNH (DEFINITION OF DONE - DoD)
1. Giao diện Frontend hiển thị chuẩn xác 100% so với website gốc trên theme `ripple` (từ bố cục, slider banner, hiệu ứng đếm số, typography, icon Themify/Flaticon đến các khoảng cách).
2. Toàn bộ các helper đặc thù của Botble (`Theme::*`, `Menu::*`, `RvMedia::*`, `Language::*`) được thay thế hoàn toàn bằng Blade Components và Helper thuần Laravel 13.
3. 100% các đường dẫn cũ (283 slugs của Bài viết, Chuyên mục, Trang tĩnh, Tags) hoạt động trơn tru qua dynamic route `/{slug}`, tuyệt đối không phát sinh mã lỗi 404.
4. Hệ thống đa ngôn ngữ (`vi` và `en`) hoạt động chuẩn xác: Đổi ngôn ngữ chuyển giao diện và nội dung tương ứng, menu và content block nạp đúng theo locale hiện tại.
5. Form liên hệ tại trang Inquiry và trên website gửi dữ liệu thành công qua AJAX hoặc Form POST, lưu bản ghi vào bảng `contacts` trong Admin Filament và hiển thị thông báo SweetAlert thân thiện.
6. Helper `get_object_image()` hiển thị hình ảnh đại diện và ảnh bài viết sắc nét, không bị lỗi cắt cụt đường dẫn hoặc vỡ ảnh.
