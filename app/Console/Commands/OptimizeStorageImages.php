<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OptimizeStorageImages extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Resize and compress oversized images already stored on the public disk';

    /**
     * Tables (and their image_path column) that store a reference to a
     * path on the public disk — kept in sync when optimize() renames a
     * file (opaque PNG converted to JPEG).
     */
    protected const IMAGE_PATH_TABLES = [
        'article_images',
        'hero_slides',
        'news_items',
        'page_heroes',
        'page_sections',
    ];

    public function handle(): int
    {
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $files = collect(Storage::disk('public')->allFiles())
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true));

        $totalBefore = 0;
        $totalAfter = 0;
        $renamed = 0;

        $this->withProgressBar($files, function (string $path) use (&$totalBefore, &$totalAfter, &$renamed) {
            $before = Storage::disk('public')->size($path);
            $newPath = ImageOptimizer::optimize('public', $path);

            if ($newPath !== $path) {
                foreach (self::IMAGE_PATH_TABLES as $table) {
                    DB::table($table)->where('image_path', $path)->update(['image_path' => $newPath]);
                }
                $renamed++;
            }

            clearstatcache();
            $after = Storage::disk('public')->size($newPath);

            $totalBefore += $before;
            $totalAfter += $after;
        });

        $this->newLine(2);
        $this->info(sprintf(
            'Optimized %d images (%d converted PNG -> JPEG): %s -> %s (saved %s)',
            $files->count(),
            $renamed,
            $this->formatBytes($totalBefore),
            $this->formatBytes($totalAfter),
            $this->formatBytes($totalBefore - $totalAfter)
        ));

        return self::SUCCESS;
    }

    protected function formatBytes(int $bytes): string
    {
        return number_format($bytes / 1024 / 1024, 2) . ' MB';
    }
}
