<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckLegacySlugs extends Command
{
    protected $signature = 'app:check-legacy-slugs {--limit=0 : Limit number of slugs to check}';
    protected $description = 'Verify 100% of legacy slugs from minhthanh2 against Laravel 13 routing';

    public function handle(Kernel $kernel): int
    {
        $this->info('Starting legacy slug audit against mtgroup_v13...');

        $legacySlugs = DB::connection('mysql_legacy')->table('slugs')->get();
        $total = $legacySlugs->count();
        $this->info("Found {$total} legacy slugs in minhthanh2.");

        $limit = (int) $this->option('limit');
        if ($limit > 0) {
            $legacySlugs = $legacySlugs->take($limit);
            $this->info("Checking sample of {$limit} slugs.");
        }

        $count200 = 0;
        $countDraft404 = 0;
        $countErrors = 0;
        $errors = [];

        $bar = $this->output->createProgressBar($legacySlugs->count());
        $bar->start();

        foreach ($legacySlugs as $slug) {
            $url = $slug->prefix ? '/' . $slug->prefix . '/' . $slug->key : '/' . $slug->key;
            $request = Request::create($url, 'GET');

            try {
                $response = $kernel->handle($request);
                $statusCode = $response->getStatusCode();

                $refClass = match ($slug->reference) {
                    'post' => Post::class,
                    'category' => Category::class,
                    'page' => Page::class,
                    'tag' => Tag::class,
                    default => null,
                };

                $model = $refClass ? $refClass::find($slug->reference_id) : null;
                $status = $model ? ($model->status ?? 'publish') : 'missing';

                if ($statusCode === 200) {
                    $count200++;
                } elseif ($statusCode === 404 && $status === 'draft') {
                    $countDraft404++;
                } else {
                    $countErrors++;
                    $errors[] = [
                        'url' => $url,
                        'code' => $statusCode,
                        'reference' => $slug->reference,
                        'id' => $slug->reference_id,
                        'status' => $status,
                    ];
                }
            } catch (\Throwable $e) {
                $countErrors++;
                $errors[] = [
                    'url' => $url,
                    'code' => 500,
                    'error' => $e->getMessage(),
                ];
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count', 'Percentage'],
            [
                ['200 OK (Published)', $count200, number_format($count200 / $legacySlugs->count() * 100, 2) . '%'],
                ['404 Not Found (Legitimate Drafts)', $countDraft404, number_format($countDraft404 / $legacySlugs->count() * 100, 2) . '%'],
                ['Errors / Unexpected Status', $countErrors, number_format($countErrors / $legacySlugs->count() * 100, 2) . '%'],
                ['Total Checked', $legacySlugs->count(), '100%'],
            ]
        );

        if (!empty($errors)) {
            $this->error('Discrepancies found:');
            $this->table(['URL', 'Code', 'Ref', 'ID', 'DB Status'], array_map(function ($err) {
                return [
                    $err['url'],
                    $err['code'],
                    $err['reference'] ?? 'N/A',
                    $err['id'] ?? 'N/A',
                    $err['status'] ?? ($err['error'] ?? 'N/A'),
                ];
            }, $errors));
            return Command::FAILURE;
        }

        $this->info('Audit completed successfully with ZERO unexpected 404s!');
        return Command::SUCCESS;
    }
}

