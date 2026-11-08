<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use UnitEnum;

class ThemeOptions extends Page
{
    use WithFileUploads, WithPagination;

    protected string $view = 'filament.pages.theme-options';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static string|UnitEnum|null $navigationGroup = 'Giao diện';

    protected static ?string $title = 'Tùy chọn giao diện';

    protected static ?string $navigationLabel = 'Tùy chọn giao diện';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'theme/options';

    public array $settings = [];

    public string $activeTab = 'logo'; // 'logo', 'font', 'general'

    public $logoUpload = null;

    public $faviconUpload = null;

    public bool $showMediaPicker = false;

    public string $mediaPickerTarget = ''; // 'logo' or 'favicon'

    public string $mediaSearch = '';

    public int $mediaPerPage = 24;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function getGoogleFonts(): array
    {
        return [
            'Roboto' => 'Roboto (Mặc định - Phổ biến, hiển thị chuẩn mọi thiết bị)',
            'Be Vietnam Pro' => 'Be Vietnam Pro (Thiết kế tối ưu riêng cho Tiếng Việt)',
            'Inter' => 'Inter (Hiện đại, nét thanh thoát, chuẩn UI/UX cao cấp)',
            'Montserrat' => 'Montserrat (Hình học sắc nét, sang trọng)',
            'Open Sans' => 'Open Sans (Dễ đọc, trung tính, thân thiện)',
            'Nunito' => 'Nunito (Bo tròn nhẹ nhàng, tươi sáng)',
            'Poppins' => 'Poppins (Hình học hiện đại, phong cách quốc tế)',
            'Playfair Display' => 'Playfair Display (Có chân serif cổ điển, cao cấp)',
            'Merriweather' => 'Merriweather (Serif trang trọng, tối ưu cho bài đọc dài)',
            'Raleway' => 'Raleway (Thanh lịch, hiện đại, tiêu đề nổi bật)',
            'Lato' => 'Lato (Cân đối, ấm áp, cấu trúc vững chãi)',
            'Quicksand' => 'Quicksand (Nét tròn thân thiện, công nghệ)',
            'Mulish' => 'Mulish (Tối giản, chuyên nghiệp)',
            'Source Sans 3' => 'Source Sans 3 (Chuẩn Adobe, rõ ràng, dễ nhìn)',
            'Oswald' => 'Oswald (Tiêu đề đậm, cô đọng, mạnh mẽ)',
            'Cabin' => 'Cabin',
            'Cardo' => 'Cardo',
            'Crimson Text' => 'Crimson Text',
            'EB Garamond' => 'EB Garamond',
            'Josefin Sans' => 'Josefin Sans',
            'Lora' => 'Lora',
            'Maven Pro' => 'Maven Pro',
            'Noto Sans' => 'Noto Sans',
            'PT Sans' => 'PT Sans',
            'PT Serif' => 'PT Serif',
            'Ubuntu' => 'Ubuntu',
            'Vollkorn' => 'Vollkorn',
        ];
    }

    public function mount(): void
    {
        $existing = Setting::pluck('value', 'key')->toArray();

        $defaults = [
            'theme-ripple-logo' => '/storage/uploads/gst-logo-01.png',
            'logo' => '/storage/uploads/gst-logo-01.png',
            'logo_height' => '50',
            'favicon' => '/storage/uploads/gst-logo-01.png',
            'primary_font' => 'Roboto',
            'theme-ripple-primary_font' => 'Roboto',
            'theme-ripple-copyright' => 'Minh Thành Group',
            'custom_css' => '',
        ];

        $this->settings = array_merge($defaults, $existing);

        // Ensure aliases stay in sync on load
        if (!empty($this->settings['logo']) && empty($this->settings['theme-ripple-logo'])) {
            $this->settings['theme-ripple-logo'] = $this->settings['logo'];
        }
        if (!empty($this->settings['theme-ripple-logo']) && empty($this->settings['logo'])) {
            $this->settings['logo'] = $this->settings['theme-ripple-logo'];
        }
        if (!empty($this->settings['primary_font']) && empty($this->settings['theme-ripple-primary_font'])) {
            $this->settings['theme-ripple-primary_font'] = $this->settings['primary_font'];
        }
    }

