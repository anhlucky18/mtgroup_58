<x-filament-panels::page>
    <style>
        /* ========================================================
           CHUẨN HÓA LAYOUT THEO BOTBLE CMS (flexbox-annotated-section)
           Font chữ nhỏ vừa phải, phân tách rõ ràng, dễ nhìn & dễ tìm kiếm
           ======================================================== */
        .botble-settings-container {
            max-width: 1120px;
            margin: 0 auto;
        }

        /* Khóa cứng kích thước SVG - tuyệt đối không bị to */
        .botble-settings-container svg {
            display: inline-block !important;
            vertical-align: middle !important;
            flex-shrink: 0 !important;
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
        }

        /* Khung điều hướng mục lục nhanh (Quick Jump Pills) */
        .quick-nav-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }
        .dark .quick-nav-bar {
            background-color: #1e293b;
            border-color: #334155;
        }
        .quick-nav-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-right: 4px;
        }
        .quick-nav-link {
            font-size: 11.5px;
            font-weight: 500;
            color: #475569;
            background-color: #f1f5f9;
            padding: 3px 8px;
            border-radius: 4px;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            white-space: nowrap;
        }
        .quick-nav-link:hover {
            background-color: #d97706;
            color: #ffffff;
        }
        .dark .quick-nav-link {
            background-color: #0f172a;
            color: #cbd5e1;
        }
        .dark .quick-nav-link:hover {
            background-color: #f59e0b;
            color: #0f172a;
        }

        /* Khối Annotated Section (Cột trái Annotation - Cột phải Card) */
        .flexbox-annotated-section {
            display: flex;
            flex-direction: column;
            gap: 16px;
            padding-top: 24px;
            margin-bottom: 24px;
            border-top: 1px solid #e2e8f0;
        }
        .flexbox-annotated-section:first-of-type {
            padding-top: 0;
            border-top: none;
        }
        .dark .flexbox-annotated-section {
            border-top-color: #334155;
        }

        @media (min-width: 768px) {
            .flexbox-annotated-section {
                flex-direction: row;
                align-items: flex-start;
                gap: 28px;
            }
            .flexbox-annotated-section-annotation {
                flex: 0 0 280px;
                max-width: 280px;
            }
            .flexbox-annotated-section-content {
                flex: 1 1 0%;
                min-width: 0;
            }
        }

        /* Tiêu đề & mô tả phân mục bên trái */
        .annotated-section-title {
            margin: 0 0 4px 0;
            font-size: 14px; /* Font chữ nhỏ vừa phải */
            font-weight: 600;
            color: #0f172a;
            line-height: 1.35;
        }
        .dark .annotated-section-title {
            color: #f8fafc;
        }

        .annotated-section-desc {
            margin: 0;
            font-size: 12px; /* Font mô tả 12px gọn gàng */
            color: #64748b;
            line-height: 1.45;
        }
        .dark .annotated-section-desc {
            color: #94a3b8;
        }

        /* Thẻ Card trắng bên phải (Wrapper content) */
        .wrapper-content {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            padding: 18px 20px;
        }
        .dark .wrapper-content {
            background-color: #1e293b;
            border-color: #334155;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.2);
        }

        /* Nhãn trường nhập liệu */
        .text-title-field {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .dark .text-title-field {
            color: #cbd5e1;
        }

        /* Ô nhập liệu chuẩn Botble .next-input */
        .next-input,
        .next-select,
        .next-textarea {
            display: block;
            width: 100%;
            padding: 5px 10px;
            font-size: 12.5px; /* Chữ nhỏ vừa vặn */
            line-height: 1.4;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, 0.03);
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            outline: none;
        }
        .next-input {
            height: 33px;
        }
        .next-select {
            height: 33px;
        }
        .next-input:focus,
        .next-select:focus,
        .next-textarea:focus {
            border-color: #d97706; /* Amber focus */
            box-shadow: 0 0 0 2px rgba(217, 119, 6, 0.18);
        }
        .dark .next-input,
        .dark .next-select,
        .dark .next-textarea {
            background-color: #0f172a;
            border-color: #475569;
            color: #f8fafc;
        }
        .dark .next-input:focus,
        .dark .next-select:focus,
        .dark .next-textarea:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.25);
        }

        .help-block-text {
            display: block;
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.35;
        }
        .dark .help-block-text {
            color: #94a3b8;
        }

        /* Code textarea */
        .code-input {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important;
            font-size: 11.5px !important;
            line-height: 1.45 !important;
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border: 1px solid #334155 !important;
        }

        /* Sticky Bottom Action Bar */
        .save-sticky-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            bottom: 12px;
            z-index: 30;
            margin-top: 24px;
        }
        .dark .save-sticky-bar {
            background-color: rgba(30, 41, 59, 0.95);
            border-color: #334155;
        }
        .btn-submit-save {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 18px;
            font-size: 12.5px;
            font-weight: 600;
            color: #ffffff;
            background-color: #d97706;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.15s;
        }
        .btn-submit-save:hover {
            background-color: #b45309;
        }
    </style>

    <div class="botble-settings-container">
        {{-- Quick Navigation Anchor Bar --}}
        <div class="quick-nav-bar">
            <span class="quick-nav-title">Mục lục:</span>
            <a href="#sec-general" class="quick-nav-link">Thông tin chung</a>
            <a href="#sec-branding" class="quick-nav-link">Logo &amp; Favicon</a>
            <a href="#sec-typography" class="quick-nav-link">Phông chữ &amp; Bản quyền</a>
            <a href="#sec-seo" class="quick-nav-link">Cấu hình SEO</a>
            <a href="#sec-contact" class="quick-nav-link">Hotline &amp; Thuế</a>
            <a href="#sec-offices" class="quick-nav-link">4 Văn phòng</a>
            <a href="#sec-tracking" class="quick-nav-link">Mã tiếp thị GTM</a>
            <a href="#sec-scripts" class="quick-nav-link">Mã nhúng Scripts</a>
        </div>

        <form wire:submit.prevent="save">
            {{-- 1. SECTION: GENERAL INFORMATION --}}
            <div id="sec-general" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Thông tin chung</h2>
                    <p class="annotated-section-desc">
                        Thiết lập tên website chính thức, khẩu hiệu (slogan) và email quản trị hệ thống.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="site_title">
                                Tên website (Site Title) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="site_title"
                                wire:model="settings.site_title" 
                                class="next-input" 
                                placeholder="Minh Thành Group"
                            />
                        </div>

                        <div>
                            <label class="text-title-field" for="site_slogan">
                                Khẩu hiệu (Slogan)
                            </label>
                            <input 
                                type="text" 
                                id="site_slogan"
                                wire:model="settings.site_slogan" 
                                class="next-input" 
                                placeholder="Cung cấp giải pháp quan trắc môi trường tự động, liên tục"
                            />
                        </div>

                        <div>
                            <label class="text-title-field" for="admin_email">
                                Email quản trị (Admin Email)
                            </label>
                            <input 
                                type="email" 
                                id="admin_email"
                                wire:model="settings.admin_email" 
                                class="next-input font-mono" 
                                placeholder="quantrac.mtg@gmail.com"
                            />
                            <span class="help-block-text">Email tiếp nhận liên hệ và thông báo từ hệ thống website.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SECTION: LOGO & FAVICON --}}
            <div id="sec-branding" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Logo &amp; Biểu tượng</h2>
                    <p class="annotated-section-desc">
                        Đường dẫn hình ảnh logo xuất hiện trên thanh menu Header và favicon trên tab trình duyệt.
                    </p>
                    <div class="mt-2">
                        <a href="/admin/theme/options" class="text-xs text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-1 font-medium">
                            <x-heroicon-m-arrow-top-right-on-square /> Mở Tùy chọn giao diện &rarr;
                        </a>
                    </div>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-4">
                        {{-- Logo Row --}}
                        <div>
                            <label class="text-title-field">Đường dẫn tệp Logo chính</label>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-1 flex items-center justify-center shrink-0 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] dark:bg-[radial-gradient(#374151_1px,transparent_1px)] [background-size:6px_6px]">
                                    <img 
                                        src="{{ $settings['theme-ripple-logo'] ?? $settings['logo'] ?? '/storage/uploads/gst-logo-01.png' }}" 
                                        alt="Logo preview" 
                                        class="max-h-full max-w-full object-contain" 
                                        onerror="this.onerror=null;this.src='/storage/uploads/gst-logo-01.png';" 
                                    />
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        wire:model="settings.theme-ripple-logo" 
                                        placeholder="/storage/uploads/gst-logo-01.png"
                                        class="next-input font-mono text-xs" 
                                    />
                                </div>
                            </div>
                            <span class="help-block-text">Khuyến nghị ảnh định dạng PNG hoặc SVG trong suốt. Mặc định: /storage/uploads/gst-logo-01.png</span>
                        </div>

                        {{-- Favicon Row --}}
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-800">
                            <label class="text-title-field">Đường dẫn tệp Favicon (Tab icon)</label>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 p-1 flex items-center justify-center shrink-0">
                                    <img 
                                        src="{{ $settings['favicon'] ?? '/storage/uploads/gst-logo-01.png' }}" 
                                        alt="Favicon preview" 
                                        class="w-5 h-5 object-contain" 
                                        onerror="this.onerror=null;this.src='/storage/uploads/gst-logo-01.png';" 
                                    />
                                </div>
                                <div class="flex-1">
                                    <input 
                                        type="text" 
                                        wire:model="settings.favicon" 
                                        placeholder="/storage/uploads/gst-logo-01.png"
                                        class="next-input font-mono text-xs" 
                                    />
                                </div>
                            </div>
                            <span class="help-block-text">Biểu tượng hiển thị trên tab trình duyệt (chuẩn kích thước 16x16 hoặc 32x32 px).</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. SECTION: TYPOGRAPHY & COPYRIGHT --}}
            <div id="sec-typography" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Phông chữ &amp; Bản quyền</h2>
                    <p class="annotated-section-desc">
                        Phông chữ Google Fonts tải trực tiếp từ CDN cho toàn website và nội dung bản quyền chân trang.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-title-field" for="primary_font">
                                    Phông chữ toàn trang (Google Fonts)
                                </label>
                                <a href="/admin/theme/options" class="text-[11px] text-amber-600 dark:text-amber-400 hover:underline inline-flex items-center gap-0.5 font-medium">
                                    <x-heroicon-m-eye /> Xem trước trực tiếp &rarr;
                                </a>
                            </div>
                            <select id="primary_font" wire:model="settings.primary_font" class="next-select">
                                @foreach ($fonts as $fontKey => $fontLabel)
                                    <option value="{{ $fontKey }}">{{ $fontLabel }}</option>
                                @endforeach
                            </select>
                            <span class="help-block-text">Tự động nhúng Google Font và áp dụng cho toàn bộ heading, menu và body text.</span>
                        </div>

                        <div>
                            <label class="text-title-field" for="theme_copyright">
                                Bản quyền chân trang (Footer Copyright)
                            </label>
                            <input 
                                type="text" 
                                id="theme_copyright"
                                wire:model="settings.theme-ripple-copyright" 
                                class="next-input" 
                                placeholder="Minh Thành Group"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. SECTION: HOMEPAGE SEO --}}
            <div id="sec-seo" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Cấu hình SEO Trang Chủ</h2>
                    <p class="annotated-section-desc">
                        Cấu hình tiêu đề SEO và đoạn mô tả meta tóm tắt hiển thị trên kết quả tìm kiếm Google.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="seo_title">
                                Tiêu đề SEO (SEO Title)
                            </label>
                            <input 
                                type="text" 
                                id="seo_title"
                                wire:model="settings.seo_title" 
                                class="next-input" 
                                placeholder="Cung cấp giải pháp quan trắc môi trường tự động"
                            />
                        </div>

                        <div>
                            <label class="text-title-field" for="seo_description">
                                Mô tả SEO (SEO Description)
                            </label>
                            <textarea 
                                rows="3" 
                                id="seo_description"
                                wire:model="settings.seo_description" 
                                class="next-textarea"
                                placeholder="Minh Thành Group chuyên cung cấp giải pháp và thiết bị quan trắc môi trường tự động..."
                            ></textarea>
                            <span class="help-block-text">Độ dài tối ưu từ 120 đến 160 ký tự.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. SECTION: CONTACT & LEGAL --}}
            <div id="sec-contact" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Đường dây nóng &amp; Thuế</h2>
                    <p class="annotated-section-desc">
                        Hotline liên hệ nhanh trên thanh điều hướng, nút gọi và mã số thuế doanh nghiệp.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="text-title-field" for="hotline_main">
                                    Hotline chính <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="hotline_main"
                                    wire:model="settings.hotline_main" 
                                    class="next-input font-mono font-semibold" 
                                    placeholder="0963189981"
                                />
                            </div>

                            <div>
                                <label class="text-title-field" for="tax_code">
                                    Mã số thuế doanh nghiệp
                                </label>
                                <input 
                                    type="text" 
                                    id="tax_code"
                                    wire:model="settings.tax_code" 
                                    class="next-input font-mono" 
                                    placeholder="2301052220"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. SECTION: 4 OFFICES --}}
            <div id="sec-offices" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Hệ thống 4 Văn phòng</h2>
                    <p class="annotated-section-desc">
                        Địa chỉ Trụ sở chính và 3 Văn phòng đại diện trên toàn quốc hiển thị tại chân trang và trang Liên hệ.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3">
                        {{-- Office 1 --}}
                        <div>
                            <label class="text-title-field text-amber-700 dark:text-amber-400">
                                1. Trụ sở chính (Bắc Ninh)
                            </label>
                            <input 
                                type="text" 
                                wire:model="settings.office_bac_ninh" 
                                class="next-input" 
                                placeholder="Số 32 Nguyễn Khuyến, Phường Võ Cường, TP. Bắc Ninh"
                            />
                        </div>

                        {{-- Office 2 --}}
                        <div>
                            <label class="text-title-field">
                                2. Văn phòng đại diện Hà Nội
                            </label>
                            <input 
                                type="text" 
                                wire:model="settings.office_ha_noi" 
                                class="next-input" 
                                placeholder="Tòa nhà Lạc Hồng, Ngõ 27 Lê Văn Lương, Nhân Chính, Thanh Xuân, Hà Nội"
                            />
                        </div>

                        {{-- Office 3 --}}
                        <div>
                            <label class="text-title-field">
                                3. Chi nhánh Miền Trung (Thanh Hóa)
                            </label>
                            <input 
                                type="text" 
                                wire:model="settings.office_thanh_hoa" 
                                class="next-input" 
                                placeholder="Số 46 Đặng Văn Hỷ, An Hưng, TP. Thanh Hóa"
                            />
                        </div>

                        {{-- Office 4 --}}
                        <div>
                            <label class="text-title-field">
                                4. Văn phòng đại diện TP. Hồ Chí Minh
                            </label>
                            <input 
                                type="text" 
                                wire:model="settings.office_hcm" 
                                class="next-input" 
                                placeholder="Số 20 Đường 1 KDC CityLand Park Hills, Gò Vấp, TP. HCM"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. SECTION: TRACKING & WEBMASTER TOOLS --}}
            <div id="sec-tracking" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Mã tích hợp tiếp thị</h2>
                    <p class="annotated-section-desc">
                        Tích hợp công cụ phân tích Google Tag Manager (GTM) và tiện ích Facebook Chat Messenger qua mã ID.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="gtm_id">
                                Google Tag Manager ID
                            </label>
                            <input 
                                type="text" 
                                id="gtm_id"
                                wire:model="settings.gtm_id" 
                                placeholder="GTM-MHFCDPPW" 
                                class="next-input font-mono" 
                            />
                            <span class="help-block-text">Hệ thống tự động nhúng mã theo dõi GTM chuẩn vào thẻ head và body.</span>
                        </div>

                        <div>
                            <label class="text-title-field" for="fb_page_id">
                                Facebook Chat Plugin Page ID
                            </label>
                            <input 
                                type="text" 
                                id="fb_page_id"
                                wire:model="settings.fb_page_id" 
                                placeholder="100367992250913" 
                                class="next-input font-mono" 
                            />
                            <span class="help-block-text">ID Fanpage dùng để hiển thị hộp trò chuyện Messenger trực tiếp.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 8. SECTION: CUSTOM SCRIPTS --}}
            <div id="sec-scripts" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Mã nhúng tùy biến</h2>
                    <p class="annotated-section-desc">
                        Chèn các mã HTML/JS tùy biến vào vị trí trước thẻ đóng &lt;/head&gt; hoặc trước thẻ đóng &lt;/body&gt;.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="custom_header_scripts">
                                Mã nhúng Header (&lt;head&gt; ... &lt;/head&gt;)
                            </label>
                            <textarea 
                                rows="4" 
                                id="custom_header_scripts"
                                wire:model="settings.custom_header_scripts" 
                                placeholder="<!-- Chèn Google Analytics, Google Search Console meta tag... -->" 
                                class="next-textarea code-input"
                            ></textarea>
                            <span class="help-block-text">Dùng cho thẻ meta xác minh, mã Google Analytics hoặc Google Search Console.</span>
                        </div>

                        <div>
                            <label class="text-title-field" for="custom_footer_scripts">
                                Mã nhúng Footer (Trước thẻ đóng &lt;/body&gt;)
                            </label>
                            <textarea 
                                rows="4" 
                                id="custom_footer_scripts"
                                wire:model="settings.custom_footer_scripts" 
                                placeholder="<!-- Chèn widget Zalo chat, Hotline rung lắc, livechat... -->" 
                                class="next-textarea code-input"
                            ></textarea>
                            <span class="help-block-text">Dùng cho widget chat Zalo, Tawk.to hoặc script theo dõi chuyển đổi.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 9. STICKY BOTTOM SAVE ACTION BAR --}}
            <div class="save-sticky-bar">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Cập nhật cấu hình toàn diện cho website Minh Thành Group.
                </span>
                <button type="submit" class="btn-submit-save">
                    <x-heroicon-m-check />
                    <span>Lưu cấu hình hệ thống</span>
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
