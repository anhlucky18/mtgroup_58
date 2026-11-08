<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use UnitEnum;

class GeneralSettings extends Page
{
    protected string $view = 'filament.pages.general-settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|UnitEnum|null $navigationGroup = 'Hệ thống';

    protected static ?string $title = 'Cấu hình toàn trang';

    protected static ?string $navigationLabel = 'Cấu hình hệ thống';

    protected static ?int $navigationSort = 1;

    public array $settings = [];

    public string $activeTab = 'branding';

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }

    public function mount(): void
    {
        $existing = Setting::pluck('value', 'key')->toArray();

        $defaults = [
            'site_title' => 'Minh Thành Group',
            'site_slogan' => 'Cung cấp giải pháp quan trắc môi trường tự động, liên tục',
            'seo_title' => 'Cung cấp giải pháp quan trắc môi trường tự động',
            'seo_description' => 'Minh Thành Group chuyên cung cấp giải pháp và thiết bị quan trắc môi trường tự động uy tín hàng đầu.',
            'theme-ripple-copyright' => 'Minh Thành Group',
            'theme-ripple-logo' => '/storage/uploads/gst-logo-01.png',
            'logo' => '/storage/uploads/gst-logo-01.png',
            'primary_font' => 'Roboto',
            'theme-ripple-primary_font' => 'Roboto',
            'favicon' => '/storage/uploads/gst-logo-01.png',
            'hotline_main' => '0963189981',
            'admin_email' => 'quantrac.mtg@gmail.com',
            'tax_code' => '2301052220',
            'office_bac_ninh' => 'Số 32 Nguyễn Khuyến, Phường Võ Cường, TP. Bắc Ninh',
            'office_ha_noi' => 'Tòa nhà Lạc Hồng, Ngõ 27 Lê Văn Lương, Nhân Chính, Thanh Xuân, Hà Nội',
            'office_thanh_hoa' => 'Số 46 Đặng Văn Hỷ, An Hưng, TP. Thanh Hóa',
            'office_hcm' => 'Số 20 Đường 1 KDC CityLand Park Hills, Gò Vấp, TP. HCM',
            'gtm_id' => 'GTM-MHFCDPPW',
            'fb_page_id' => '100367992250913',
            'custom_header_scripts' => '',
            'custom_footer_scripts' => '',
        ];

        $this->settings = array_merge($defaults, $existing);
    }

    public function save(): void
    {
        // Keep logo and font aliases in sync
        if (isset($this->settings['theme-ripple-logo'])) {
            $this->settings['logo'] = $this->settings['theme-ripple-logo'];
        }
        if (isset($this->settings['primary_font'])) {
            $this->settings['theme-ripple-primary_font'] = $this->settings['primary_font'];
        }

        foreach ($this->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        Notification::make()
            ->title('Cập nhật cấu hình website thành công!')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        return [
            'fonts' => ThemeOptions::getGoogleFonts(),
        ];
    }
}
