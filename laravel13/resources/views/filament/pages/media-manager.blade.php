<x-filament-panels::page>
    <style>
        /* ========================================================
           BOTBLE MEDIA MANAGER THEME STYLING
           Matches https://mtgroup.tech/admin/media
           ======================================================== */
        :root {
            --rv-primary: #003d4f;
            --rv-secondary: #f24d23;
            --rv-border: #e2e8f0;
            --rv-desc-bg: #dddddd;
        }

        .rv-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .dark .rv-container {
            background-color: #0f172a;
            border-color: #334155;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3);
        }

        /* Top Header */
        .rv-header-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 16px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .dark .rv-header-top {
            background-color: #1e293b;
            border-color: #334155;
        }

        /* Action Buttons */
        .rv-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff !important;
            background-color: #003d4f;
            border: 1px solid #003d4f;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .rv-btn-primary:hover {
            background-color: #f24d23;
            border-color: #f24d23;
        }
        .rv-btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #334155;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }
        .rv-btn-outline:hover {
            background-color: #f1f5f9;
            color: #003d4f;
            border-color: #94a3b8;
        }
        .dark .rv-btn-outline {
            background-color: #1e293b;
            border-color: #475569;
            color: #cbd5e1;
        }
        .dark .rv-btn-outline:hover {
            background-color: #334155;
            color: #ffffff;
        }

        /* Sub Header / Breadcrumb & Tools */
        .rv-header-bottom {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 8px 16px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
        .dark .rv-header-bottom {
            background-color: #0f172a;
            border-color: #334155;
        }

        /* Left Aside (Filters) */
        .rv-aside {
            width: 210px;
            min-width: 210px;
            background-color: #003d4f;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-shrink: 0;
            padding: 16px 0;
            user-select: none;
        }
        .dark .rv-aside {
            background-color: #0b1c24;
        }
        .rv-aside-title {
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
        }
        .rv-aside-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: 13px;
            color: #f1f5f9;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: all 0.15s ease-in-out;
        }
        .rv-aside-item:hover {
            background-color: rgba(0, 0, 0, 0.25);
            color: #ffffff;
            border-left-color: #f24d23;
        }
        .rv-aside-item.active {
            background-color: rgba(0, 0, 0, 0.35);
            color: #ffffff;
            font-weight: 600;
            border-left-color: #f24d23;
        }

        /* Items Container */
        .rv-main-area {
            display: flex;
            min-height: 600px;
            position: relative;
        }
        .rv-items-scroll {
            flex: 1 1 auto;
            min-width: 0;
            padding: 16px;
            overflow-y: auto;
            max-height: calc(100vh - 220px);
        }

        /* Grid Tile Structure */
        .rv-media-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        @media (min-width: 640px) {
            .rv-media-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        }
        @media (min-width: 768px) {
            .rv-media-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
        }
        @media (min-width: 1024px) {
            .rv-media-grid { grid-template-columns: repeat(6, minmax(0, 1fr)); }
        }
        @media (min-width: 1400px) {
            .rv-media-grid { grid-template-columns: repeat(8, minmax(0, 1fr)); }
        }

        /* Sizing of folder & image card */
        .rv-item-card {
            display: flex;
            flex-direction: column;
            cursor: pointer;
            border-radius: 4px;
            overflow: hidden;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.15s ease-in-out;
            position: relative;
        }
        .dark .rv-item-card {
            background-color: #1e293b;
            border-color: #334155;
        }
        .rv-item-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .rv-item-card.is-active {
            border-color: #f24d23 !important;
            box-shadow: 0 0 0 2px #f24d23 !important;
        }

        /* Square Thumbnail (1:1 Ratio for both Image & Folder) */
        .rv-thumb-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            overflow: hidden;
        }
        .dark .rv-thumb-box {
            background-color: #0f172a;
        }

        /* 70px icon size for folder and documents, matching Botble CMS */
        .rv-folder-icon svg {
            width: 70px !important;
            height: 70px !important;
            min-width: 70px !important;
            max-width: 70px !important;
            color: #d97706 !important; /* Warm folder amber */
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
            transition: transform 0.15s ease;
        }
        .rv-item-card:hover .rv-folder-icon svg {
            transform: scale(1.06);
        }

        .rv-uplevel-icon svg {
            width: 50px !important;
            height: 50px !important;
            min-width: 50px !important;
            max-width: 50px !important;
            color: #64748b !important;
        }

        .rv-doc-icon svg {
            width: 56px !important;
            height: 56px !important;
            min-width: 56px !important;
            max-width: 56px !important;
            color: #94a3b8 !important;
        }

        /* Image sizing inside square thumbnail */
        .rv-thumb-image {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.2s ease;
        }
        .rv-item-card:hover .rv-thumb-image {
            transform: scale(1.05);
        }

        /* Description bar beneath thumbnail */
        .rv-desc-bar {
            padding: 6px 8px;
            background-color: #dddddd;
            color: #1e293b;
            text-align: center;
            font-size: 12px;
            font-weight: 500;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: all 0.15s ease;
        }
        .dark .rv-desc-bar {
            background-color: #334155;
            color: #f1f5f9;
        }
        .rv-item-card.is-active .rv-desc-bar {
            background-color: #f24d23 !important;
            color: #ffffff !important;
        }

        /* Right Details Panel */
        .rv-details-aside {
            width: 270px;
            min-width: 270px;
            background-color: #ffffff;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            max-height: calc(100vh - 220px);
            flex-shrink: 0;
        }
        .dark .rv-details-aside {
            background-color: #0f172a;
            border-color: #334155;
        }

        /* Small Icons inside buttons and badges */
        .rv-mini-icon svg {
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            max-width: 16px !important;
        }
    </style>

    <div 
        x-data="{
            previewModal: false,
            createFolderModal: false,
            uploadModal: false,
            selectedItem: @entangle('activeItem').live,
            copied: false,
            selectItem(item) {
                this.selectedItem = item;
                $wire.selectItem(item);
            },
            copyUrl(url) {
                navigator.clipboard.writeText(url);
                this.copied = true;
                setTimeout(() => this.copied = false, 1500);
            }
        }" 
        class="w-full space-y-4"
    >
        {{-- MAIN CONTAINER --}}
        <div class="rv-container">
            {{-- 1. TOP HEADER (ACTIONS & SEARCH) --}}
            <div class="rv-header-top">
                {{-- Action buttons --}}
                <div class="flex items-center flex-wrap gap-2">
                    {{-- Upload button --}}
                    <button 
                        type="button"
                        x-on:click="uploadModal = true"
                        class="rv-btn-primary"
                        title="Tải tệp tin mới lên thư mục hiện tại"
                    >
                        <span class="rv-mini-icon"><x-heroicon-m-arrow-up-tray /></span>
                        <span>Tải lên</span>
                    </button>

                    {{-- Create folder button --}}
                    <button 
                        type="button"
                        x-on:click="createFolderModal = true"
                        class="rv-btn-primary"
                        title="Tạo thư mục mới trong thư mục hiện tại"
                    >
                        <span class="rv-mini-icon"><x-heroicon-m-folder-plus /></span>
                        <span>Tạo thư mục</span>
                    </button>

                    {{-- Refresh button --}}
                    <button 
                        type="button"
                        wire:click="$refresh"
                        class="rv-btn-outline"
                        title="Tải lại danh sách"
                    >
                        <span class="rv-mini-icon"><x-heroicon-m-arrow-path /></span>
                        <span>Làm mới</span>
                    </button>

                    @if($currentFolder)
                        <button 
                            type="button"
                            wire:click="goUp"
                            class="rv-btn-outline"
                            title="Lên một cấp thư mục"
                        >
                            <span class="rv-mini-icon"><x-heroicon-m-arrow-uturn-up /></span>
                            <span>Lên một cấp</span>
                        </button>
                    @endif
                </div>

                {{-- Search Box --}}
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <div class="relative w-full sm:w-72">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Tìm kiếm tệp & thư mục..."
                            class="w-full pl-9 pr-8 py-1.5 text-xs bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded focus:ring-1 focus:ring-amber-500 focus:border-amber-500 text-gray-900 dark:text-gray-100 placeholder-gray-400"
                        />
                        <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-gray-400 rv-mini-icon">
                            <x-heroicon-m-magnifying-glass />
                        </div>
                        @if($search)
                            <button 
                                type="button" 
                                wire:click="$set('search', '')" 
                                class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                            >
                                <span class="rv-mini-icon"><x-heroicon-m-x-mark /></span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 2. SUB HEADER (BREADCRUMBS & TOOLS) --}}
            <div class="rv-header-bottom">
                {{-- Breadcrumb trail --}}
                <nav class="flex items-center text-xs font-medium text-gray-600 dark:text-gray-300 overflow-x-auto py-1">
                    <button 
                        type="button" 
                        wire:click="goToBreadcrumb('')"
                        class="hover:text-amber-600 dark:hover:text-amber-400 flex items-center gap-1 cursor-pointer transition font-semibold"
                    >
                        <span class="rv-mini-icon text-amber-500"><x-heroicon-m-folder /></span>
                        <span>Tất cả tệp tin</span>
                    </button>

                    @foreach($folderBreadcrumbs as $index => $crumb)
                        @if($index > 0)
                            <span class="mx-1.5 text-gray-400">/</span>
                            <button 
                                type="button" 
                                wire:click="goToBreadcrumb('{{ $crumb['path'] }}')"
                                class="hover:text-amber-600 dark:hover:text-amber-400 cursor-pointer transition {{ $loop->last ? 'font-bold text-gray-900 dark:text-white' : '' }}"
                            >
                                {{ $crumb['name'] }}
                            </button>
                        @endif
                    @endforeach
                </nav>

                {{-- Tools: Sort, View Switcher & Details toggle --}}
                <div class="flex items-center gap-3">
                    {{-- Sort Dropdown --}}
                    <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-300">
                        <span class="hidden sm:inline">Sắp xếp:</span>
                        <select 
                            wire:model.live="sortBy"
                            class="text-xs py-1 px-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded focus:ring-1 focus:ring-amber-500 cursor-pointer"
                        >
                            <option value="created_at-desc">Ngày tạo (Mới nhất)</option>
                            <option value="created_at-asc">Ngày tạo (Cũ nhất)</option>
                            <option value="name-asc">Tên tệp (A &rarr; Z)</option>
                            <option value="name-desc">Tên tệp (Z &rarr; A)</option>
                            <option value="size-desc">Dung lượng (Lớn &rarr; Nhỏ)</option>
                            <option value="size-asc">Dung lượng (Nhỏ &rarr; Lớn)</option>
                        </select>
                    </div>

                    {{-- View mode: Tiles vs List --}}
                    <div class="inline-flex rounded border border-gray-300 dark:border-gray-700 overflow-hidden">
                        <button 
                            type="button"
                            wire:click="setViewType('tiles')"
                            class="p-1.5 {{ $viewType === 'tiles' ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'bg-white dark:bg-gray-800 text-gray-500 hover:text-gray-900' }} transition cursor-pointer"
                            title="Chế độ xem lưới (Tiles)"
                        >
                            <span class="rv-mini-icon"><x-heroicon-m-squares-2x2 /></span>
                        </button>
                        <button 
                            type="button"
                            wire:click="setViewType('list')"
                            class="p-1.5 {{ $viewType === 'list' ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' : 'bg-white dark:bg-gray-800 text-gray-500 hover:text-gray-900' }} transition cursor-pointer"
                            title="Chế độ xem danh sách (List)"
                        >
                            <span class="rv-mini-icon"><x-heroicon-m-list-bullet /></span>
                        </button>
                    </div>

                    {{-- Toggle details pane --}}
                    <button 
                        type="button"
                        wire:click="toggleDetails"
                        class="p-1.5 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:text-gray-900 transition cursor-pointer"
                        title="{{ $showDetails ? 'Ẩn bảng thông tin chi tiết' : 'Hiện bảng thông tin chi tiết' }}"
                    >
                        <span class="rv-mini-icon"><x-heroicon-m-information-circle /></span>
                    </button>
                </div>
            </div>

            {{-- 3. MAIN BODY (LEFT ASIDE + CENTER ITEMS + RIGHT DETAILS) --}}
            <div class="rv-main-area">
                {{-- LEFT ASIDE: FILTERS & CATEGORIES --}}
                <aside class="rv-aside hidden md:flex">
                    <div class="space-y-4">
                        {{-- BỘ LỌC --}}
                        <div>
                            <div class="rv-aside-title">Bộ lọc</div>
                            <div>
                                <div 
                                    wire:click="setFilter('everything')"
                                    class="rv-aside-item {{ $filter === 'everything' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-circle-stack /></span>
                                    <span>Tất cả</span>
                                </div>
                                <div 
                                    wire:click="setFilter('image')"
                                    class="rv-aside-item {{ $filter === 'image' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-photo /></span>
                                    <span>Hình ảnh</span>
                                </div>
                                <div 
                                    wire:click="setFilter('video')"
                                    class="rv-aside-item {{ $filter === 'video' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-film /></span>
                                    <span>Video</span>
                                </div>
                                <div 
                                    wire:click="setFilter('document')"
                                    class="rv-aside-item {{ $filter === 'document' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-document-text /></span>
                                    <span>Tài liệu</span>
                                </div>
                            </div>
                        </div>

                        {{-- XEM THEO --}}
                        <div>
                            <div class="rv-aside-title">Xem theo</div>
                            <div>
                                <div 
                                    wire:click="setViewIn('all_media')"
                                    class="rv-aside-item {{ $viewIn === 'all_media' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-globe-alt /></span>
                                    <span>Tất cả tệp tin</span>
                                </div>
                                <div 
                                    wire:click="setViewIn('recent')"
                                    class="rv-aside-item {{ $viewIn === 'recent' ? 'active' : '' }}"
                                >
                                    <span class="rv-mini-icon"><x-heroicon-m-clock /></span>
                                    <span>Gần đây (7 ngày)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ASIDE BOTTOM: STORAGE USAGE --}}
                    <div class="p-4 border-t border-teal-900/40">
                        <div class="text-[11px] text-gray-300 font-medium mb-1.5 flex justify-between">
                            <span>Dung lượng đã dùng:</span>
                            <span class="font-bold text-white">{{ $storageQuota['used'] }}</span>
                        </div>
                        <div class="w-full bg-teal-950 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $storageQuota['percent'] }}%"></div>
                        </div>
                        <div class="text-[10px] text-gray-400 mt-1">
                            Trống: {{ $storageQuota['free'] }}
                        </div>
                    </div>
                </aside>

                {{-- CENTER: ITEMS DISPLAY (TILES OR LIST) --}}
                <div class="rv-items-scroll">
                    @if(empty($folders) && $files->isEmpty())
                        <div class="py-16 text-center text-gray-400 space-y-3">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto text-gray-400">
                                <span class="rv-doc-icon"><x-heroicon-o-folder /></span>
                            </div>
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Thư mục hiện tại đang trống</h4>
                            <p class="text-xs text-gray-500 max-w-sm mx-auto">
                                Không có thư mục hay tệp tin nào khớp với điều kiện lọc. Hãy tải ảnh lên hoặc tạo thư mục mới.
                            </p>
                            <button 
                                type="button"
                                x-on:click="uploadModal = true"
                                class="rv-btn-primary mx-auto"
                            >
                                <span class="rv-mini-icon"><x-heroicon-m-arrow-up-tray /></span>
                                <span>Tải tệp tin lên</span>
                            </button>
                        </div>
                    @else
                        {{-- TILES / GRID VIEW --}}
                        @if($viewType === 'tiles')
                            <div class="rv-media-grid">
                                {{-- Up one level tile if inside a subfolder --}}
                                @if($currentFolder)
                                    <div 
                                        wire:click="goUp"
                                        class="rv-item-card"
                                        title="Lên thư mục cấp cha"
                                    >
                                        <div class="rv-thumb-box">
                                            <div class="rv-uplevel-icon">
                                                <x-heroicon-m-arrow-uturn-up />
                                            </div>
                                        </div>
                                        <div class="rv-desc-bar">
                                            ... (Lên một cấp)
                                        </div>
                                    </div>
                                @endif

                                {{-- FOLDERS LIST --}}
                                @foreach($folders as $folder)
                                    @php
                                        $fData = [
                                            'is_folder' => true,
                                            'name' => $folder['name'],
                                            'path' => $folder['path'],
                                            'items_count' => $folder['items_count'],
                                            'date' => $folder['date'],
                                            'size' => $folder['size'],
                                            'url' => '',
                                        ];
                                    @endphp
                                    <div 
                                        x-on:click="selectItem(@js($fData))"
                                        x-on:dblclick="$wire.openFolder('{{ addslashes($folder['path']) }}')"
                                        :class="{ 'is-active': selectedItem && selectedItem.is_folder && selectedItem.path === '{{ addslashes($folder['path']) }}' }"
                                        class="rv-item-card"
                                        title="{{ $folder['name'] }} (Nhấp đúp để mở thư mục)"
                                    >
                                        {{-- 1:1 Square Thumbnail with 70px Folder Icon --}}
                                        <div class="rv-thumb-box">
                                            <div class="rv-folder-icon">
                                                <x-heroicon-s-folder />
                                            </div>
                                            {{-- Items badge --}}
                                            <span class="absolute top-1.5 right-1.5 text-[9px] font-semibold font-mono px-1.5 py-0.5 rounded bg-black/60 text-white backdrop-blur-xs">
                                                {{ $folder['items_count'] }} mục
                                            </span>
                                        </div>

                                        {{-- Description caption bar --}}
                                        <div class="rv-desc-bar" title="{{ $folder['name'] }}">
                                            {{ $folder['name'] }}
                                        </div>
                                    </div>
                                @endforeach

                                {{-- FILES LIST --}}
                                @foreach($files as $file)
                                    <div 
                                        x-on:click="selectItem(@js($file))"
                                        x-on:dblclick="
                                            selectItem(@js($file));
                                            previewModal = true;
                                        "
                                        :class="{ 'is-active': selectedItem && !selectedItem.is_folder && selectedItem.relative_path === '{{ addslashes($file['relative_path']) }}' }"
                                        class="rv-item-card"
                                        title="{{ $file['name'] }}"
                                    >
                                        {{-- 1:1 Square Thumbnail (Exact same dimensions as folder) --}}
                                        <div class="rv-thumb-box">
                                            {{-- Extension badge --}}
                                            <span class="absolute top-1.5 left-1.5 z-10 text-[9px] font-bold font-mono px-1.5 py-0.5 rounded bg-black/70 text-white uppercase shadow-xs">
                                                {{ $file['ext'] }}
                                            </span>

                                            {{-- Size badge --}}
                                            <span class="absolute top-1.5 right-1.5 z-10 text-[9px] font-medium px-1.5 py-0.5 rounded bg-black/70 text-gray-200 shadow-xs">
                                                {{ $file['size'] }}
                                            </span>

                                            @if($file['is_image'])
                                                <img 
                                                    src="{{ $file['url'] }}" 
                                                    alt="{{ $file['name'] }}" 
                                                    loading="lazy" 
                                                    class="rv-thumb-image"
                                                    onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23999\' stroke-width=\'2\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><path d=\'m21 15-5-5L5 21\'/></svg>';" 
                                                />
                                            @elseif($file['is_video'])
                                                <div class="rv-doc-icon text-indigo-500">
                                                    <x-heroicon-o-film />
                                                </div>
                                            @else
                                                <div class="rv-doc-icon">
                                                    <x-heroicon-o-document-text />
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Description caption bar --}}
                                        <div class="rv-desc-bar" title="{{ $file['name'] }}">
                                            {{ $file['name'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- LIST VIEW (TABLE-LIKE) --}}
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded overflow-hidden">
                                <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                                    <thead class="bg-gray-100 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 uppercase font-semibold">
                                        <tr>
                                            <th class="py-2.5 px-3">Tên</th>
                                            <th class="py-2.5 px-3 w-32">Kích thước</th>
                                            <th class="py-2.5 px-3 w-40">Ngày tạo</th>
                                            <th class="py-2.5 px-3 w-28 text-right">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        @if($currentFolder)
                                            <tr wire:click="goUp" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer">
                                                <td class="py-2 px-3 flex items-center gap-2 font-medium">
                                                    <span class="rv-mini-icon text-gray-500"><x-heroicon-m-arrow-uturn-up /></span>
                                                    <span>.. (Lên một cấp)</span>
                                                </td>
                                                <td class="py-2 px-3 text-gray-400">-</td>
                                                <td class="py-2 px-3 text-gray-400">-</td>
                                                <td class="py-2 px-3 text-right"></td>
                                            </tr>
                                        @endif

                                        {{-- Folders in List --}}
                                        @foreach($folders as $folder)
                                            @php
                                                $fData = [
                                                    'is_folder' => true,
                                                    'name' => $folder['name'],
                                                    'path' => $folder['path'],
                                                    'items_count' => $folder['items_count'],
                                                    'date' => $folder['date'],
                                                    'size' => $folder['size'],
                                                    'url' => '',
                                                ];
                                            @endphp
                                            <tr 
                                                x-on:click="selectItem(@js($fData))"
                                                x-on:dblclick="$wire.openFolder('{{ addslashes($folder['path']) }}')"
                                                :class="{ 'bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200 font-semibold': selectedItem && selectedItem.is_folder && selectedItem.path === '{{ addslashes($folder['path']) }}' }"
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition"
                                            >
                                                <td class="py-2 px-3 flex items-center gap-2">
                                                    <span class="rv-mini-icon text-amber-500"><x-heroicon-s-folder /></span>
                                                    <span class="truncate max-w-md">{{ $folder['name'] }}</span>
                                                </td>
                                                <td class="py-2 px-3">{{ $folder['items_count'] }} mục</td>
                                                <td class="py-2 px-3 text-gray-400">{{ $folder['date'] }}</td>
                                                <td class="py-2 px-3 text-right">
                                                    <button 
                                                        type="button" 
                                                        wire:click="openFolder('{{ $folder['path'] }}')" 
                                                        class="text-amber-600 hover:text-amber-700 font-medium mr-2"
                                                    >Mở</button>
                                                </td>
                                            </tr>
                                        @endforeach

                                        {{-- Files in List --}}
                                        @foreach($files as $file)
                                            <tr 
                                                x-on:click="selectItem(@js($file))"
                                                :class="{ 'bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200 font-semibold': selectedItem && !selectedItem.is_folder && selectedItem.relative_path === '{{ addslashes($file['relative_path']) }}' }"
                                                class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition"
                                            >
                                                <td class="py-2 px-3 flex items-center gap-2">
                                                    @if($file['is_image'])
                                                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-6 h-6 object-cover rounded" />
                                                    @else
                                                        <span class="rv-mini-icon text-gray-400"><x-heroicon-m-document /></span>
                                                    @endif
                                                    <span class="truncate max-w-md">{{ $file['name'] }}</span>
                                                </td>
                                                <td class="py-2 px-3 font-mono text-[11px]">{{ $file['size'] }}</td>
                                                <td class="py-2 px-3 text-gray-400">{{ $file['date'] }}</td>
                                                <td class="py-2 px-3 text-right">
                                                    <button 
                                                        type="button"
                                                        x-on:click="copyUrl('{{ $file['url'] }}')"
                                                        class="text-blue-600 hover:text-blue-700 mr-2"
                                                        title="Sao chép link"
                                                    >Copy</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        {{-- Pagination links --}}
                        <div class="mt-6 flex justify-center">
                            {{ $files->links() }}
                        </div>
                    @endif
                </div>

                {{-- RIGHT ASIDE: DETAILS PANEL (270px width) --}}
                @if($showDetails)
                    <aside class="rv-details-aside p-4 space-y-4">
                        <template x-if="selectedItem">
                            <div class="space-y-4">
                                {{-- Thumbnail preview box --}}
                                <div class="w-full aspect-square rounded border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 flex items-center justify-center overflow-hidden p-2 relative">
                                    <template x-if="selectedItem.is_folder">
                                        <div class="rv-folder-icon">
                                            <x-heroicon-s-folder />
                                        </div>
                                    </template>
                                    <template x-if="!selectedItem.is_folder && selectedItem.is_image">
                                        <img :src="selectedItem.url" :alt="selectedItem.name" class="max-h-full max-w-full object-contain" />
                                    </template>
                                    <template x-if="!selectedItem.is_folder && !selectedItem.is_image">
                                        <div class="rv-doc-icon">
                                            <x-heroicon-o-document-text />
                                        </div>
                                    </template>
                                </div>

                                {{-- Name & Basic info --}}
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900 dark:text-gray-100 break-words" x-text="selectedItem.name"></h4>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        <span x-text="selectedItem.is_folder ? 'Thư mục' : selectedItem.ext"></span> &bull; 
                                        <span x-text="selectedItem.size"></span>
                                    </p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        Cập nhật: <span x-text="selectedItem.date"></span>
                                    </p>
                                </div>

                                {{-- URL Copy input (for files) --}}
                                <template x-if="!selectedItem.is_folder">
                                    <div class="space-y-1.5">
                                        <label class="block text-[11px] font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                            Đường dẫn tệp tin (URL)
                                        </label>
                                        <div class="flex items-center gap-1">
                                            <input 
                                                type="text" 
                                                readonly 
                                                :value="selectedItem.url" 
                                                class="w-full text-[11px] font-mono py-1 px-2 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded select-all"
                                            />
                                            <button 
                                                type="button" 
                                                x-on:click="copyUrl(selectedItem.url)"
                                                class="p-1.5 rounded bg-amber-500 hover:bg-amber-600 text-white transition flex-shrink-0 cursor-pointer"
                                                title="Sao chép đường dẫn"
                                            >
                                                <span class="rv-mini-icon"><x-heroicon-m-clipboard /></span>
                                            </button>
                                        </div>
                                        <template x-if="copied">
                                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                                <span class="rv-mini-icon"><x-heroicon-m-check /></span> Đã chép vào bộ nhớ tạm!
                                            </span>
                                        </template>
                                    </div>
                                </template>

                                {{-- Action buttons --}}
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-800 space-y-2">
                                    {{-- For folders: Open folder --}}
                                    <template x-if="selectedItem.is_folder">
                                        <button 
                                            type="button"
                                            x-on:click="$wire.openFolder(selectedItem.path)"
                                            class="w-full py-1.5 px-3 text-xs font-semibold rounded bg-amber-600 hover:bg-amber-700 text-white transition flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <span class="rv-mini-icon"><x-heroicon-m-folder-open /></span>
                                            <span>Mở thư mục này</span>
                                        </button>
                                    </template>

                                    {{-- For images: Preview lightbox --}}
                                    <template x-if="!selectedItem.is_folder && selectedItem.is_image">
                                        <button 
                                            type="button"
                                            x-on:click="previewModal = true"
                                            class="w-full py-1.5 px-3 text-xs font-semibold rounded bg-amber-600 hover:bg-amber-700 text-white transition flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <span class="rv-mini-icon"><x-heroicon-m-eye /></span>
                                            <span>Xem ảnh kích thước lớn</span>
                                        </button>
                                    </template>

                                    {{-- Delete button --}}
                                    <template x-if="selectedItem.is_folder">
                                        <button 
                                            type="button"
                                            wire:confirm="Bạn có chắc chắn muốn xóa thư mục này và toàn bộ nội dung bên trong?"
                                            x-on:click="$wire.deleteFolder(selectedItem.path)"
                                            class="w-full py-1.5 px-3 text-xs font-semibold rounded bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 transition flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <span class="rv-mini-icon"><x-heroicon-m-trash /></span>
                                            <span>Xóa thư mục</span>
                                        </button>
                                    </template>

                                    <template x-if="!selectedItem.is_folder">
                                        <button 
                                            type="button"
                                            wire:confirm="Bạn có chắc chắn muốn xóa tệp tin này?"
                                            x-on:click="$wire.deleteFile(selectedItem.relative_path)"
                                            class="w-full py-1.5 px-3 text-xs font-semibold rounded bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 transition flex items-center justify-center gap-1.5 cursor-pointer"
                                        >
                                            <span class="rv-mini-icon"><x-heroicon-m-trash /></span>
                                            <span>Xóa tệp tin</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <template x-if="!selectedItem">
                            <div class="h-full flex flex-col items-center justify-center text-center p-4 text-gray-400 space-y-2">
                                <span class="rv-doc-icon text-gray-300"><x-heroicon-o-information-circle /></span>
                                <p class="text-xs font-medium text-gray-500">Chưa chọn tệp hoặc thư mục nào</p>
                                <p class="text-[11px] text-gray-400">Nhấp vào một thư mục hoặc ảnh để xem thông tin chi tiết và thao tác.</p>
                            </div>
                        </template>
                    </aside>
                @endif
            </div>
        </div>

        {{-- MODAL 1: CREATE NEW FOLDER --}}
        <div 
            x-show="createFolderModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex items-center justify-center min-h-screen px-4 p-0">
                <div 
                    x-show="createFolderModal"
                    x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-black/60 transition-opacity" 
                    x-on:click="createFolderModal = false"
                ></div>

                <div 
                    x-show="createFolderModal"
                    x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative bg-white dark:bg-gray-900 rounded-lg max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800 space-y-4"
                >
                    <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span class="rv-mini-icon text-amber-500"><x-heroicon-m-folder-plus /></span>
                            <span>Tạo thư mục mới</span>
                        </h3>
                        <button type="button" x-on:click="createFolderModal = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>

                    <form wire:submit.prevent="createFolder" class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tên thư mục:
                            </label>
                            <input 
                                type="text" 
                                wire:model="newFolderName" 
                                placeholder="Nhập tên thư mục (ví dụ: banner-2026, du-an)..."
                                class="w-full text-xs py-2 px-3 border border-gray-300 dark:border-gray-700 rounded focus:ring-1 focus:ring-amber-500 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800"
                                autofocus
                            />
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button 
                                type="button" 
                                x-on:click="createFolderModal = false"
                                class="rv-btn-outline"
                            >
                                Hủy bỏ
                            </button>
                            <button 
                                type="submit"
                                class="rv-btn-primary"
                                x-on:click="createFolderModal = false"
                            >
                                Tạo thư mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL 2: UPLOAD FILES --}}
        <div 
            x-show="uploadModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
        >
            <div class="flex items-center justify-center min-h-screen px-4 p-0">
                <div 
                    x-show="uploadModal"
                    x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-black/60 transition-opacity" 
                    x-on:click="uploadModal = false"
                ></div>

                <div 
                    x-show="uploadModal"
                    x-transition:enter="ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative bg-white dark:bg-gray-900 rounded-lg max-w-lg w-full p-6 shadow-xl border border-gray-200 dark:border-gray-800 space-y-4"
                >
                    <div class="flex items-center justify-between border-b pb-3 border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span class="rv-mini-icon text-amber-500"><x-heroicon-m-arrow-up-tray /></span>
                            <span>Tải tệp tin lên thư mục hiện tại</span>
                        </h3>
                        <button type="button" x-on:click="uploadModal = false" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>

                    <form wire:submit.prevent="uploadFiles" class="space-y-4">
                        <div class="p-6 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800 text-center space-y-2">
                            <span class="rv-doc-icon text-gray-400 mx-auto block"><x-heroicon-o-arrow-up-tray /></span>
                            <div class="text-xs text-gray-600 dark:text-gray-300">
                                Chọn một hoặc nhiều tệp từ máy tính để tải lên
                            </div>
                            <input 
                                type="file" 
                                wire:model="newFiles" 
                                multiple 
                                accept="image/png,image/jpeg,image/webp,image/svg+xml,image/gif,application/pdf,video/mp4"
                                class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100"
                            />
                        </div>

                        <div class="text-[11px] text-gray-500">
                            Thư mục đích: <code class="font-mono text-amber-600 bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded">uploads/{{ $currentFolder ?: '' }}</code>
                        </div>

                        @error('newFiles.*') 
                            <span class="text-xs text-red-500 block">{{ $message }}</span> 
                        @enderror

                        <div class="flex justify-end gap-2 pt-2">
                            <button 
                                type="button" 
                                x-on:click="uploadModal = false"
                                class="rv-btn-outline"
                            >
                                Hủy bỏ
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="rv-btn-primary"
                            >
                                <span wire:loading.remove wire:target="newFiles, uploadFiles" class="flex items-center gap-1">
                                    <span class="rv-mini-icon"><x-heroicon-m-arrow-up-tray /></span> Tải lên ngay
                                </span>
                                <span wire:loading wire:target="newFiles, uploadFiles" class="flex items-center gap-1">
                                    Đang tải lên...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL 3: LIGHTBOX PREVIEW MODAL --}}
        <div 
            x-show="previewModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            role="dialog" 
            aria-modal="true"
        >
            <div class="flex items-center justify-center min-h-screen px-4 p-0">
                <div 
                    x-show="previewModal"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" 
                    x-on:click="previewModal = false"
                ></div>

                <div 
                    x-show="previewModal"
                    x-transition:enter="ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    class="relative bg-white dark:bg-gray-900 rounded-xl text-left overflow-hidden shadow-2xl max-w-4xl w-full border border-gray-200 dark:border-gray-800 my-8"
                >
                    <div class="px-6 py-3 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
                        <div class="truncate mr-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate" x-text="selectedItem?.name"></h3>
                            <p class="text-[11px] text-gray-500">
                                Dung lượng: <span class="font-mono text-gray-700 dark:text-gray-300" x-text="selectedItem?.size"></span> | 
                                Ngày: <span class="text-gray-700 dark:text-gray-300" x-text="selectedItem?.date"></span>
                            </p>
                        </div>
                        <button 
                            type="button" 
                            x-on:click="previewModal = false" 
                            class="text-gray-400 hover:text-gray-600 text-2xl font-light leading-none cursor-pointer"
                        >&times;</button>
                    </div>

                    <div class="p-6 flex items-center justify-center bg-gray-950 min-h-[350px] max-h-[550px] overflow-hidden">
                        <template x-if="selectedItem?.is_image">
                            <img :src="selectedItem?.url" :alt="selectedItem?.name" class="max-h-[500px] max-w-full object-contain rounded" />
                        </template>
                        <template x-if="!selectedItem?.is_image">
                            <div class="text-center p-8 text-gray-400">
                                <span class="rv-doc-icon mx-auto block"><x-heroicon-o-document-text /></span>
                                <p class="text-sm text-gray-300 mt-2">Tệp tin không hỗ trợ xem ảnh trực tiếp.</p>
                            </div>
                        </template>
                    </div>

                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-800 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200 dark:border-gray-800">
                        <div class="w-full sm:w-auto flex-1 font-mono text-[11px] text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-900 px-3 py-1.5 rounded border border-gray-300 dark:border-gray-700 truncate select-all" x-text="selectedItem?.url"></div>
                        <div class="flex items-center gap-2">
                            <button 
                                type="button"
                                x-on:click="copyUrl(selectedItem?.url)"
                                class="rv-btn-primary"
                            >
                                <span class="rv-mini-icon"><x-heroicon-m-clipboard /></span>
                                <span x-text="copied ? 'Đã sao chép!' : 'Sao chép link'"></span>
                            </button>
                            <button 
                                type="button" 
                                x-on:click="previewModal = false" 
                                class="rv-btn-outline"
                            >
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