    public function openMediaPicker(string $target): void
    {
        $this->mediaPickerTarget = $target;
        $this->mediaSearch = '';
        $this->resetPage();
        $this->showMediaPicker = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaPicker = false;
        $this->mediaPickerTarget = '';
    }

    public function selectMedia(string $path): void
    {
        if ($this->mediaPickerTarget === 'logo') {
            $this->settings['theme-ripple-logo'] = $path;
            $this->settings['logo'] = $path;
            $this->logoUpload = null;
        } elseif ($this->mediaPickerTarget === 'favicon') {
            $this->settings['favicon'] = $path;
            $this->faviconUpload = null;
        }

        $this->closeMediaPicker();

        Notification::make()
            ->title('Đã chọn hình ảnh thành công!')
            ->success()
            ->send();
    }

    public function resetLogo(): void
    {
        $this->settings['theme-ripple-logo'] = '/storage/uploads/gst-logo-01.png';
        $this->settings['logo'] = '/storage/uploads/gst-logo-01.png';
        $this->logoUpload = null;
    }

    public function resetFavicon(): void
    {
        $this->settings['favicon'] = '/storage/uploads/gst-logo-01.png';
        $this->faviconUpload = null;
    }

    public function resetFont(): void
    {
        $this->settings['primary_font'] = 'Roboto';
        $this->settings['theme-ripple-primary_font'] = 'Roboto';
    }

    public function getMediaFiles()
    {
        $dir = storage_path('app/public/uploads');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true, true);
        }

        $allFiles = File::allFiles($dir);
        $fileList = collect($allFiles)
            ->filter(function (\Symfony\Component\Finder\SplFileInfo $file) {
                return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico']);
            })
            ->map(function (\Symfony\Component\Finder\SplFileInfo $file) {
                $rel = str_replace(storage_path('app/public') . '/', '', $file->getPathname());
                return [
                    'name' => $file->getFilename(),
                    'relative_path' => $rel,
                    'url' => '/storage/' . $rel,
                    'mtime' => $file->getMTime(),
                ];
            });

        if ($this->mediaSearch) {
            $keyword = mb_strtolower($this->mediaSearch);
            $fileList = $fileList->filter(function ($item) use ($keyword) {
                return str_contains(mb_strtolower($item['name']), $keyword);
            });
        }

        $sorted = $fileList->sortByDesc('mtime')->values();

        $page = $this->getPage();
        $total = $sorted->count();
        $pagedData = $sorted->slice(($page - 1) * $this->mediaPerPage, $this->mediaPerPage)->values();

        return new LengthAwarePaginator(
            $pagedData,
            $total,
            $this->mediaPerPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    public function save(): void
    {
        // Handle uploaded logo
        if ($this->logoUpload) {
            $this->validate([
                'logoUpload' => 'image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            ]);

            $filename = 'logo_' . time() . '.' . $this->logoUpload->getClientOriginalExtension();
            $this->logoUpload->storeAs('uploads', $filename, 'public');
            $logoPath = '/storage/uploads/' . $filename;

            $this->settings['theme-ripple-logo'] = $logoPath;
            $this->settings['logo'] = $logoPath;
            $this->logoUpload = null;
        }

        // Handle uploaded favicon
        if ($this->faviconUpload) {
            $this->validate([
                'faviconUpload' => 'file|mimes:ico,png,jpg,jpeg,svg|max:2048',
            ]);

            $filename = 'favicon_' . time() . '.' . $this->faviconUpload->getClientOriginalExtension();
            $this->faviconUpload->storeAs('uploads', $filename, 'public');
            $faviconPath = '/storage/uploads/' . $filename;

            $this->settings['favicon'] = $faviconPath;
            $this->faviconUpload = null;
        }

        // Synchronize alias keys
        $this->settings['logo'] = $this->settings['theme-ripple-logo'] ?? '';
        $this->settings['theme-ripple-primary_font'] = $this->settings['primary_font'] ?? 'Roboto';

        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        Notification::make()
            ->title('Cập nhật cấu hình giao diện thành công!')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'fonts' => static::getGoogleFonts(),
            'mediaFiles' => $this->showMediaPicker ? $this->getMediaFiles() : null,
        ];
    }
}

