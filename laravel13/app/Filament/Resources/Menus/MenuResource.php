<?php

namespace App\Filament\Resources\Menus;

use App\Filament\Resources\Menus\Pages\CreateMenu;
use App\Filament\Resources\Menus\Pages\EditMenu;
use App\Filament\Resources\Menus\Pages\ListMenus;
use App\Filament\Resources\Menus\RelationManagers\ItemsRelationManager;
use App\Models\Menu;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bars-3';

    protected static string|UnitEnum|null $navigationGroup = 'Điều hướng & Khối tĩnh';

    protected static ?string $modelLabel = 'Menu';

    protected static ?string $pluralModelLabel = 'Menu điều hướng';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
                                Section::make('Thông tin cơ bản Menu')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên hiển thị Menu')
                                            ->placeholder('Ví dụ: Menu chính Tiếng Việt')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                                if ($operation === 'create') {
                                                    $set('slug', Str::slug($state));
                                                }
                                            }),
                                        TextInput::make('slug')
                                            ->label('Định danh Slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(120),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Vị trí & Ngôn ngữ')
                                    ->schema([
                                        Select::make('location')
                                            ->label('Vị trí hiển thị trên Theme')
                                            ->options([
                                                'main-menu' => 'Menu chính (main-menu - Tiếng Việt)',
                                                'main-menu-en' => 'Menu chính tiếng Anh (main-menu-en)',
                                                'product-menu' => 'Menu danh mục sản phẩm (product-menu)',
                                                'project-menu' => 'Menu dự án (project-menu)',
                                                'product-menu-1' => 'Menu phụ sản phẩm 1 (product-menu-1)',
                                                'product-en' => 'Menu sản phẩm tiếng Anh (product-en)',
                                            ])
                                            ->searchable()
                                            ->placeholder('Chọn vị trí hiển thị'),
                                        Select::make('locale')
                                            ->label('Ngôn ngữ')
                                            ->options([
                                                'vi' => 'Tiếng Việt',
                                                'en_US' => 'English',
                                            ])
                                            ->default('vi')
                                            ->required(),
                                        Select::make('status')
                                            ->label('Trạng thái')
                                            ->options([
                                                'publish' => 'Xuất bản',
                                                'draft' => 'Bản nháp',
                                            ])
                                            ->default('publish')
                                            ->required(),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên Menu')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('location')
                    ->label('Vị trí hiển thị')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—'),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('items_count')
                    ->label('Số mục con')
                    ->counts('items')
                    ->badge()
                    ->color('info')
                    ->sortable(),
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

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
