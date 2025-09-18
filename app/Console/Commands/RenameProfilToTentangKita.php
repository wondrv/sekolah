<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Block;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RenameProfilToTentangKami extends Command
{
    protected $signature = 'cms:rename-profil {--dry-run : Show changes without applying}';
    protected $description = 'Rename all occurrences of "profil" to "tentang-kami" in titles, slugs, URLs where appropriate.';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $this->info(($dry ? '[DRY RUN] ' : '') . 'Renaming "profil" → "tentang-kami"...');

        DB::beginTransaction();
        try {
            // 1) MenuItem titles/urls
            $items = MenuItem::query()->get();
            $menuUpdates = 0;
            foreach ($items as $item) {
                $origTitle = $item->title;
                $origUrl = $item->url;
                $newTitle = $origTitle === 'Profil' ? 'Tentang Kita' : $origTitle;
                $newUrl = $origUrl ? str_replace('/profil', '/tentang-kami', $origUrl) : $origUrl;

                if ($newTitle !== $origTitle || $newUrl !== $origUrl) {
                    $menuUpdates++;
                    $this->line("MenuItem #{$item->id}: '{$origTitle}' -> '{$newTitle}', url: '{$origUrl}' -> '{$newUrl}'");
                    if (!$dry) {
                        $item->title = $newTitle;
                        $item->url = $newUrl;
                        $item->save();
                    }
                }
            }

            // 2) Page slugs starting with 'profil'
            $pages = Page::query()->where('slug', 'like', 'profil%')->get();
            $pageUpdates = 0;
            foreach ($pages as $page) {
                $oldSlug = $page->slug;
                $newSlug = Str::startsWith($oldSlug, 'profil')
                    ? Str::replaceFirst('profil', 'tentang-kami', $oldSlug)
                    : $oldSlug;
                if ($newSlug !== $oldSlug) {
                    $pageUpdates++;
                    $this->line("Page #{$page->id} slug: '{$oldSlug}' -> '{$newSlug}'");
                    if (!$dry) {
                        $page->slug = $newSlug;
                        $page->save();
                    }
                }
            }

            // 3) Blocks JSON data containing '/profil'
            if (class_exists(Block::class)) {
                $blocks = Block::query()->where('data', 'like', '%/profil%')->get();
                $blockUpdates = 0;
                foreach ($blocks as $block) {
                    $old = $block->data;
                    $json = json_encode($old);
                    $jsonNew = str_replace('/profil', '/tentang-kami', $json);
                    if ($jsonNew !== $json) {
                        $blockUpdates++;
                        $this->line("Block #{$block->id} data updated");
                        if (!$dry) {
                            $block->data = json_decode($jsonNew, true);
                            $block->save();
                        }
                    }
                }
            } else {
                $blockUpdates = 0;
            }

            $this->info("Summary: MenuItems updated: {$menuUpdates}, Pages updated: {$pageUpdates}, Blocks updated: {$blockUpdates}");

            if ($dry) {
                DB::rollBack();
                $this->info('Dry run finished. No changes applied.');
            } else {
                DB::commit();
                $this->info('Rename completed successfully.');
            }
            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
