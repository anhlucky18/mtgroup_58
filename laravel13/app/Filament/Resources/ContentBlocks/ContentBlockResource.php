<?php

namespace App\Filament\Resources\ContentBlocks;

use App\Filament\Resources\ContentBlocks\Pages\CreateContentBlock;
use App\Filament\Resources\ContentBlocks\Pages\EditContentBlock;
use App\Filament\Resources\ContentBlocks\Pages\ListContentBlocks;
use App\Models\ContentBlock;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class ContentBlockResource extends Resource
{
    protected static ?string $model = ContentBlock::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static string|UnitEnum|null $navigationGroup = 'Điều hướng & Khối tĩnh';

    protected static ?string $modelLabel = 'Khối tĩnh';

    protected static ?string $pluralModelLabel = 'Khối tĩnh (Content Blocks)';

    protected static ?int $navigationSort = 2;

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
                                Section::make('Thông tin khối nội dung')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tên khối gợi nhớ')
                                            ->placeholder('Ví dụ: Banner Trang Chủ, Khối Đối Tác, Về Chúng Tôi')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('content')
                                            ->label('Mã HTML / Markup giao diện')
                                            ->helperText('Bảo toàn 100% các thẻ SVG, Bootstrap grid, hiệu ứng counter odometer, slider.')
                                            ->rows(22)
                                            ->extraAttributes(['class' => 'font-mono text-xs leading-relaxed'])
                                            ->required(),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Định danh & Trạng thái')
                                    ->schema([
                                        TextInput::make('alias')
                                            ->label('Khóa định danh (Alias)')
                                            ->helperText('Alias dùng để gọi trong theme Blade frontend: shortcode hoặc ContentBlock::byAlias(). Không thay đổi khi đang hoạt động.')
                                            ->required()
                                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                                            ->dehydrated()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(191),
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
                TextColumn::make('alias')
                    ->label('Alias')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Đã sao chép Alias!'),
                TextColumn::make('name')
                    ->label('Tên khối')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('locale')
                    ->label('Ngôn ngữ')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vi' => 'success',
                        'en_US' => 'primary',
                        default => 'gray',
                    }),
                ToggleColumn::make('status')
                    ->label('Kích hoạt')
                    ->state(fn ($record) => $record->status === 'publish')
                    ->updateStateUsing(fn ($record, $state) => $record->update(['status' => $state ? 'publish' : 'draft'])),
                TextColumn::make('updated_at')
                    ->label('Cập nhật lúc')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('locale')
                    ->label('Ngôn ngữ')
                    ->options([
                        'vi' => 'Tiếng Việt',
                        'en_US' => 'English',
                    ]),
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

    public static function getPages(): array
    {
        return [
            'index' => ListContentBlocks::route('/'),
            'create' => CreateContentBlock::route('/create'),
            'edit' => EditContentBlock::route('/{record}/edit'),
        ];
    }
}
