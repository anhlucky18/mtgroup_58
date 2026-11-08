<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use UnitEnum;

class MediaManager extends Page
{
    use WithPagination, WithFileUploads;

    protected string $view = 'filament.pages.media-manager';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?string $title = 'Thư viện hình ảnh';

    protected static ?string $navigationLabel = 'Thư viện ảnh (Media)';

    protected static ?int $navigationSort = 2;

    public string $currentFolder = '';

    public string $search = '';

    public string $filter = 'everything'; // everything, image, video, document

    public string $viewIn = 'all_media'; // all_media, recent

    public string $sortBy = 'created_at-desc'; // created_at-desc, created_at-asc, name-asc, name-desc, size-desc, size-asc

    public string $viewType = 'tiles'; // tiles, list

    public bool $showDetails = true;

    public ?array $activeItem = null;

    public string $newFolderName = '';

    public bool $createFolderModalOpen = false;

    public bool $uploadModalOpen = false;

    public array $newFiles = [];

    public int $perPage = 48;

    public function getMaxWidth(): Width | string | null
    {
        return Width::Full;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function updatingCurrentFolder(): void
    {
        $this->resetPage();
        $this->activeItem = null;
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function setViewIn(string $viewIn): void
    {
        $this->viewIn = $viewIn;
        $this->resetPage();
    }

    public function setSortBy(string $sortBy): void
    {
        $this->sortBy = $sortBy;
        $this->resetPage();
    }

    public function setViewType(string $viewType): void
    {
        $this->viewType = $viewType;
    }

    public function toggleDetails(): void
    {
        $this->showDetails = ! $this->showDetails;
    }

    public function selectItem(array $item): void
    {
        $this->activeItem = $item;
    }

    public function openFolder(string $path): void
    {
        $this->currentFolder = $this->sanitizePath($path);
        $this->resetPage();
        $this->search = '';
        $this->activeItem = null;
    }

    public function goToBreadcrumb(string $path = ''): void
    {
        $this->currentFolder = $this->sanitizePath($path);
        $this->resetPage();
        $this->activeItem = null;
    }

    public function goUp(): void
    {
        if (empty($this->currentFolder)) {
            return;
        }

        $clean = $this->sanitizePath($this->currentFolder);
        $parent = dirname($clean);
        $this->currentFolder = ($parent === '.' || $parent === '/' || $parent === '\\') ? '' : $parent;
        $this->resetPage();
        $this->activeItem = null;
    }

    public function createFolder(): void
    {
        $cleanName = trim($this->newFolderName);
        if (empty($cleanName)) {
            Notification::make()
                ->title('Vui lòng nhập tên thư mục.')
                ->warning()
                ->send();
            return;
        }

        $slugName = Str::slug($cleanName);
        if (empty($slugName)) {
            $slugName = preg_replace('/[^a-zA-Z0-9_-]/', '', $cleanName);
        }

        $baseDir = storage_path('app/public/uploads');
        $targetDir = $baseDir . ($this->currentFolder ? '/' . $this->sanitizePath($this->currentFolder) : '') . '/' . $slugName;

        if (File::isDirectory($targetDir)) {
            Notification::make()
                ->title('Thư mục này đã tồn tại!')
                ->danger()
                ->send();
            return;
        }

        File::makeDirectory($targetDir, 0755, true, true);

        $this->newFolderName = '';
        $this->createFolderModalOpen = false;

        Notification::make()
            ->title("Đã tạo thư mục '{$slugName}' thành công!")
            ->success()
            ->send();
    }

    public function deleteFolder(string $folderPath): void
    {
        $clean = $this->sanitizePath($folderPath);
        if (empty($clean)) {
            Notification::make()
                ->title('Không thể xóa thư mục gốc!')
                ->danger()
                ->send();
            return;
        }

        $targetDir = storage_path('app/public/uploads/' . $clean);
        if (File::isDirectory($targetDir)) {
            File::deleteDirectory($targetDir);
            $this->activeItem = null;
            Notification::make()
                ->title('Đã xóa thư mục thành công!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Không tìm thấy thư mục cần xóa.')
                ->danger()
                ->send();
        }
    }

    public function uploadFiles(): void
    {
        $this->validate([
            'newFiles.*' => 'required|file|mimes:jpeg,png,jpg,webp,svg,gif,pdf,mp4,zip|max:51200',
        ]);

        $folderSubpath = 'uploads' . ($this->currentFolder ? '/' . $this->sanitizePath($this->currentFolder) : '');
        $absDir = storage_path('app/public/' . $folderSubpath);

        if (! File::isDirectory($absDir)) {
            File::makeDirectory($absDir, 0755, true, true);
        }

        $uploadedCount = 0;
        foreach ($this->newFiles as $file) {
            $filename = $file->getClientOriginalName();

            if (file_exists($absDir . '/' . $filename)) {
                $filename = time() . '_' . $filename;
            }

            $file->storeAs($folderSubpath, $filename, 'public');
            $uploadedCount++;
        }

        $this->newFiles = [];
        $this->uploadModalOpen = false;

        Notification::make()
            ->title("Đã tải lên thành công {$uploadedCount} tệp tin!")
            ->success()
            ->send();
    }

    public function deleteFile(string $relativePath): void
    {
        $cleanPath = ltrim(str_replace(['../', '..\\'], '', $relativePath), '/');
        if (Storage::disk('public')->exists($cleanPath)) {
            Storage::disk('public')->delete($cleanPath);
            $this->activeItem = null;
            Notification::make()
                ->title('Đã xóa tệp tin thành công!')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Không tìm thấy tệp tin cần xóa.')
                ->danger()
                ->send();
        }
    }

    public function sanitizePath(string $path): string
    {
        $clean = str_replace('\\', '/', $path);
        $clean = trim($clean, '/ ');
        $parts = array_filter(explode('/', $clean), fn ($p) => $p !== '' && $p !== '.' && $p !== '..');
        return implode('/', $parts);
    }

    public function getFolderBreadcrumbs(): array
    {
        $breadcrumbs = [
            ['name' => 'Tất cả tệp tin', 'path' => ''],
        ];

        if (empty($this->currentFolder)) {
            return $breadcrumbs;
        }

        $parts = explode('/', $this->sanitizePath($this->currentFolder));
        $cumulative = '';
        foreach ($parts as $part) {
            $cumulative = $cumulative ? $cumulative . '/' . $part : $part;
            $breadcrumbs[] = [
                'name' => $part,
                'path' => $cumulative,
            ];
        }

        return $breadcrumbs;
    }

    public function getMediaData(): array
    {
        $baseUploads = storage_path('app/public/uploads');
        if (! File::isDirectory($baseUploads)) {
            File::makeDirectory($baseUploads, 0755, true, true);
        }

        $cleanFolder = $this->sanitizePath($this->currentFolder);
        $currentDir = $baseUploads . ($cleanFolder ? '/' . $cleanFolder : '');

        if (! File::isDirectory($currentDir)) {
            $currentDir = $baseUploads;
            $this->currentFolder = '';
        }

        // 1. Folders in current folder
        $folders = [];
        if (empty($this->search)) {
            $dirs = File::directories($currentDir);
            $folders = collect($dirs)->map(function ($dirPath) use ($baseUploads) {
                $dirName = basename($dirPath);
                $relPath = trim(str_replace($baseUploads, '', $dirPath), '/\\');
                $itemsCount = count(File::files($dirPath)) + count(File::directories($dirPath));
                $mtime = filemtime($dirPath);

                return [
                    'is_folder' => true,
                    'name' => $dirName,
                    'path' => str_replace('\\', '/', $relPath),
                    'items_count' => $itemsCount,
                    'mtime' => $mtime,
                    'date' => date('d/m/Y H:i', $mtime),
                    'size' => "{$itemsCount} mục",
                ];
            });

            // Sort folders by name asc
            $folders = $folders->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)->values();
        }

        // 2. Files in current folder (or recursive search if searching)
        if (! empty($this->search)) {
            $allFiles = File::allFiles($currentDir);
        } else {
            $allFiles = File::files($currentDir);
        }

        $files = collect($allFiles)->map(function (\SplFileInfo $file) {
            $rel = str_replace(storage_path('app/public') . '/', '', $file->getPathname());
            $rel = str_replace('\\', '/', $rel);
            $ext = strtolower($file->getExtension());
            $size = $file->getSize();
            $mtime = $file->getMTime();

            return [
                'is_folder' => false,
                'name' => $file->getFilename(),
                'relative_path' => $rel,
                'url' => '/storage/' . $rel,
                'size' => $this->formatFileSize($size),
                'size_bytes' => $size,
                'mtime' => $mtime,
                'date' => date('d/m/Y H:i', $mtime),
                'ext' => strtoupper($ext),
                'mime_type' => $ext,
                'is_image' => in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'bmp', 'ico', 'avif']),
                'is_video' => in_array($ext, ['mp4', 'mov', 'webm', 'ogg', 'avi', 'mkv']),
                'is_document' => in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv']),
            ];
        });

