<?php

namespace Tests\Feature;

use App\Filament\Pages\GeneralSettings;
use App\Filament\Pages\ThemeOptions;
use App\Filament\Resources\ContentBlocks\ContentBlockResource;
use App\Filament\Resources\Menus\MenuResource;
use App\Filament\Resources\Users\UserResource;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Slug;
use App\Models\Tag;
use App\Models\User;
use App\Rules\UniqueCrossSlug;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FilamentAdminCmsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
    }

    public function test_has_slug_trait_syncs_to_slugs_table_on_create_update_delete(): void
    {
        // 1. Create Post
        $post = Post::create([
            'name' => 'Bài viết thử nghiệm',
            'slug' => 'bai-viet-thu-nghiem',
            'locale' => 'vi',
            'status' => 'publish',
        ]);

        $this->assertDatabaseHas('slugs', [
            'key' => 'bai-viet-thu-nghiem',
            'reference_id' => $post->id,
            'reference_type' => Post::class,
            'locale' => 'vi',
        ]);

        // 2. Update Post slug
        $post->update(['slug' => 'bai-viet-cap-nhat']);

        $this->assertDatabaseHas('slugs', [
            'key' => 'bai-viet-cap-nhat',
            'reference_id' => $post->id,
            'reference_type' => Post::class,
        ]);
        $this->assertDatabaseMissing('slugs', [
            'key' => 'bai-viet-thu-nghiem',
        ]);

        // 3. Delete Post
        $postId = $post->id;
        $post->delete();

        $this->assertDatabaseMissing('slugs', [
            'reference_id' => $postId,
            'reference_type' => Post::class,
        ]);
    }

    public function test_has_slug_trait_works_for_categories_and_pages(): void
    {
        $category = Category::create([
            'name' => 'Chuyên mục thiết bị',
            'slug' => 'chuyen-muc-thiet-bi',
            'locale' => 'vi',
        ]);

        $this->assertDatabaseHas('slugs', [
            'key' => 'chuyen-muc-thiet-bi',
            'reference_id' => $category->id,
            'reference_type' => Category::class,
        ]);

        $page = Page::create([
            'name' => 'Giới thiệu công ty',
            'slug' => 'gioi-thieu-cong-ty',
            'locale' => 'vi',
        ]);

        $this->assertDatabaseHas('slugs', [
            'key' => 'gioi-thieu-cong-ty',
            'reference_id' => $page->id,
            'reference_type' => Page::class,
        ]);
    }

    public function test_unique_cross_slug_validation_rule(): void
    {
        // Create an existing category with slug 'tin-tuc-su-kien'
        $category = Category::create([
            'name' => 'Tin tức sự kiện',
            'slug' => 'tin-tuc-su-kien',
            'locale' => 'vi',
        ]);

        // Test 1: New post tries to use the same slug -> must fail
        $validator1 = Validator::make(
            ['slug' => 'tin-tuc-su-kien'],
            ['slug' => [new UniqueCrossSlug(null)]]
        );
        $this->assertTrue($validator1->fails());

        // Test 2: New post uses an available slug -> must pass
        $validator2 = Validator::make(
            ['slug' => 'slug-hoan-toan-moi'],
            ['slug' => [new UniqueCrossSlug(null)]]
        );
        $this->assertTrue($validator2->passes());

        // Test 3: The existing category updates its own record with the same slug -> must pass
        $validator3 = Validator::make(
            ['slug' => 'tin-tuc-su-kien'],
            ['slug' => [new UniqueCrossSlug($category)]]
        );
        $this->assertTrue($validator3->passes());
    }

    public function test_user_can_access_panel_based_on_role(): void
    {
        $adminPanel = Filament::getPanel('admin');

        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $regularUser = User::factory()->create();

        $this->assertTrue($superAdmin->canAccessPanel($adminPanel));
        $this->assertTrue($editor->canAccessPanel($adminPanel));
        $this->assertFalse($regularUser->canAccessPanel($adminPanel));
    }

    public function test_super_admin_has_exclusive_access_to_system_resources(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $this->actingAs($superAdmin);
        $this->assertTrue(UserResource::canAccess());
        $this->assertTrue(ContentBlockResource::canAccess());
        $this->assertTrue(MenuResource::canAccess());
        $this->assertTrue(GeneralSettings::canAccess());
        $this->assertTrue(ThemeOptions::canAccess());

        $this->actingAs($editor);
        $this->assertFalse(UserResource::canAccess());
        $this->assertFalse(ContentBlockResource::canAccess());
        $this->assertFalse(MenuResource::canAccess());
        $this->assertFalse(GeneralSettings::canAccess());
        $this->assertFalse(ThemeOptions::canAccess());
    }

    public function test_general_settings_persistence(): void
    {
        Setting::updateOrCreate(['key' => 'site_title'], ['value' => 'Minh Thành Group Test']);
        Setting::updateOrCreate(['key' => 'hotline_main'], ['value' => '0963189981']);

        $this->assertEquals('Minh Thành Group Test', Setting::where('key', 'site_title')->value('value'));
        $this->assertEquals('0963189981', Setting::where('key', 'hotline_main')->value('value'));
    }

    public function test_theme_options_persistence_and_sync(): void
    {
        Setting::updateOrCreate(['key' => 'theme-ripple-logo'], ['value' => '/storage/uploads/new-logo.png']);
        Setting::updateOrCreate(['key' => 'primary_font'], ['value' => 'Montserrat']);

        $this->assertEquals('/storage/uploads/new-logo.png', theme_option('logo'));
        $this->assertEquals('Montserrat', theme_option('primary_font'));
    }
}
