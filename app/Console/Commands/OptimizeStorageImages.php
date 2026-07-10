<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeStorageImages extends Command
{
    protected $signature = 'images:optimize';

    protected $description = 'Resize and compress oversized images already stored on the public disk';

    public function handle(): int
    {
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $files = collect(Storage::disk('public')->allFiles())
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $extensions, true));

        $totalBefore = 0;
        $totalAfter = 0;

        $this->withProgressBar($files, function (string $path) use (&$totalBefore, &$totalAfter) {
            $before = Storage::disk('public')->size($path);
            ImageOptimizer::optimize('public', $path);
            clearstatcache();
            $after = Storage::disk('public')->size($path);

            $totalBefore += $before;
            $totalAfter += $after;
        });

        $this->newLine(2);
        $this->info(sprintf(
            'Optimized %d images: %s -> %s (saved %s)',
            $files->count(),
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