        // Filter by search
        if (! empty($this->search)) {
            $keyword = mb_strtolower($this->search);
            $files = $files->filter(function ($item) use ($keyword) {
                return str_contains(mb_strtolower($item['name']), $keyword);
            });
        }

        // Filter by type
        if ($this->filter === 'image') {
            $files = $files->filter(fn ($f) => $f['is_image']);
        } elseif ($this->filter === 'video') {
            $files = $files->filter(fn ($f) => $f['is_video']);
        } elseif ($this->filter === 'document') {
            $files = $files->filter(fn ($f) => $f['is_document']);
        }

        // Filter by viewIn (recent: last 7 days)
        if ($this->viewIn === 'recent') {
            $sevenDaysAgo = time() - (7 * 86400);
            $files = $files->filter(fn ($f) => $f['mtime'] >= $sevenDaysAgo);
        }

        // Sort files
        switch ($this->sortBy) {
            case 'name-asc':
                $files = $files->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
                break;
            case 'name-desc':
                $files = $files->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE);
                break;
            case 'created_at-asc':
                $files = $files->sortBy('mtime');
                break;
            case 'size-desc':
                $files = $files->sortByDesc('size_bytes');
                break;
            case 'size-asc':
                $files = $files->sortBy('size_bytes');
                break;
            case 'created_at-desc':
            default:
                $files = $files->sortByDesc('mtime');
                break;
        }

        $files = $files->values();

        // Paginate files
        $page = $this->getPage();
        $totalFiles = $files->count();
        $pagedFiles = $files->slice(($page - 1) * $this->perPage, $this->perPage)->values();

        $paginatedFiles = new LengthAwarePaginator(
            $pagedFiles,
            $totalFiles,
            $this->perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return [
            'folders' => $folders,
            'files' => $paginatedFiles,
            'totalFiles' => $totalFiles,
            'totalFolders' => count($folders),
        ];
    }

    public function getStorageQuota(): array
    {
        $baseUploads = storage_path('app/public/uploads');
        $totalBytes = 0;
        if (File::isDirectory($baseUploads)) {
            foreach (File::allFiles($baseUploads) as $file) {
                $totalBytes += $file->getSize();
            }
        }

        $freeBytes = @disk_free_space($baseUploads) ?: 10737418240; // 10GB default
        $totalDiskBytes = @disk_total_space($baseUploads) ?: 53687091200; // 50GB default
        $usedPercent = $totalDiskBytes > 0 ? min(100, round(($totalBytes / $totalDiskBytes) * 100, 1)) : 0;

        return [
            'used' => $this->formatFileSize($totalBytes),
            'percent' => max(1, $usedPercent),
            'free' => $this->formatFileSize($freeBytes),
        ];
    }

    protected function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }

    protected function getViewData(): array
    {
        $mediaData = $this->getMediaData();

        return [
            'folders' => $mediaData['folders'],
            'files' => $mediaData['files'],
            'totalFiles' => $mediaData['totalFiles'],
            'totalFolders' => $mediaData['totalFolders'],
            'folderBreadcrumbs' => $this->getFolderBreadcrumbs(),
            'storageQuota' => $this->getStorageQuota(),
        ];
    }
}
