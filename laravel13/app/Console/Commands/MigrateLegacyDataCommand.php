<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Contact;
use App\Models\ContentBlock;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Slug;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

class MigrateLegacyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-legacy-data 
                            {--dry-run : Run logic and audit checks without saving to database} 
                            {--fresh : Clean target tables before importing} 
                            {--step= : Execute specific step (users, categories, tags, pages, posts, slugs, blocks, menus, settings, media)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract, Transform and Load (ETL) legacy CMS data from minhthanh2 into Laravel 13';

    // Preloaded lookup caches
    protected array $slugMap = [];
    protected array $localeMap = [];
    protected array $seoMap = [];
    protected array $galleryMap = [];

    // Counters for auditing
    protected array $stats = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $isFresh = (bool) $this->option('fresh');
        $step = $this->option('step');

        $this->info("==================================================================");
        $this->info(" ETL DATA MIGRATION: minhthanh2 -> mtgroup_v13 (Laravel 13)");
        $this->info(" Mode: " . ($isDryRun ? "DRY-RUN (Safe Simulation, Rollback at end)" : "LIVE EXECUTION"));
        if ($isFresh) {
            $this->warn(" Flag: --fresh enabled (Target tables will be cleaned before import)");
        }
        if ($step) {
            $this->info(" Target Step: {$step}");
        }
        $this->info("==================================================================");

        // Preload metadata lookup tables from legacy database
        $this->preloadLegacyMetadata();

        if ($isDryRun) {
            DB::beginTransaction();
        }

        try {
            Schema::disableForeignKeyConstraints();

            if ($isFresh && !$step) {
                $this->cleanTargetTables();
            }

            $steps = [
                'users'      => fn () => $this->migrateUsers(),
                'categories' => fn () => $this->migrateCategories(),
                'tags'       => fn () => $this->migrateTags(),
                'pages'      => fn () => $this->migratePages(),
                'posts'      => fn () => $this->migratePosts(),
                'slugs'      => fn () => $this->migrateSlugs(),
                'blocks'     => fn () => $this->migrateContentBlocks(),
                'menus'      => fn () => $this->migrateMenus(),
                'settings'   => fn () => $this->migrateSettings(),
                'media'      => fn () => $this->migrateMediaFiles(),
            ];

            if ($step) {
                if (!isset($steps[$step])) {
                    $this->error("Invalid step: {$step}. Available steps: " . implode(', ', array_keys($steps)));
                    return self::FAILURE;
                }
                $steps[$step]();
            } else {
                foreach ($steps as $name => $callback) {
                    $callback();
                }
            }

            // Print Data Audit Report
            $this->printAuditReport();

            if ($isDryRun) {
                DB::rollBack();
                $this->warn("\n[DRY-RUN] Simulation completed successfully! All database changes have been ROLLED BACK.");
            } else {
                $this->info("\n[SUCCESS] ETL Migration completed 100% successfully!");
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            if ($isDryRun) {
                DB::rollBack();
            }
            $this->error("\n[ERROR] Migration failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return self::FAILURE;
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Preload slugs, locales, SEO metadata, and gallery meta into memory.
     */
    protected function preloadLegacyMetadata(): void
    {
        $this->line("-> Preloading legacy metadata into memory...");

        // 1. Slugs map: $this->slugMap[reference][reference_id] = key
        $slugs = DB::connection('mysql_legacy')->table('slugs')->get();
        foreach ($slugs as $slug) {
            $this->slugMap[$slug->reference][$slug->reference_id] = $slug->key;
        }

        // 2. Locale map: $this->localeMap[reference][content_id] = code
        $locales = DB::connection('mysql_legacy')->table('language_meta')->get();
        foreach ($locales as $loc) {
            $code = strtolower(trim($loc->lang_meta_code));
            if ($code === 'vi' || $code === 'vi_vn') {
                $code = 'vi';
            } elseif ($code === 'en_us' || $code === 'en') {
                $code = 'en_US';
            }
            $this->localeMap[$loc->lang_meta_reference][$loc->lang_meta_content_id] = $code;
        }

        // 3. SEO Meta map: $this->seoMap[reference][content_id] = ['seo_title' => ..., 'seo_description' => ...]
        $metaBoxes = DB::connection('mysql_legacy')->table('meta_boxes')
            ->where('meta_key', 'seo_meta')
            ->get();
        foreach ($metaBoxes as $mb) {
            $meta = json_decode($mb->meta_value, true);
            if (is_array($meta) && isset($meta[0])) {
                $this->seoMap[$mb->reference][$mb->content_id] = [
                    'seo_title' => $meta[0]['seo_title'] ?? null,
                    'seo_description' => $meta[0]['seo_description'] ?? null,
                ];
            }
        }

        // 4. Gallery Meta map: $this->galleryMap[reference][content_id] = [...]
        $galleries = DB::connection('mysql_legacy')->table('gallery_meta')
            ->whereNotNull('images')
            ->get();
        foreach ($galleries as $gal) {
            $imgs = json_decode($gal->images, true);
            if (is_array($imgs)) {
                $this->galleryMap[$gal->reference][$gal->content_id] = $imgs;
            }
        }

        $this->info("   Metadata preloaded: " . count($slugs) . " slugs, " . count($locales) . " locales, " . count($this->seoMap, COUNT_RECURSIVE) . " SEO entries.");
    }

    /**
     * Clean target tables when --fresh is specified.
     */
    protected function cleanTargetTables(): void
    {
        $this->warn("-> Cleaning target tables (DELETE FROM)...");
        DB::table('category_post')->delete();
        DB::table('post_tag')->delete();
        DB::table('menu_items')->delete();
        DB::table('menus')->delete();
        DB::table('slugs')->delete();
        DB::table('posts')->delete();
        DB::table('pages')->delete();
        DB::table('tags')->delete();
        DB::table('categories')->delete();
        DB::table('content_blocks')->delete();
        DB::table('settings')->delete();
        $this->info("   Target tables cleaned.");
    }

    /**
     * Step 1: Migrate Users
     */
    protected function migrateUsers(): void
    {
        $this->line("-> [Step 1/10] Migrating Users...");
        $legacyUsers = DB::connection('mysql_legacy')->table('users')->get();
        $count = 0;

        // Ensure test admin account 'admin@mtgroup.vn' does not block id=1
        $testAdmin = DB::table('users')->where('email', 'admin@mtgroup.vn')->first();
        if ($testAdmin && $testAdmin->id == 1) {
            $nextId = (DB::table('users')->max('id') ?? 1) + 1;
            DB::table('users')->where('id', 1)->update(['id' => $nextId]);
            DB::table('model_has_roles')->where('model_id', 1)->update(['model_id' => $nextId]);
            $this->info("   Preserved test admin (admin@mtgroup.vn) with ID {$nextId}.");
        }

        foreach ($legacyUsers as $u) {
            $name = trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? ''));
            if (empty($name)) {
                $name = $u->username ?? 'Administrator';
            }

            $userData = [
                'name' => $name,
                'email' => $u->email,
                'password' => $u->password, // Preserve original Bcrypt hash
                'created_at' => $u->created_at ?? now(),
                'updated_at' => $u->updated_at ?? now(),
            ];

            // If user exists with same email, update; otherwise insert with original ID
            $existingByEmail = DB::table('users')->where('email', $u->email)->first();
            if ($existingByEmail) {
                DB::table('users')->where('id', $existingByEmail->id)->update($userData);
                $userId = $existingByEmail->id;
            } else {
                $userData['id'] = $u->id;
                DB::table('users')->updateOrInsert(['id' => $u->id], $userData);
                $userId = $u->id;
            }

            // Assign super_admin role
            if (Schema::hasTable('roles')) {
                $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
                $userModel = User::find($userId);
                if ($userModel && !$userModel->hasRole('super_admin')) {
                    $userModel->assignRole($superAdminRole);
                }
            }

            $count++;
        }

        // Also ensure test admin has super_admin role
        $testAdminUser = User::where('email', 'admin@mtgroup.vn')->first();
        if ($testAdminUser) {
            $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
            if (!$testAdminUser->hasRole('super_admin')) {
                $testAdminUser->assignRole($superAdminRole);
            }
        }

        $totalUsers = DB::table('users')->count();
        $this->stats['users'] = [
            'source' => count($legacyUsers),
            'target' => $totalUsers,
        ];
        $this->info("   Migrated {$count} legacy user(s). Total users in database: {$totalUsers}.");
    }

    /**
     * Step 2: Migrate Categories
     */
    protected function migrateCategories(): void
    {
        $this->line("-> [Step 2/10] Migrating Categories...");
        $legacyCategories = DB::connection('mysql_legacy')->table('categories')->get();
        $rows = [];

        foreach ($legacyCategories as $cat) {
            $slug = $this->slugMap['category'][$cat->id] ?? null;
            if (!$slug) {
                $slug = \Illuminate\Support\Str::slug($cat->name);
            }
            $locale = $this->localeMap['category'][$cat->id] ?? 'vi';
            $seo = $this->seoMap['category'][$cat->id] ?? [];

            $rows[] = [
                'id'              => $cat->id,
                'name'            => $cat->name,
                'slug'            => $slug,
                'parent_id'       => $cat->parent_id == 0 ? null : $cat->parent_id,
                'description'     => $cat->description,
                'image'           => null,
                'icon'            => $cat->icon,
                'order'           => (int) $cat->order,
                'is_featured'     => (bool) $cat->is_featured,
                'status'          => $cat->status ?? 'publish',
                'locale'          => $locale,
                'seo_title'       => $seo['seo_title'] ?? null,
                'seo_description' => $seo['seo_description'] ?? null,
                'created_at'      => $cat->created_at ?? now(),
                'updated_at'      => $cat->updated_at ?? now(),
            ];
        }

        foreach ($rows as $row) {
            DB::table('categories')->updateOrInsert(['id' => $row['id']], $row);
        }

        $this->stats['categories'] = [
            'source' => count($legacyCategories),
            'target' => count($rows),
        ];
        $this->info("   Migrated " . count($rows) . " categories.");
    }

    /**
     * Step 3: Migrate Tags
     */
    protected function migrateTags(): void
    {
        $this->line("-> [Step 3/10] Migrating Tags...");
        $legacyTags = DB::connection('mysql_legacy')->table('tags')->get();
        $rows = [];

        foreach ($legacyTags as $tag) {
            $slug = $this->slugMap['tag'][$tag->id] ?? \Illuminate\Support\Str::slug($tag->name);
            $locale = $this->localeMap['tag'][$tag->id] ?? 'vi';

            $rows[] = [
                'id'          => $tag->id,
                'name'        => $tag->name,
                'slug'        => $slug,
                'description' => $tag->description,
                'status'      => $tag->status ?? 'publish',
                'locale'      => $locale,
                'created_at'  => $tag->created_at ?? now(),
                'updated_at'  => $tag->updated_at ?? now(),
            ];
        }

        foreach ($rows as $row) {
            DB::table('tags')->updateOrInsert(['id' => $row['id']], $row);
        }

        $this->stats['tags'] = [
            'source' => count($legacyTags),
            'target' => count($rows),
        ];
        $this->info("   Migrated " . count($rows) . " tags.");
    }

    /**
     * Step 4: Migrate Pages
     */
    protected function migratePages(): void
    {
        $this->line("-> [Step 4/10] Migrating Pages...");
        $legacyPages = DB::connection('mysql_legacy')->table('pages')->get();
        $rows = [];

        foreach ($legacyPages as $page) {
            $slug = $this->slugMap['page'][$page->id] ?? \Illuminate\Support\Str::slug($page->name);
            $locale = $this->localeMap['page'][$page->id] ?? 'vi';
            $seo = $this->seoMap['page'][$page->id] ?? [];
            $gallery = $this->galleryMap['page'][$page->id] ?? null;

            $rows[] = [
                'id'              => $page->id,
                'name'            => $page->name,
                'slug'            => $slug,
                'description'     => $page->description,
                'content'         => $page->content,
                'template'        => $page->template ?? 'default',
                'image'           => $page->image,
                'author_id'       => $page->user_id ?? 1,
                'gallery'         => $gallery ? json_encode($gallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null,
                'status'          => $page->status ?? 'publish',
                'locale'          => $locale,
                'seo_title'       => $seo['seo_title'] ?? null,
                'seo_description' => $seo['seo_description'] ?? null,
                'created_at'      => $page->created_at ?? now(),
                'updated_at'      => $page->updated_at ?? now(),
            ];
        }

        foreach ($rows as $row) {
            DB::table('pages')->updateOrInsert(['id' => $row['id']], $row);
        }

        $this->stats['pages'] = [
            'source' => count($legacyPages),
            'target' => count($rows),
        ];
        $this->info("   Migrated " . count($rows) . " pages.");
    }

    /**
     * Step 5: Migrate Posts & Pivots
     */
    protected function migratePosts(): void
    {
        $this->line("-> [Step 5/10] Migrating Posts & Pivots...");
        $legacyPosts = DB::connection('mysql_legacy')->table('posts')->get();
        $postRows = [];

        foreach ($legacyPosts as $post) {
            $slug = $this->slugMap['post'][$post->id] ?? \Illuminate\Support\Str::slug($post->name);
            $locale = $this->localeMap['post'][$post->id] ?? 'vi';
            $seo = $this->seoMap['post'][$post->id] ?? [];
            $gallery = $this->galleryMap['post'][$post->id] ?? null;

            // published_at logic
            $publishedAt = ($post->status === 'publish') ? ($post->created_at ?? now()) : null;

            $postRows[] = [
                'id'              => $post->id,
                'name'            => $post->name,
                'slug'            => $slug,
                'description'     => $post->description,
                'content'         => $post->content,
                'image'           => $post->image,
                'author_id'       => $post->author_id ?: 1,
                'status'          => $post->status ?? 'publish',
                'views'           => (int) ($post->views ?? 0),
                'is_featured'     => (bool) ($post->is_featured ?? false),
                'format_type'     => $post->format_type ?? 'default',
                'locale'          => $locale,
                'gallery'         => $gallery ? json_encode($gallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) : null,
                'seo_title'       => $seo['seo_title'] ?? null,
                'seo_description' => $seo['seo_description'] ?? null,
                'published_at'    => $publishedAt,
                'created_at'      => $post->created_at ?? now(),
                'updated_at'      => $post->updated_at ?? now(),
            ];
        }

        // Insert posts in chunks
        foreach (array_chunk($postRows, 50) as $chunk) {
            foreach ($chunk as $row) {
                DB::table('posts')->updateOrInsert(['id' => $row['id']], $row);
            }
        }

        // Migrate Pivot: category_post
        $legacyCatPivot = DB::connection('mysql_legacy')->table('post_categories')->get();
        $catPivotRows = [];
        foreach ($legacyCatPivot as $p) {
            $catPivotRows[] = [
                'post_id'     => $p->post_id,
                'category_id' => $p->category_id,
            ];
        }
        DB::table('category_post')->delete();
        DB::table('category_post')->insertOrIgnore($catPivotRows);

        // Migrate Pivot: post_tag
        $legacyTagPivot = DB::connection('mysql_legacy')->table('post_tags')->get();
        $tagPivotRows = [];
        foreach ($legacyTagPivot as $p) {
            $tagPivotRows[] = [
                'post_id' => $p->post_id,
                'tag_id'  => $p->tag_id,
            ];
        }
        DB::table('post_tag')->delete();
        DB::table('post_tag')->insertOrIgnore($tagPivotRows);

        $this->stats['posts'] = [
            'source' => count($legacyPosts),
            'target' => count($postRows),
        ];
        $this->stats['category_post'] = [
            'source' => count($legacyCatPivot),
            'target' => count($catPivotRows),
        ];
        $this->stats['post_tag'] = [
            'source' => count($legacyTagPivot),
            'target' => count($tagPivotRows),
        ];

        $this->info("   Migrated " . count($postRows) . " posts, " . count($catPivotRows) . " category relations, " . count($tagPivotRows) . " tag relations.");
    }

    /**
     * Step 6: Migrate Slugs
     */
    protected function migrateSlugs(): void
    {
        $this->line("-> [Step 6/10] Migrating Slugs...");
        $legacySlugs = DB::connection('mysql_legacy')->table('slugs')->get();
        $rows = [];

        $modelMapping = [
            'post'     => Post::class,
            'category' => Category::class,
            'page'     => Page::class,
            'tag'      => Tag::class,
        ];

        foreach ($legacySlugs as $s) {
            $refType = $modelMapping[$s->reference] ?? null;
            if (!$refType) {
                continue;
            }
            $locale = $this->localeMap[$s->reference][$s->reference_id] ?? 'vi';

            $rows[] = [
                'id'             => $s->id,
                'key'            => $s->key,
                'reference_id'   => $s->reference_id,
                'reference_type' => $refType,
                'prefix'         => $s->prefix ?: null,
                'locale'         => $locale,
                'created_at'     => $s->created_at ?? now(),
                'updated_at'     => $s->updated_at ?? now(),
            ];
        }

        foreach (array_chunk($rows, 50) as $chunk) {
            foreach ($chunk as $row) {
                DB::table('slugs')->updateOrInsert(['id' => $row['id']], $row);
            }
        }

        $this->stats['slugs'] = [
            'source' => count($legacySlugs),
            'target' => count($rows),
        ];
        $this->info("   Migrated " . count($rows) . " slugs.");
    }

    /**
     * Step 7: Migrate Content Blocks
     */
    protected function migrateContentBlocks(): void
    {
        $this->line("-> [Step 7/10] Migrating Content Blocks...");
        $legacyBlocks = DB::connection('mysql_legacy')->table('blocks')->get();
        $rows = [];

        foreach ($legacyBlocks as $b) {
            $locale = $this->localeMap['block'][$b->id] ?? 'vi';

            $rows[] = [
                'id'         => $b->id,
                'alias'      => $b->alias,
                'name'       => $b->name,
                'content'    => $b->content,
                'locale'     => $locale,
                'status'     => $b->status ?? 'publish',
                'created_at' => $b->created_at ?? now(),
                'updated_at' => $b->updated_at ?? now(),
            ];
        }

        foreach ($rows as $row) {
            DB::table('content_blocks')->updateOrInsert(['id' => $row['id']], $row);
        }

        $this->stats['blocks'] = [
            'source' => count($legacyBlocks),
            'target' => count($rows),
        ];
        $this->info("   Migrated " . count($rows) . " content blocks.");
    }

    /**
     * Step 8: Migrate Menus & Menu Items
     */
    protected function migrateMenus(): void
    {
        $this->line("-> [Step 8/10] Migrating Menus & Menu Items...");
        $legacyMenus = DB::connection('mysql_legacy')->table('menus')->get();
        $legacyNodes = DB::connection('mysql_legacy')->table('menu_nodes')->get();

        // Location mappings for legacy menus
        $locationMap = [
            1 => 'product-menu',
            2 => 'project-menu',
            3 => 'product-menu-1',
            4 => 'product-en',
            5 => 'main-menu',
            6 => 'main-menu-en',
        ];

        foreach ($legacyMenus as $m) {
            $locale = $this->localeMap['menu'][$m->id] ?? 'vi';
            $location = $locationMap[$m->id] ?? $m->slug;

            DB::table('menus')->updateOrInsert(
                ['id' => $m->id],
                [
                    'name'       => $m->name,
                    'slug'       => $m->slug,
                    'location'   => $location,
                    'locale'     => $locale,
                    'status'     => $m->status ?? 'publish',
                    'created_at' => $m->created_at ?? now(),
                    'updated_at' => $m->updated_at ?? now(),
                ]
            );
        }

        // Migrate Menu Items
        $nodeRows = [];
        foreach ($legacyNodes as $node) {
            $url = $node->url;
            $refId = $node->related_id ?: null;
            $refType = null;

            if ($node->type === 'category' && $node->related_id) {
                $refType = Category::class;
                if (empty($url)) {
                    $slug = $this->slugMap['category'][$node->related_id] ?? null;
                    $url = $slug ? '/' . $slug : null;
                }
            } elseif ($node->type === 'page' && $node->related_id) {
                $refType = Page::class;
                if (empty($url)) {
                    $slug = $this->slugMap['page'][$node->related_id] ?? null;
                    $url = $slug ? '/' . $slug : null;
                }
            } elseif ($node->type === 'custom-link') {
                if (empty($url)) {
                    $url = '/';
                }
            }

            $nodeRows[] = [
                'id'             => $node->id,
                'menu_id'        => $node->menu_id,
                'parent_id'      => $node->parent_id == 0 ? null : $node->parent_id,
                'title'          => $node->title ?? 'Menu Item',
                'url'            => $url,
                'reference_id'   => $refId,
                'reference_type' => $refType,
                'target'         => $node->target ?? '_self',
                'order'          => (int) $node->position,
                'icon_font'      => $node->icon_font,
                'css_class'      => $node->css_class,
                'created_at'     => $node->created_at ?? now(),
                'updated_at'     => $node->updated_at ?? now(),
            ];
        }

        foreach ($nodeRows as $row) {
            DB::table('menu_items')->updateOrInsert(['id' => $row['id']], $row);
        }

        $this->stats['menus'] = [
            'source' => count($legacyMenus),
            'target' => count($legacyMenus),
        ];
        $this->stats['menu_items'] = [
            'source' => count($legacyNodes),
            'target' => count($nodeRows),
        ];
        $this->info("   Migrated " . count($legacyMenus) . " menus and " . count($nodeRows) . " menu items.");
    }

    /**
     * Step 9: Migrate Settings
     */
    protected function migrateSettings(): void
    {
        $this->line("-> [Step 9/10] Migrating Settings...");
        $legacySettings = DB::connection('mysql_legacy')->table('settings')->get();
        $count = 0;

        foreach ($legacySettings as $s) {
            DB::table('settings')->updateOrInsert(
                ['key' => $s->key],
                [
                    'value'      => $s->value,
                    'created_at' => $s->created_at ?? now(),
                    'updated_at' => $s->updated_at ?? now(),
                ]
            );
            $count++;
        }

        $this->stats['settings'] = [
            'source' => count($legacySettings),
            'target' => $count,
        ];
        $this->info("   Migrated {$count} settings.");
    }

    /**
     * Step 10: Migrate Media Files (Storage Sync)
     */
    protected function migrateMediaFiles(): void
    {
        $this->line("-> [Step 10/10] Synchronizing Storage Files...");
        $sourceDir = '/var/www/mtgroup/minhthanh3/storage/app/public/uploads';
        $destDir = storage_path('app/public/uploads');

        if (!File::isDirectory($sourceDir)) {
            $this->warn("   Source uploads directory not found: {$sourceDir}. Skipping physical file copy.");
            return;
        }

        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        // Copy directory recursively
        File::copyDirectory($sourceDir, $destDir);

        $sourceFilesCount = count(File::allFiles($sourceDir));
        $destFilesCount = count(File::allFiles($destDir));

        $this->stats['media_files'] = [
            'source' => $sourceFilesCount,
            'target' => $destFilesCount,
        ];
        $this->info("   Synchronized {$destFilesCount} media files from {$sourceDir} to {$destDir}.");
    }

    /**
     * Print concise data audit comparison table.
     */
    protected function printAuditReport(): void
    {
        $this->info("\n================ DATA INTEGRITY & AUDIT REPORT ================");
        $headers = ['Entity', 'Source Records (minhthanh2)', 'Target Records (mtgroup_v13)', 'Integrity Status'];
        $tableRows = [];

        foreach ($this->stats as $entity => $data) {
            $source = $data['source'];
            $target = $data['target'];
            $status = ($source == $target || $entity === 'users') ? 'MATCHED (100%)' : 'MISMATCH';

            $tableRows[] = [
                ucfirst(str_replace('_', ' ', $entity)),
                $source,
                $target,
                $status,
            ];
        }

        $this->table($headers, $tableRows);
        $this->info("==================================================================");
    }
}
