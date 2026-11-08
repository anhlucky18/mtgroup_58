<x-filament-panels::page>
    <style>
        /* ========================================================
           CHUẨN HÓA GIAO DIỆN THEO BOTBLE CMS (flexbox-annotated-section)
           Font chữ nhỏ vừa phải, phân tách rõ ràng, dễ nhìn & dễ tìm kiếm
           ======================================================== */
        .botble-settings-container {
            max-width: 1120px;
            margin: 0 auto;
        }

        /* Khóa cứng kích thước SVG - tuyệt đối không bị to */
        .botble-settings-container svg,
        .media-picker-modal svg {
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
            font-size: 14px;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.35;
        }
        .dark .annotated-section-title {
            color: #f8fafc;
        }

        .annotated-section-desc {
            margin: 0;
            font-size: 12px;
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
            font-size: 12.5px;
            line-height: 1.4;
            border-radius: 4px;
            border: 1.5px solid #cbd5e1;
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
            border-color: #d97706;
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

        /* Custom file input */
        .next-file-input {
            display: block;
            width: 100%;
            padding: 4px 8px;
            font-size: 11.5px;
            border-radius: 4px;
            border: 1px dashed #cbd5e1;
            background-color: #f8fafc;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .next-file-input:hover {
            border-color: #d97706;
            background-color: #fffbeb;
        }
        .dark .next-file-input {
            border-color: #475569;
            background-color: #0f172a;
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

        /* Nút phụ nhỏ */
        .btn-subtle-action {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            background-color: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .btn-subtle-action:hover {
            border-color: #d97706;
            color: #d97706;
            background-color: #fffbeb;
        }
        .dark .btn-subtle-action {
            background-color: #0f172a;
            border-color: #475569;
            color: #cbd5e1;
        }
        .dark .btn-subtle-action:hover {
            border-color: #f59e0b;
            color: #f59e0b;
            background-color: #1e293b;
        }

        /* Khung xem trước ảnh */
        .preview-box-checker {
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 10px 10px;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90px;
        }
        .dark .preview-box-checker {
            border-color: #334155;
            background-color: #0f172a;
            background-image: radial-gradient(#334155 1px, transparent 1px);
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
            <a href="#sec-logo" class="quick-nav-link">Logo chính</a>
            <a href="#sec-dimensions" class="quick-nav-link">Chiều cao Logo</a>
            <a href="#sec-favicon" class="quick-nav-link">Biểu tượng Favicon</a>
            <a href="#sec-typography" class="quick-nav-link">Phông chữ (Google Fonts)</a>
            <a href="#sec-copyright" class="quick-nav-link">Bản quyền chân trang</a>
            <a href="#sec-custom-css" class="quick-nav-link">CSS tùy biến</a>
        </div>

        <form wire:submit.prevent="save">
            {{-- 1. SECTION: LOGO CHÍNH --}}
            <div id="sec-logo" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Logo chính Website</h2>
                    <p class="annotated-section-desc">
                        Logo thương hiệu hiển thị tại thanh điều hướng (Header), menu di động và cấu trúc dữ liệu schema website.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Xem trước &amp; Tải ảnh</span>
                            <button 
                                type="button" 
                                wire:click="resetLogo" 
                                class="btn-subtle-action"
                                title="Khôi phục lại đường dẫn logo mặc định"
                            >
                                <x-heroicon-m-arrow-path />
                                <span>Mặc định</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                            {{-- Khung Preview --}}
                            <div class="md:col-span-5 space-y-1.5">
                                <label class="text-title-field">Xem trước Logo</label>
                                <div class="preview-box-checker">
                                    @if ($logoUpload)
                                        <div class="text-center">
                                            <img src="{{ $logoUpload->temporaryUrl() }}" alt="Logo preview" class="max-h-14 w-auto object-contain mx-auto" />
                                            <span class="mt-1 inline-block text-[10px] text-amber-700 dark:text-amber-400 font-semibold bg-amber-50 dark:bg-amber-950/60 px-1.5 py-0.5 rounded">
                                                Ảnh mới tải lên
                                            </span>
                                        </div>
                                    @elseif (!empty($settings['theme-ripple-logo'] ?? $settings['logo']))
                                        <img 
                                            src="{{ $settings['theme-ripple-logo'] ?? $settings['logo'] }}" 
                                            alt="Logo" 
                                            class="max-h-14 w-auto object-contain" 
                                            onerror="this.onerror=null;this.src='/storage/uploads/gst-logo-01.png';" 
                                        />
                                    @else
                                        <span class="text-xs text-gray-400">Chưa có logo</span>
                                    @endif
                                </div>
                                <span class="help-block-text text-center">Khuyến nghị ảnh trong suốt PNG, WebP hoặc SVG.</span>
                            </div>

                            {{-- Điều khiển tải / chọn ảnh --}}
                            <div class="md:col-span-7 space-y-2.5">
                                <div>
                                    <label class="text-title-field">Tải ảnh mới từ máy tính</label>
                                    <input 
                                        type="file" 
                                        wire:model="logoUpload" 
                                        accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                                        class="next-file-input"
                                    />
                                    <div wire:loading wire:target="logoUpload" class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1 font-medium">
                                        <svg class="animate-spin text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Đang tải ảnh lên...
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <button 
                                        type="button" 
                                        wire:click="openMediaPicker('logo')"
                                        class="btn-subtle-action"
                                    >
                                        <x-heroicon-m-folder-open />
                                        <span>Chọn từ Thư viện Media</span>
                                    </button>
                                </div>

                                <div>
                                    <label class="text-title-field" for="theme_logo">Đường dẫn tệp Logo (URL)</label>
                                    <input 
                                        type="text" 
                                        id="theme_logo"
                                        wire:model="settings.theme-ripple-logo" 
                                        class="next-input font-mono" 
                                        placeholder="/storage/uploads/gst-logo-01.png"
                                    />
                                    <span class="help-block-text">Đồng bộ tự động khóa <code>theme-ripple-logo</code> và <code>logo</code>.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. SECTION: KÍCH THƯỚC LOGO --}}
            <div id="sec-dimensions" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Kích thước hiển thị Logo</h2>
                    <p class="annotated-section-desc">
                        Điều chỉnh chiều cao tối đa của logo trên thanh điều hướng đầu trang (Header) trên toàn bộ website.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div class="max-w-xs">
                            <label class="text-title-field" for="logo_height">
                                Chiều cao Header Logo (px)
                            </label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="number" 
                                    id="logo_height"
                                    wire:model="settings.logo_height" 
                                    min="20" 
                                    max="150" 
                                    step="1"
                                    class="next-input font-mono text-center font-semibold w-24" 
                                    placeholder="50"
                                />
                                <span class="text-xs text-gray-500 font-medium">pixel</span>
                            </div>
                            <span class="help-block-text">
                                Mặc định: <strong>50px</strong>. Khuyến nghị thiết lập từ 45px đến 65px để menu không bị giãn nở quá lớn.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. SECTION: FAVICON --}}
            <div id="sec-favicon" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Biểu tượng Favicon</h2>
                    <p class="annotated-section-desc">
                        Biểu tượng nhỏ hiển thị trên tab trình duyệt, dấu trang (bookmark) và cạnh tiêu đề khi tìm kiếm trên di động.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Xem trước &amp; Tải Favicon</span>
                            <button 
                                type="button" 
                                wire:click="resetFavicon" 
                                class="btn-subtle-action"
                                title="Khôi phục lại favicon mặc định"
                            >
                                <x-heroicon-m-arrow-path />
                                <span>Mặc định</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                            {{-- Favicon Mockup & Size Grid --}}
                            <div class="md:col-span-5 space-y-2">
                                <label class="text-title-field">Mô phỏng Tab trình duyệt</label>
                                
                                <div class="rounded border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 p-2">
                                    <div class="bg-white dark:bg-gray-900 rounded px-2 py-1.5 flex items-center gap-2 shadow-xs border border-gray-200/80 dark:border-gray-700">
                                        @if ($faviconUpload)
                                            <img src="{{ $faviconUpload->temporaryUrl() }}" alt="Favicon" class="w-3.5 h-3.5 object-contain" />
                                        @elseif (!empty($settings['favicon']))
                                            <img src="{{ $settings['favicon'] }}" alt="Favicon" class="w-3.5 h-3.5 object-contain" onerror="this.onerror=null;this.src='/storage/uploads/gst-logo-01.png';" />
                                        @else
                                            <div class="w-3.5 h-3.5 bg-amber-500 rounded-xs"></div>
                                        @endif
                                        <span class="text-[11px] font-medium text-gray-700 dark:text-gray-300 truncate">Minh Thành Group</span>
                                        <span class="text-gray-400 text-[10px] ml-auto">&times;</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-around p-2 bg-gray-50 dark:bg-gray-800/40 rounded border border-gray-200 dark:border-gray-700">
                                    <div class="text-center">
                                        <span class="text-[9.5px] text-gray-500 block mb-0.5">16x16</span>
                                        <div class="w-5 h-5 mx-auto flex items-center justify-center bg-white dark:bg-gray-900 border rounded-xs">
                                            <img src="{{ $faviconUpload ? $faviconUpload->temporaryUrl() : ($settings['favicon'] ?? '/storage/uploads/gst-logo-01.png') }}" class="w-3.5 h-3.5 object-contain" />
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-[9.5px] text-gray-500 block mb-0.5">32x32</span>
                                        <div class="w-7 h-7 mx-auto flex items-center justify-center bg-white dark:bg-gray-900 border rounded-xs">
                                            <img src="{{ $faviconUpload ? $faviconUpload->temporaryUrl() : ($settings['favicon'] ?? '/storage/uploads/gst-logo-01.png') }}" class="w-5 h-5 object-contain" />
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <span class="text-[9.5px] text-gray-500 block mb-0.5">48x48</span>
                                        <div class="w-9 h-9 mx-auto flex items-center justify-center bg-white dark:bg-gray-900 border rounded-xs">
                                            <img src="{{ $faviconUpload ? $faviconUpload->temporaryUrl() : ($settings['favicon'] ?? '/storage/uploads/gst-logo-01.png') }}" class="w-7 h-7 object-contain" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Điều khiển tải Favicon --}}
                            <div class="md:col-span-7 space-y-2.5">
                                <div>
                                    <label class="text-title-field">Tải ảnh Favicon mới</label>
                                    <input 
                                        type="file" 
                                        wire:model="faviconUpload" 
                                        accept="image/x-icon,image/png,image/svg+xml,image/jpeg"
                                        class="next-file-input"
                                    />
                                    <div wire:loading wire:target="faviconUpload" class="text-xs text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1 font-medium">
                                        <svg class="animate-spin text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Đang tải favicon...
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <button 
                                        type="button" 
                                        wire:click="openMediaPicker('favicon')"
                                        class="btn-subtle-action"
                                    >
                                        <x-heroicon-m-folder-open />
                                        <span>Chọn từ Thư viện Media</span>
                                    </button>
                                </div>

                                <div>
                                    <label class="text-title-field" for="theme_favicon">Đường dẫn tệp Favicon (URL)</label>
                                    <input 
                                        type="text" 
                                        id="theme_favicon"
                                        wire:model="settings.favicon" 
                                        class="next-input font-mono" 
                                        placeholder="/storage/uploads/gst-logo-01.png"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. SECTION: GOOGLE FONTS --}}
            <div id="sec-typography" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Phông chữ Website (Google Fonts)</h2>
                    <p class="annotated-section-desc">
                        Chọn phông chữ chính được nạp tự động qua Google Fonts CDN và áp dụng đồng bộ toàn bộ giao diện frontend.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Tùy chọn phông chữ</span>
                            <button 
                                type="button" 
                                wire:click="resetFont" 
                                class="btn-subtle-action"
                                title="Khôi phục lại font Roboto chuẩn mặc định"
                            >
                                <x-heroicon-m-arrow-path />
                                <span>Khôi phục Roboto</span>
                            </button>
                        </div>

                        <div>
                            <label class="text-title-field" for="primary_font">
                                Chọn phông chữ chính (Primary Font)
                            </label>
                            <select 
                                id="primary_font"
                                wire:model.live="settings.primary_font"
                                class="next-select"
                            >
                                @foreach ($fonts as $fontKey => $fontLabel)
                                    <option value="{{ $fontKey }}">{{ $fontLabel }}</option>
                                @endforeach
                            </select>
                            <span class="help-block-text">
                                Các font đầu danh sách (Roboto, Be Vietnam Pro, Inter, Montserrat, Open Sans...) hỗ trợ chuẩn 100% tiếng Việt có dấu.
                            </span>
                        </div>

                        {{-- Interactive Live Font Preview Box --}}
                        <div class="pt-2">
                            <label class="text-title-field">Xem trước phông chữ thực tế (Live Preview)</label>

                            @php
                                $selectedFont = $settings['primary_font'] ?? 'Roboto';
                                $fontParam = urlencode($selectedFont) . ':ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap';
                            @endphp

                            <link href="https://fonts.googleapis.com/css2?family={{ $fontParam }}" rel="stylesheet" type="text/css">

                            <div 
                                class="p-4 rounded border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 space-y-3"
                                style="font-family: '{{ $selectedFont }}', sans-serif !important;"
                            >
                                <div class="border-b border-gray-100 dark:border-gray-700 pb-2">
                                    <span class="text-[11px] text-amber-600 dark:text-amber-400 uppercase tracking-wider font-semibold">Minh Thành Group • Quan trắc môi trường</span>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mt-0.5 leading-snug">
                                        Cung Cấp Giải Pháp Quan Trắc Môi Trường Tự Động, Liên Tục
                                    </h3>
                                </div>

                                <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                                    Minh Thành Group chuyên cung cấp các giải pháp và thiết bị trạm quan trắc tự động liên tục chất lượng nước thải, khí thải công nghiệp, nước ngầm và không khí xung quanh. Hệ thống dữ liệu được truyền trực tiếp về Sở Tài nguyên và Môi trường.
                                </p>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 text-[11px]">
                                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                                        <span class="text-gray-500 block mb-0.5 font-medium">Bảng chữ cái tiếng Việt có dấu:</span>
                                        <span class="text-gray-900 dark:text-gray-100 font-semibold">
                                            a, á, à, ả, ã, ạ, ă, ắ, ằ, ẳ, ẵ, ặ, â, ấ, ầ, ẩ, ẫ, ậ, đ, e, é, è, ẻ, ẽ, ẹ, ê, ế, ề, ể, ễ, ệ, o, ô, ơ, u, ư...
                                        </span>
                                    </div>
                                    <div class="p-2 rounded bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800">
                                        <span class="text-gray-500 block mb-0.5 font-medium">Chữ số &amp; Ký hiệu:</span>
                                        <span class="text-gray-900 dark:text-gray-100 font-semibold">
                                            Hotline: 0963.189.981 - Mã số thuế: 2301052220 (VNĐ, $, %, @)
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. SECTION: COPYRIGHT --}}
            <div id="sec-copyright" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Bản quyền chân trang</h2>
                    <p class="annotated-section-desc">
                        Dòng chữ thông báo bản quyền hiển thị tại góc dưới cùng của chân trang (Footer) trên toàn bộ website.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="theme_copyright">
                                Dòng chữ bản quyền (Copyright)
                            </label>
                            <input 
                                type="text" 
                                id="theme_copyright"
                                wire:model="settings.theme-ripple-copyright" 
                                class="next-input" 
                                placeholder="Minh Thành Group"
                            />
                            <span class="help-block-text">
                                Khóa lưu trữ: <code>theme-ripple-copyright</code>. Hiển thị dưới chân mọi trang người dùng.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. SECTION: CUSTOM CSS --}}
            <div id="sec-custom-css" class="flexbox-annotated-section">
                <div class="flexbox-annotated-section-annotation">
                    <h2 class="annotated-section-title">Mã CSS tùy biến thêm</h2>
                    <p class="annotated-section-desc">
                        Chèn mã CSS tự định nghĩa để tùy chỉnh giao diện mà không cần chỉnh sửa trực tiếp vào file nguồn của theme.
                    </p>
                </div>

                <div class="flexbox-annotated-section-content">
                    <div class="wrapper-content space-y-3.5">
                        <div>
                            <label class="text-title-field" for="custom_css">
                                Mã CSS (Custom CSS)
                            </label>
                            <textarea 
                                id="custom_css"
                                rows="6" 
                                wire:model="settings.custom_css" 
                                placeholder="/* Viết mã CSS tùy biến để ghi đè giao diện frontend */"
                                class="next-textarea code-input"
                            ></textarea>
                            <span class="help-block-text">
                                Được nạp trực tiếp trong thẻ <code>&lt;style&gt;</code> ở phần <code>&lt;head&gt;</code> của trang chủ.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Save Action Bar --}}
            <div class="save-sticky-bar">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <x-heroicon-m-information-circle />
                    <span>Thay đổi sẽ có hiệu lực ngay lập tức sau khi lưu.</span>
                </div>
                <button type="submit" class="btn-submit-save">
                    <x-heroicon-m-check />
                    <span>Lưu Tùy Chọn Giao Diện</span>
                </button>
            </div>
        </form>
    </div>

    {{-- MEDIA PICKER MODAL --}}
    @if ($showMediaPicker)
        <div class="fixed inset-0 z-50 overflow-y-auto media-picker-modal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-black/75 backdrop-blur-xs transition-opacity" wire:click="closeMediaPicker"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white dark:bg-gray-900 rounded-lg text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-800">
                    <div class="px-5 py-3.5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-1.5">
                                <x-heroicon-m-folder-open />
                                <span>Chọn ảnh cho: <strong class="uppercase text-amber-600">{{ $mediaPickerTarget }}</strong></span>
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-0.5">Nhấp vào hình ảnh để áp dụng ngay lập tức.</p>
                        </div>
                        <button type="button" wire:click="closeMediaPicker" class="text-gray-400 hover:text-gray-500 text-xl font-light">&times;</button>
                    </div>

                    <div class="p-5 space-y-3.5">
                        {{-- Search Filter --}}
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live.debounce.300ms="mediaSearch"
                                placeholder="Tìm kiếm tên hình ảnh (ví dụ: logo, icon, banner)..."
                                class="next-input pl-8"
                            />
                            <div style="position: absolute; left: 8px; top: 0; bottom: 0; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                <x-heroicon-m-magnifying-glass />
                            </div>
                        </div>

                        {{-- Media Grid --}}
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2.5 max-h-96 overflow-y-auto p-1">
                            @if ($mediaFiles && $mediaFiles->count() > 0)
                                @foreach ($mediaFiles as $file)
                                    <div 
                                        wire:click="selectMedia('{{ $file['url'] }}')"
                                        class="group cursor-pointer rounded border border-gray-200 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-500 overflow-hidden bg-white dark:bg-gray-800 transition p-1.5 flex flex-col items-center shadow-2xs"
                                    >
                                        <div class="w-full h-18 flex items-center justify-center bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] dark:bg-[radial-gradient(#334155_1px,transparent_1px)] [background-size:6px_6px] rounded-xs overflow-hidden">
                                            <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="max-h-full max-w-full object-contain group-hover:scale-105 transition" />
                                        </div>
                                        <span class="text-[10px] text-gray-600 dark:text-gray-400 truncate w-full text-center mt-1 group-hover:text-amber-600 font-medium" title="{{ $file['name'] }}">
                                            {{ $file['name'] }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-full py-10 text-center text-gray-400 text-xs">
                                    Không tìm thấy hình ảnh phù hợp.
                                </div>
                            @endif
                        </div>

                        {{-- Pagination --}}
                        @if ($mediaFiles && $mediaFiles->hasPages())
                            <div class="pt-2 text-xs">
                                {{ $mediaFiles->links() }}
                            </div>
                        @endif
                    </div>

                    <div class="px-5 py-2.5 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-800 flex justify-end">
                        <button 
                            type="button" 
                            wire:click="closeMediaPicker" 
                            class="btn-subtle-action"
                        >
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
