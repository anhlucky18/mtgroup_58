<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestPostsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Bài viết mới nhất & Xem nhiều nhất')
            ->query(Post::query()->latest('created_at'))
            ->paginated([5, 10])
            ->defaultPaginationPageOption(5)
            ->columns([
                ImageColumn::make('image')
                    ->label('Ảnh')
                    ->disk('public')
                    ->state(fn ($record) => $record->image ? preg_replace('#^/?storage/#', '', $record->image) : null)
                    ->square()
                    ->imageSize(40),
                TextColumn::make('name')
                    ->label('Tiêu đề')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('categories.name')
                    ->label('Chuyên mục')
                    ->badge()
                    ->color('info')
                    ->limitList(2),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('views')
                    ->label('Lượt xem')
                    ->sortable()
                    ->badge()
                    ->color('warning'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'publish' => 'success',
                        'draft' => 'gray',
                        default => 'warning',
                    }),
                TextColumn::make('published_at')
                    ->label('Ngày xuất bản')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}

