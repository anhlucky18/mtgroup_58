<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Page;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unreadContacts = Contact::where('status', 'unread')->count();

        return [
            Stat::make('Tổng số bài viết', Post::count())
                ->description('Tin tức, bài viết & dự án')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),
            Stat::make('Tổng số chuyên mục', Category::count())
                ->description('Phân loại nội dung')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
            Stat::make('Tổng số trang tĩnh', Page::count())
                ->description('Trang giới thiệu, sản phẩm...')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
            Stat::make('Liên hệ chưa đọc', $unreadContacts)
                ->description($unreadContacts > 0 ? 'Cần xử lý phản hồi ngay' : 'Đã xử lý toàn bộ liên hệ')
                ->descriptionIcon($unreadContacts > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($unreadContacts > 0 ? 'danger' : 'success'),
        ];
    }
}

