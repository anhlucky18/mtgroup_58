<?php

namespace App\Filament\Resources\Menus\RelationManagers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Danh sách các mục liên kết (Menu Items)';

    public function form(Schema $schema): Schema
    {
        $ownerMenu = $this->getOwnerRecord();

        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề mục menu')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),

                        Select::make('parent_id')
                            ->label('Mục cha (Cấp trên)')
                            ->options(function (?MenuItem $record) use ($ownerMenu) {
                                return MenuItem::where('menu_id', $ownerMenu->id)
                                    ->when($record?->id, fn ($q) => $q->where('id', '!=', $record->id))
                                    ->pluck('title', 'id');
                            })
                            ->searchable()
                            ->placeholder('— Cấp gốc (Root level) —')
                            ->columnSpan(1),

                        TextInput::make('url')
                            ->label('Đường dẫn (URL)')
                            ->placeholder('/gioi-thieu, /dich-vu hoặc #')
                            ->helperText('Nhập đường dẫn trực tiếp hoặc chọn nhanh từ danh sách bên cạnh.')
                            ->maxLength(500)
                            ->columnSpan(1),

                        Select::make('quick_link')
                            ->label('Chọn nhanh từ Chuyên mục / Trang')
                            ->dehydrated(false)
                            ->options(function () {
                                $pages = Page::pluck('name', 'slug')->mapWithKeys(fn ($name, $slug) => ['/' . $slug => '[Trang] ' . $name]);
                                $categories = Category::pluck('name', 'slug')->mapWithKeys(fn ($name, $slug) => ['/' . $slug => '[Chuyên mục] ' . $name]);

                                return [
                                    'Trang tĩnh' => $pages->toArray(),
                                    'Chuyên mục' => $categories->toArray(),
                                ];
                            })
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('url', $state);
                                }
                            })
                            ->columnSpan(1),

                        TextInput::make('order')
                            ->label('Thứ tự hiển thị')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->columnSpan(1),

                        Select::make('target')
                            ->label('Kiểu mở tab')
                            ->options([
                                '_self' => 'Trong cùng tab (_self)',
                                '_blank' => 'Mở trong tab mới (_blank)',
                            ])
                            ->default('_self')
                            ->required()
                            ->columnSpan(1),

                        TextInput::make('icon_font')
                            ->label('Font Icon Class')
                            ->placeholder('fa fa-home, ti-layers, fi flaticon-worker')
                            ->maxLength(100)
                            ->columnSpan(1),

                        TextInput::make('css_class')
                            ->label('CSS Class tùy biến')
                            ->placeholder('menu-item-highlight, no-dropdown')
                            ->maxLength(120)
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('order')
            ->columns([
                TextColumn::make('order')
                    ->label('Thứ tự')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('parent.title')
                    ->label('Mục cha')
                    ->badge()
                    ->color('info')
                    ->placeholder('— Cấp gốc —'),
                TextColumn::make('url')
                    ->label('URL')
                    ->color('primary')
                    ->searchable(),
                TextColumn::make('target')
                    ->label('Target')
                    ->badge(),
                TextColumn::make('icon_font')
                    ->label('Icon')
                    ->placeholder('—'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Thêm mục menu'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
